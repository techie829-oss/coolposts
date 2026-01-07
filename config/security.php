<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all security-related configuration settings
    | for the link sharing application.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    */
    'rate_limiting' => [
        'auth' => [
            'max_attempts' => env('RATE_LIMIT_AUTH_ATTEMPTS', 5),
            'decay_minutes' => env('RATE_LIMIT_AUTH_DECAY', 15),
        ],
        'payment' => [
            'max_attempts' => env('RATE_LIMIT_PAYMENT_ATTEMPTS', 10),
            'decay_minutes' => env('RATE_LIMIT_PAYMENT_DECAY', 60),
        ],
        'link_creation' => [
            'max_attempts' => env('RATE_LIMIT_LINK_CREATION_ATTEMPTS', 50),
            'decay_minutes' => env('RATE_LIMIT_LINK_CREATION_DECAY', 60),
        ],
        'api' => [
            'max_attempts' => env('RATE_LIMIT_API_ATTEMPTS', 100),
            'decay_minutes' => env('RATE_LIMIT_API_DECAY', 60),
        ],
        'admin' => [
            'max_attempts' => env('RATE_LIMIT_ADMIN_ATTEMPTS', 200),
            'decay_minutes' => env('RATE_LIMIT_ADMIN_DECAY', 60),
        ],
        'webhook' => [
            'max_attempts' => env('RATE_LIMIT_WEBHOOK_ATTEMPTS', 100),
            'decay_minutes' => env('RATE_LIMIT_WEBHOOK_DECAY', 60),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CSRF Protection Configuration
    |--------------------------------------------------------------------------
    */
    'csrf' => [
        'enabled' => env('CSRF_PROTECTION_ENABLED', true),
        'token_expiry_minutes' => env('CSRF_TOKEN_EXPIRY', 30),
        'double_submission_protection' => env('CSRF_DOUBLE_SUBMISSION_PROTECTION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'key_length' => env('API_KEY_LENGTH', 64),
        'key_expiry_days' => env('API_KEY_EXPIRY_DAYS', 365),
        'max_requests_per_hour' => env('API_MAX_REQUESTS_PER_HOUR', 1000),
        'require_https' => env('API_REQUIRE_HTTPS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Security Configuration
    |--------------------------------------------------------------------------
    */
    'webhook' => [
        'signature_verification' => env('WEBHOOK_SIGNATURE_VERIFICATION', true),
        'timestamp_tolerance_seconds' => env('WEBHOOK_TIMESTAMP_TOLERANCE', 300),
        'allowed_ips' => env('WEBHOOK_ALLOWED_IPS', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation Configuration
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'url_max_length' => env('URL_MAX_LENGTH', 2048),
        'title_max_length' => env('TITLE_MAX_LENGTH', 255),
        'password_min_length' => env('PASSWORD_MIN_LENGTH', 8),
        'file_upload_max_size_kb' => env('FILE_UPLOAD_MAX_SIZE_KB', 2048),
        'allowed_file_types' => env('ALLOWED_FILE_TYPES', 'jpg,jpeg,png,gif'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security Configuration
    |--------------------------------------------------------------------------
    */
    'session' => [
        'lifetime_minutes' => env('SESSION_LIFETIME', 120),
        'secure_cookies' => env('SESSION_SECURE_COOKIES', true),
        'http_only_cookies' => env('SESSION_HTTP_ONLY_COOKIES', true),
        'same_site' => env('SESSION_SAME_SITE', 'lax'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Security Configuration
    |--------------------------------------------------------------------------
    */
    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 8),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_symbols' => env('PASSWORD_REQUIRE_SYMBOLS', false),
        'max_age_days' => env('PASSWORD_MAX_AGE_DAYS', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers Configuration
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'x_content_type_options' => env('SECURITY_HEADER_X_CONTENT_TYPE_OPTIONS', 'nosniff'),
        'x_frame_options' => env('SECURITY_HEADER_X_FRAME_OPTIONS', 'DENY'),
        'x_xss_protection' => env('SECURITY_HEADER_X_XSS_PROTECTION', '1; mode=block'),
        'referrer_policy' => env('SECURITY_HEADER_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
        'content_security_policy' => env('SECURITY_HEADER_CSP', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline';"),
        'strict_transport_security' => env('SECURITY_HEADER_HSTS', 'max-age=31536000; includeSubDomains'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Suspicious Activity Detection
    |--------------------------------------------------------------------------
    */
    'suspicious_activity' => [
        'enabled' => env('SUSPICIOUS_ACTIVITY_DETECTION', true),
        'max_failed_logins' => env('MAX_FAILED_LOGINS', 10),
        'account_lockout_minutes' => env('ACCOUNT_LOCKOUT_MINUTES', 30),
        'suspicious_ip_threshold' => env('SUSPICIOUS_IP_THRESHOLD', 100),
        'geographic_restrictions' => env('GEOGRAPHIC_RESTRICTIONS', false),
        'allowed_countries' => env('ALLOWED_COUNTRIES', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'security_events' => env('LOG_SECURITY_EVENTS', true),
        'failed_attempts' => env('LOG_FAILED_ATTEMPTS', true),
        'suspicious_activity' => env('LOG_SUSPICIOUS_ACTIVITY', true),
        'api_usage' => env('LOG_API_USAGE', true),
        'webhook_events' => env('LOG_WEBHOOK_EVENTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption Configuration
    |--------------------------------------------------------------------------
    */
    'encryption' => [
        'algorithm' => env('ENCRYPTION_ALGORITHM', 'AES-256-CBC'),
        'key_rotation_days' => env('ENCRYPTION_KEY_ROTATION_DAYS', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Security Configuration
    |--------------------------------------------------------------------------
    */
    'backup' => [
        'encrypt_backups' => env('ENCRYPT_BACKUPS', true),
        'backup_retention_days' => env('BACKUP_RETENTION_DAYS', 30),
        'secure_backup_storage' => env('SECURE_BACKUP_STORAGE', true),
    ],
];
