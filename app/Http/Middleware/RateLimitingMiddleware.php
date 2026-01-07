<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RateLimitingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName() ?? '';
        $user = $request->user();
        $ip = $request->ip();

        // Define rate limits based on route type
        $limits = $this->getRateLimits($routeName, $user);

        foreach ($limits as $key => $limit) {
            $key = $this->resolveRequestSignature($request, $key, $user, $ip);

            if (RateLimiter::tooManyAttempts($key, $limit['max_attempts'])) {
                $this->logRateLimitExceeded($request, $key, $limit);

                return response()->json([
                    'error' => 'Rate limit exceeded',
                    'message' => 'Too many requests. Please try again later.',
                    'retry_after' => RateLimiter::availableIn($key),
                ], 429)->header('Retry-After', RateLimiter::availableIn($key));
            }

            RateLimiter::hit($key, $limit['decay_minutes'] * 60);
        }

        return $next($request);
    }

    /**
     * Get rate limits based on route type
     */
    protected function getRateLimits(string $routeName, $user): array
    {
        $limits = [];

        // Authentication routes
        if (str_contains($routeName, 'login') || str_contains($routeName, 'register')) {
            $limits['auth'] = [
                'max_attempts' => 5,
                'decay_minutes' => 15,
                'description' => 'Authentication attempts'
            ];
        }

        // Payment routes
        if (str_contains($routeName, 'payment') || str_contains($routeName, 'subscription')) {
            $limits['payment'] = [
                'max_attempts' => 10,
                'decay_minutes' => 60,
                'description' => 'Payment attempts'
            ];
        }

        // Link creation
        if (str_contains($routeName, 'links.create')) {
            $limits['link_creation'] = [
                'max_attempts' => 50,
                'decay_minutes' => 60,
                'description' => 'Link creation'
            ];
        }

        // API routes
        if (str_contains($routeName, 'api.')) {
            $limits['api'] = [
                'max_attempts' => 100,
                'decay_minutes' => 60,
                'description' => 'API requests'
            ];
        }

        // Admin routes
        if (str_contains($routeName, 'admin.')) {
            $limits['admin'] = [
                'max_attempts' => 200,
                'decay_minutes' => 60,
                'description' => 'Admin operations'
            ];
        }

        // Default rate limit for all routes
        if (empty($limits)) {
            $limits['default'] = [
                'max_attempts' => 1000,
                'decay_minutes' => 60,
                'description' => 'General requests'
            ];
        }

        return $limits;
    }

    /**
     * Resolve request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request, string $key, $user, string $ip): string
    {
        $identifier = $user ? $user->id : $ip;

        return sha1($key . '|' . $identifier . '|' . $request->userAgent());
    }

    /**
     * Log rate limit exceeded attempts
     */
    protected function logRateLimitExceeded(Request $request, string $key, array $limit): void
    {
        Log::warning('Rate limit exceeded', [
            'key' => $key,
            'limit' => $limit,
            'ip' => $request->ip(),
            'user_id' => $request->user()?->id,
            'route' => $request->route()->getName(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ]);
    }
}
