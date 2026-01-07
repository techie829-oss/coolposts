<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    /**
     * Verify reCAPTCHA token
     *
     * @param string $token
     * @param string $action
     * @return array
     */
    public function verify(string $token, string $action = null): array
    {
        try {
            $response = Http::asForm()->post(config('recaptcha.verify_url'), [
                'secret' => config('recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->successful()) {
                Log::warning('reCAPTCHA API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to verify reCAPTCHA',
                    'score' => 0,
                ];
            }

            $data = $response->json();

            // Check if verification was successful
            if (!$data['success']) {
                Log::warning('reCAPTCHA verification failed', [
                    'error_codes' => $data['error-codes'] ?? [],
                    'ip' => request()->ip(),
                ]);

                return [
                    'success' => false,
                    'message' => 'reCAPTCHA verification failed',
                    'score' => 0,
                    'errors' => $data['error-codes'] ?? [],
                ];
            }

            // Check action if specified
            if ($action && isset($data['action']) && $data['action'] !== $action) {
                Log::warning('reCAPTCHA action mismatch', [
                    'expected' => $action,
                    'received' => $data['action'] ?? 'none',
                    'ip' => request()->ip(),
                ]);

                return [
                    'success' => false,
                    'message' => 'reCAPTCHA action mismatch',
                    'score' => 0,
                ];
            }

            // Check score threshold
            $score = $data['score'] ?? 0;
            $threshold = config('recaptcha.score_threshold', 0.5);

            if ($score < $threshold) {
                Log::info('reCAPTCHA score below threshold', [
                    'score' => $score,
                    'threshold' => $threshold,
                    'ip' => request()->ip(),
                ]);

                return [
                    'success' => false,
                    'message' => 'reCAPTCHA score too low',
                    'score' => $score,
                    'threshold' => $threshold,
                ];
            }

            // Success
            Log::info('reCAPTCHA verification successful', [
                'score' => $score,
                'action' => $data['action'] ?? 'none',
                'ip' => request()->ip(),
            ]);

            return [
                'success' => true,
                'message' => 'reCAPTCHA verification successful',
                'score' => $score,
                'action' => $data['action'] ?? 'none',
            ];

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'message' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);

            return [
                'success' => false,
                'message' => 'reCAPTCHA verification error',
                'score' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if reCAPTCHA is properly configured
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty(config('recaptcha.site_key')) && !empty(config('recaptcha.secret_key'));
    }

    /**
     * Get reCAPTCHA site key for frontend
     *
     * @return string
     */
    public function getSiteKey(): string
    {
        return config('recaptcha.site_key', '');
    }
}
