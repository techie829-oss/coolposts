<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnhancedCsrfMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip CSRF for GET requests and API routes
        if ($request->isMethod('GET') || $request->is('api/*')) {
            return $next($request);
        }

        // Check if CSRF token is present
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (!$token) {
            Log::warning('CSRF token missing', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
            ]);
            return response('CSRF token missing', 419);
        }

        // Verify CSRF token
        if (!hash_equals(Session::token(), $token)) {
            Log::warning('CSRF token mismatch', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'session_token' => Session::token(),
                'provided_token' => $token,
            ]);
            return response('CSRF token mismatch', 419);
        }

        // Check for double submission (replay attack protection)
        if ($this->isDoubleSubmission($request)) {
            Log::warning('Potential double submission detected', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
            return response('Request already processed', 409);
        }

        // Mark this token as used
        $this->markTokenAsUsed($request, $token);

        // Add security headers
        $response = $next($request);

        return $response->header('X-Content-Type-Options', 'nosniff')
                       ->header('X-Frame-Options', 'DENY')
                       ->header('X-XSS-Protection', '1; mode=block')
                       ->header('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    /**
     * Check for double submission
     */
    protected function isDoubleSubmission(Request $request): bool
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        $key = 'csrf_used_' . hash('sha256', $token);

        return cache()->has($key);
    }

    /**
     * Mark token as used
     */
    protected function markTokenAsUsed(Request $request, string $token): void
    {
        $key = 'csrf_used_' . hash('sha256', $token);
        $expiry = now()->addMinutes(30); // Token expires after 30 minutes

        cache()->put($key, true, $expiry);
    }
}
