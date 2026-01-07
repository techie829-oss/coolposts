<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $ttl = 300): Response
    {
        // Skip caching for non-GET requests
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        // Skip caching for authenticated users (they get personalized content)
        if (auth()->check()) {
            return $next($request);
        }

        // Skip caching for admin routes
        if ($request->is('admin/*')) {
            return $next($request);
        }

        // Generate cache key based on request
        $cacheKey = $this->generateCacheKey($request);

        // Try to get cached response
        $cachedResponse = Cache::get($cacheKey);
        if ($cachedResponse !== null) {
            Log::info("Cache hit for key: {$cacheKey}");
            return $cachedResponse;
        }

        // Get the response
        $response = $next($request);

        // Cache successful responses
        if ($response->getStatusCode() === 200) {
            $this->cacheResponse($cacheKey, $response, $ttl);
        }

        return $response;
    }

    /**
     * Generate cache key for the request
     */
    protected function generateCacheKey(Request $request): string
    {
        $key = 'response:' . md5($request->fullUrl());

        // Add query parameters to cache key
        if ($request->query()) {
            $key .= ':' . md5(serialize($request->query()));
        }

        return $key;
    }

    /**
     * Cache the response
     */
    protected function cacheResponse(string $key, Response $response, int $ttl): void
    {
        try {
            // Clone the response to avoid issues with the original
            $cachedResponse = clone $response;

            // Add cache headers
            $cachedResponse->headers->set('X-Cache', 'MISS');
            $cachedResponse->headers->set('Cache-Control', "public, max-age={$ttl}");

            Cache::put($key, $cachedResponse, $ttl);

            Log::info("Cached response for key: {$key} (TTL: {$ttl}s)");
        } catch (\Exception $e) {
            Log::error("Failed to cache response: " . $e->getMessage());
        }
    }
}
