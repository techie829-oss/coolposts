<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class ApiKeySecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get API key from header or query parameter
        $apiKey = $request->header('X-API-Key') ?: $request->query('api_key');

        if (!$apiKey) {
            Log::warning('API key missing', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);
            return response()->json(['error' => 'API key required'], 401);
        }

        // Validate API key format
        if (!$this->isValidApiKeyFormat($apiKey)) {
            Log::warning('Invalid API key format', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'api_key' => substr($apiKey, 0, 10) . '...',
            ]);
            return response()->json(['error' => 'Invalid API key format'], 400);
        }

        // Check if API key is rate limited
        if ($this->isApiKeyRateLimited($apiKey)) {
            Log::warning('API key rate limited', [
                'api_key' => substr($apiKey, 0, 10) . '...',
                'ip' => $request->ip(),
            ]);
            return response()->json(['error' => 'API key rate limited'], 429);
        }

        // Find user by API key
        $user = $this->findUserByApiKey($apiKey);

        if (!$user) {
            Log::warning('Invalid API key', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'api_key' => substr($apiKey, 0, 10) . '...',
            ]);
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        // Check if user is active
        if (!$user->is_active) {
            Log::warning('Inactive user API access attempt', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);
            return response()->json(['error' => 'Account is inactive'], 403);
        }

        // Check API key permissions
        if (!$this->hasApiPermission($user, $request)) {
            Log::warning('API permission denied', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
            return response()->json(['error' => 'Insufficient permissions'], 403);
        }

        // Log successful API access
        $this->logApiAccess($user, $request, $apiKey);

        // Add user to request for controllers
        $request->attributes->set('api_user', $user);

        // Increment API usage counter
        $this->incrementApiUsage($apiKey);

        return $next($request);
    }

    /**
     * Validate API key format
     */
    protected function isValidApiKeyFormat(string $apiKey): bool
    {
        // API key should be 64 characters long and contain only hex characters
        return strlen($apiKey) === 64 && ctype_xdigit($apiKey);
    }

    /**
     * Check if API key is rate limited
     */
    protected function isApiKeyRateLimited(string $apiKey): bool
    {
        $key = 'api_rate_limit:' . $apiKey;
        $maxAttempts = 1000; // 1000 requests per hour
        $decayMinutes = 60;

        if (Cache::has($key) && Cache::get($key) >= $maxAttempts) {
            return true;
        }

        Cache::increment($key);
        Cache::expire($key, $decayMinutes * 60);

        return false;
    }

    /**
     * Find user by API key
     */
    protected function findUserByApiKey(string $apiKey): ?User
    {
        return User::where('api_key', $apiKey)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Check if user has API permission for the requested action
     */
    protected function hasApiPermission(User $user, Request $request): bool
    {
        $path = $request->path();
        $method = $request->method();

        // Admin users have full access
        if ($user->role === 'admin') {
            return true;
        }

        // Check specific endpoint permissions
        if (str_contains($path, 'admin')) {
            return false; // Only admins can access admin endpoints
        }

        // Check method permissions
        if (in_array($method, ['DELETE', 'PUT', 'PATCH'])) {
            // Check if user owns the resource
            return $this->canUserModifyResource($user, $request);
        }

        return true;
    }

    /**
     * Check if user can modify a specific resource
     */
    protected function canUserModifyResource(User $user, Request $request): bool
    {
        $path = $request->path();

        // Check link ownership
        if (str_contains($path, 'links')) {
            $linkId = $request->route('link');
            if ($linkId) {
                return $user->links()->where('id', $linkId)->exists();
            }
        }

        // Check subscription ownership
        if (str_contains($path, 'subscriptions')) {
            $subscriptionId = $request->route('subscription');
            if ($subscriptionId) {
                return $user->subscriptions()->where('id', $subscriptionId)->exists();
            }
        }

        return false;
    }

    /**
     * Log API access
     */
    protected function logApiAccess(User $user, Request $request, string $apiKey): void
    {
        Log::info('API access', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'api_key' => substr($apiKey, 0, 10) . '...',
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Increment API usage counter
     */
    protected function incrementApiUsage(string $apiKey): void
    {
        $key = 'api_usage:' . $apiKey;
        $today = now()->format('Y-m-d');

        Cache::increment($key . ':' . $today);
        Cache::expire($key . ':' . $today, 86400); // 24 hours
    }
}
