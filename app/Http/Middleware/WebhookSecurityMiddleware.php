<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\RateLimiter;

class WebhookSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $gateway = $this->detectGateway($request);

        if (!$gateway) {
            Log::warning('Webhook from unknown gateway', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ]);
            return response('Unauthorized', 401);
        }

        // Verify webhook signature
        if (!$this->verifyWebhookSignature($request, $gateway)) {
            Log::warning('Webhook signature verification failed', [
                'gateway' => $gateway->slug,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);
            return response('Unauthorized', 401);
        }

        // Rate limit webhooks
        if ($this->isWebhookRateLimited($request, $gateway)) {
            Log::warning('Webhook rate limit exceeded', [
                'gateway' => $gateway->slug,
                'ip' => $request->ip(),
            ]);
            return response('Too Many Requests', 429);
        }

        // Add gateway info to request for controllers
        $request->attributes->set('webhook_gateway', $gateway);

        return $next($request);
    }

    /**
     * Detect payment gateway from request
     */
    protected function detectGateway(Request $request): ?PaymentGateway
    {
        $path = $request->path();
        $gatewaySlug = null;

        if (str_contains($path, 'stripe')) {
            $gatewaySlug = 'stripe';
        } elseif (str_contains($path, 'paypal')) {
            $gatewaySlug = 'paypal';
        } elseif (str_contains($path, 'paytm')) {
            $gatewaySlug = 'paytm';
        } elseif (str_contains($path, 'razorpay')) {
            $gatewaySlug = 'razorpay';
        }

        if (!$gatewaySlug) {
            return null;
        }

        return PaymentGateway::where('slug', $gatewaySlug)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Verify webhook signature
     */
    protected function verifyWebhookSignature(Request $request, PaymentGateway $gateway): bool
    {
        $config = $gateway->config;

        switch ($gateway->slug) {
            case 'stripe':
                return $this->verifyStripeSignature($request, $config);
            case 'paypal':
                return $this->verifyPayPalSignature($request, $config);
            case 'paytm':
                return $this->verifyPaytmSignature($request, $config);
            case 'razorpay':
                return $this->verifyRazorpaySignature($request, $config);
            default:
                return false;
        }
    }

    /**
     * Verify Stripe webhook signature
     */
    protected function verifyStripeSignature(Request $request, array $config): bool
    {
        $signature = $request->header('Stripe-Signature');
        $payload = $request->getContent();
        $secret = $config['webhook_secret'] ?? null;

        if (!$signature || !$secret) {
            return false;
        }

        try {
            // In production, use Stripe's official library
            $timestamp = null;
            $signatures = explode(',', $signature);

            foreach ($signatures as $sig) {
                if (strpos($sig, 't=') === 0) {
                    $timestamp = substr($sig, 2);
                }
            }

            if (!$timestamp || abs(time() - $timestamp) > 300) { // 5 minutes tolerance
                return false;
            }

            $expectedSignature = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);

            foreach ($signatures as $sig) {
                if (strpos($sig, 'v1=') === 0) {
                    $receivedSignature = substr($sig, 3);
                    if (hash_equals($expectedSignature, $receivedSignature)) {
                        return true;
                    }
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Stripe signature verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify PayPal webhook signature
     */
    protected function verifyPayPalSignature(Request $request, array $config): bool
    {
        // PayPal webhook verification is complex and requires their SDK
        // For now, we'll implement basic verification
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return false;
        }

        // Basic verification - in production, use PayPal's webhook verification
        return str_starts_with($authHeader, 'Bearer ');
    }

    /**
     * Verify Paytm webhook signature
     */
    protected function verifyPaytmSignature(Request $request, array $config): bool
    {
        $checksum = $request->input('CHECKSUMHASH');
        $payload = $request->except('CHECKSUMHASH');
        $merchantKey = $config['merchant_key'] ?? null;

        if (!$checksum || !$merchantKey) {
            return false;
        }

        // Generate checksum for verification
        $generatedChecksum = $this->generatePaytmChecksum($payload, $merchantKey);

        return hash_equals($checksum, $generatedChecksum);
    }

    /**
     * Verify Razorpay webhook signature
     */
    protected function verifyRazorpaySignature(Request $request, array $config): bool
    {
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();
        $secret = $config['webhook_secret'] ?? null;

        if (!$signature || !$secret) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Generate Paytm checksum for verification
     */
    protected function generatePaytmChecksum(array $params, string $merchantKey): string
    {
        // Sort parameters alphabetically
        ksort($params);

        // Create string for checksum
        $checksumString = '';
        foreach ($params as $key => $value) {
            if ($key !== 'CHECKSUMHASH') {
                $checksumString .= $key . '=' . $value . '&';
            }
        }
        $checksumString = rtrim($checksumString, '&');

        // Generate checksum
        return strtoupper(hash('sha256', $merchantKey . '|' . $checksumString . '|' . $merchantKey));
    }

    /**
     * Check if webhook is rate limited
     */
    protected function isWebhookRateLimited(Request $request, PaymentGateway $gateway): bool
    {
        $key = 'webhook_rate_limit:' . $gateway->slug . ':' . $request->ip();
        $maxAttempts = 100; // 100 webhooks per hour per IP
        $decayMinutes = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return true;
        }

        RateLimiter::hit($key, $decayMinutes * 60);
        return false;
    }
}
