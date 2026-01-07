<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestLoggingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        // Process the request
        $response = $next($request);

        // Calculate response time
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000); // Convert to milliseconds

        // Log the request asynchronously to avoid blocking
        $this->logRequestAsync($request, $response, $responseTime);

        return $response;
    }

    /**
     * Log request asynchronously
     */
    protected function logRequestAsync(Request $request, $response, int $responseTime): void
    {
        try {
            // Get response status code
            $statusCode = $response->getStatusCode();

            // Prepare request data
            $requestData = [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'response_time' => $responseTime,
                'status_code' => $statusCode,
                'request_data' => $this->sanitizeRequestData($request),
                'response_data' => $this->sanitizeResponseData($response),
                'session_id' => $request->session()->getId(),
                'user_id' => $request->user()?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert into database
            DB::table('request_logs')->insert($requestData);

        } catch (\Exception $e) {
            // Fallback to Laravel logging if database fails
            Log::error('Request logging failed: ' . $e->getMessage(), [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'response_time' => $responseTime,
            ]);
        }
    }

    /**
     * Sanitize request data for logging
     */
    protected function sanitizeRequestData(Request $request): ?string
    {
        $data = [
            'headers' => $request->headers->all(),
            'query' => $request->query(),
            'post' => $request->post(),
        ];

        // Remove sensitive data
        unset($data['headers']['authorization']);
        unset($data['headers']['cookie']);
        unset($data['post']['password']);
        unset($data['post']['password_confirmation']);

        return json_encode($data);
    }

    /**
     * Sanitize response data for logging
     */
    protected function sanitizeResponseData($response): ?string
    {
        // Only log basic response info, not full content
        $data = [
            'status_code' => $response->getStatusCode(),
            'content_type' => $response->headers->get('content-type'),
            'content_length' => strlen($response->getContent()),
        ];

        return json_encode($data);
    }
}
