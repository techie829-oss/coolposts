<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidationService
{
    /**
     * Validate link creation data
     */
    public function validateLinkCreation(array $data): array
    {
        $rules = [
            'title' => 'required|string|max:255|min:3',
            'original_url' => [
                'required',
                'url',
                'max:2048',
                function ($attribute, $value, $fail) {
                    if (!$this->isValidUrl($value)) {
                        $fail('The URL format is invalid or contains suspicious content.');
                    }
                }
            ],
            'ad_type' => 'required|in:no_ads,short_ads,long_ads',
            'ad_duration' => 'nullable|integer|min:5|max:300',
            'is_protected' => 'boolean',
            'password' => 'nullable|string|min:6|max:255',
        ];

        $messages = [
            'title.required' => 'Link title is required.',
            'title.min' => 'Link title must be at least 3 characters.',
            'title.max' => 'Link title cannot exceed 255 characters.',
            'original_url.required' => 'Original URL is required.',
            'original_url.url' => 'Please enter a valid URL.',
            'original_url.max' => 'URL is too long.',
            'ad_type.required' => 'Ad type selection is required.',
            'ad_type.in' => 'Invalid ad type selected.',
            'ad_duration.min' => 'Ad duration must be at least 5 seconds.',
            'ad_duration.max' => 'Ad duration cannot exceed 300 seconds.',
            'password.min' => 'Password must be at least 6 characters.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate user registration data
     */
    public function validateUserRegistration(array $data): array
    {
        $rules = [
            'name' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'preferred_currency' => 'required|in:INR,USD',
            'g-recaptcha-response' => 'required|recaptcha',
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'name.regex' => 'Name can only contain letters and spaces.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'preferred_currency.required' => 'Please select your preferred currency.',
            'preferred_currency.in' => 'Invalid currency selected.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
            'g-recaptcha-response.recaptcha' => 'reCAPTCHA verification failed.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate payment initiation data
     */
    public function validatePaymentInitiation(array $data): array
    {
        $rules = [
            'plan_id' => 'required|exists:subscription_plans,id',
            'gateway' => 'required|string|exists:payment_gateways,slug',
            'currency' => 'required|in:INR,USD',
        ];

        $messages = [
            'plan_id.required' => 'Subscription plan is required.',
            'plan_id.exists' => 'Selected plan is invalid.',
            'gateway.required' => 'Payment gateway is required.',
            'gateway.exists' => 'Selected payment gateway is invalid.',
            'currency.required' => 'Currency selection is required.',
            'currency.in' => 'Invalid currency selected.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate referral data
     */
    public function validateReferral(array $data): array
    {
        $rules = [
            'referral_code' => 'required|string|max:20|exists:users,referral_code',
        ];

        $messages = [
            'referral_code.required' => 'Referral code is required.',
            'referral_code.max' => 'Referral code is too long.',
            'referral_code.exists' => 'Invalid referral code.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate admin user update data
     */
    public function validateAdminUserUpdate(array $data): array
    {
        $rules = [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255',
            'role' => 'required|in:user,admin,moderator',
            'is_active' => 'boolean',
            'preferred_currency' => 'required|in:INR,USD',
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'role.required' => 'User role is required.',
            'role.in' => 'Invalid role selected.',
            'preferred_currency.required' => 'Currency selection is required.',
            'preferred_currency.in' => 'Invalid currency selected.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate global settings update
     */
    public function validateGlobalSettings(array $data): array
    {
        $rules = [
            'platform_name' => 'required|string|max:100',
            'platform_description' => 'nullable|string|max:500',
            'default_currency' => 'required|in:INR,USD',
            'supported_currencies' => 'required|array|min:1',
            'supported_currencies.*' => 'in:INR,USD',
            'short_ads_min_duration' => 'required|integer|min:5|max:300',
            'short_ads_max_duration' => 'required|integer|min:5|max:300',
            'long_ads_min_duration' => 'required|integer|min:5|max:300',
            'long_ads_max_duration' => 'required|integer|min:5|max:300',
            'no_ads_rate_inr' => 'required|numeric|min:0',
            'no_ads_rate_usd' => 'required|numeric|min:0',
            'short_ads_rate_inr' => 'required|numeric|min:0',
            'short_ads_rate_usd' => 'required|numeric|min:0',
            'long_ads_rate_inr' => 'required|numeric|min:0',
            'long_ads_rate_usd' => 'required|numeric|min:0',
            'referral_commission_rate' => 'required|numeric|min:0|max:100',
            'minimum_payout_inr' => 'required|numeric|min:0',
            'minimum_payout_usd' => 'required|numeric|min:0',
        ];

        $messages = [
            'platform_name.required' => 'Platform name is required.',
            'platform_name.max' => 'Platform name cannot exceed 100 characters.',
            'default_currency.required' => 'Default currency is required.',
            'supported_currencies.required' => 'Supported currencies are required.',
            'supported_currencies.min' => 'At least one currency must be supported.',
            'short_ads_min_duration.min' => 'Short ads minimum duration must be at least 5 seconds.',
            'short_ads_max_duration.max' => 'Short ads maximum duration cannot exceed 300 seconds.',
            'long_ads_min_duration.min' => 'Long ads minimum duration must be at least 5 seconds.',
            'long_ads_max_duration.max' => 'Long ads maximum duration cannot exceed 300 seconds.',
            'referral_commission_rate.max' => 'Referral commission rate cannot exceed 100%.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Check if URL is valid and safe
     */
    protected function isValidUrl(string $url): bool
    {
        // Basic URL validation
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check for suspicious patterns
        $suspiciousPatterns = [
            'javascript:',
            'data:',
            'vbscript:',
            'onload=',
            'onerror=',
            'onclick=',
            'onmouseover=',
            'eval(',
            'document.cookie',
            'window.location',
        ];

        $urlLower = strtolower($url);
        foreach ($suspiciousPatterns as $pattern) {
            if (str_contains($urlLower, $pattern)) {
                return false;
            }
        }

        // Check for valid protocols
        $allowedProtocols = ['http', 'https'];
        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['scheme']) || !in_array($parsedUrl['scheme'], $allowedProtocols)) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize user input
     */
    public function sanitizeInput(string $input): string
    {
        // Remove HTML tags
        $input = strip_tags($input);
        
        // Convert special characters
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);
        
        // Remove control characters
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        return trim($input);
    }

    /**
     * Validate and sanitize file upload
     */
    public function validateFileUpload($file, array $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], int $maxSize = 2048): array
    {
        $rules = [
            'file' => 'required|file|mimes:' . implode(',', $allowedTypes) . '|max:' . $maxSize,
        ];

        $messages = [
            'file.required' => 'File is required.',
            'file.file' => 'Uploaded item is not a valid file.',
            'file.mimes' => 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes),
            'file.max' => 'File size too large. Maximum size: ' . $maxSize . 'KB',
        ];

        $validator = Validator::make(['file' => $file], $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return [
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
        ];
    }
}
