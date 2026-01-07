<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fraud Detection Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for the advanced fraud
    | detection system, including risk thresholds, malicious IP lists,
    | and detection parameters.
    |
    */

    // Risk thresholds for different actions
    'risk_thresholds' => [
        'low' => 0.3,
        'medium' => 0.6,
        'high' => 0.8,
        'critical' => 0.95,
    ],

    // Actions to take at different risk levels
    'actions' => [
        'low' => 'monitor',
        'medium' => 'challenge',
        'high' => 'block',
        'critical' => 'block_and_alert',
    ],

    // Known malicious IP addresses
    'malicious_ips' => [
        // Add known malicious IPs here
        // '192.168.1.100',
        // '10.0.0.50',
    ],

    // Bot user agent patterns
    'bot_patterns' => [
        'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget',
        'python', 'java', 'perl', 'ruby', 'php', 'go-http-client',
        'okhttp', 'apache-httpclient', 'requests', 'urllib',
        'scrapy', 'selenium', 'phantomjs', 'headless'
    ],

    // Malicious request patterns
    'malicious_patterns' => [
        'sql_injection' => [
            'union', 'select', 'drop', 'insert', 'update', 'delete',
            'create', 'alter', 'exec', 'execute', 'declare', 'cast'
        ],
        'xss' => [
            '<script', 'javascript:', 'onload', 'onerror', 'onclick',
            'onmouseover', 'onfocus', 'onblur', 'eval(', 'alert('
        ],
        'path_traversal' => [
            '../', '..\\', '%2e%2e', '..%2f', '..%5c', '....//'
        ],
        'command_injection' => [
            ';', '|', '&', '`', '$(', '&&', '||', '>', '<', '>>'
        ],
        'ldap_injection' => [
            '*', '(', ')', '&', '|', '!', '=', '<', '>'
        ]
    ],

    // Rate limiting settings
    'rate_limiting' => [
        'requests_per_minute' => 60,
        'requests_per_hour' => 1000,
        'requests_per_day' => 10000,
        'burst_limit' => 10,
    ],

    // Behavioral analysis settings
    'behavioral_analysis' => [
        'session_timeout' => 3600, // 1 hour
        'max_ips_per_session' => 3,
        'max_user_agents_per_session' => 2,
        'min_click_interval' => 2, // seconds
        'max_click_interval' => 300, // 5 minutes
        'suspicious_variance_threshold' => 2,
    ],

    // Machine learning settings
    'machine_learning' => [
        'enabled' => true,
        'model_update_frequency' => 86400, // 24 hours
        'confidence_threshold' => 0.7,
        'feature_weights' => [
            'hour_of_day' => 0.1,
            'user_agent_length' => 0.05,
            'request_frequency' => 0.3,
            'ip_reputation' => 0.4,
            'geographic_risk' => 0.15
        ]
    ],

    // Geographic analysis settings
    'geographic_analysis' => [
        'enabled' => true,
        'high_risk_countries' => [
            // Add high-risk country codes here
            // 'XX', 'YY', 'ZZ'
        ],
        'vpn_proxy_detection' => true,
        'anomaly_threshold' => 0.8,
    ],

    // Temporal analysis settings
    'temporal_analysis' => [
        'enabled' => true,
        'unusual_hours' => [
            'start' => 23, // 11 PM
            'end' => 6,    // 6 AM
        ],
        'burst_detection' => [
            'time_window' => 60, // seconds
            'threshold' => 20,   // requests
        ],
    ],

    // Logging settings
    'logging' => [
        'enabled' => true,
        'log_level' => 'warning', // debug, info, warning, error
        'log_high_risk_only' => true,
        'retention_days' => 30,
    ],

    // Notification settings
    'notifications' => [
        'enabled' => true,
        'email_alerts' => [
            'enabled' => true,
            'recipients' => [
                // 'admin@example.com',
            ],
            'threshold' => 'high', // low, medium, high, critical
        ],
        'webhook_alerts' => [
            'enabled' => false,
            'url' => '',
            'threshold' => 'critical',
        ],
    ],

    // CAPTCHA settings
    'captcha' => [
        'enabled' => true,
        'provider' => 'recaptcha', // recaptcha, hcaptcha, custom
        'threshold' => 'medium',
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

    // IP reputation settings
    'ip_reputation' => [
        'enabled' => true,
        'providers' => [
            'abuseipdb' => [
                'enabled' => false,
                'api_key' => env('ABUSEIPDB_API_KEY'),
                'threshold' => 25, // confidence score
            ],
            'virustotal' => [
                'enabled' => false,
                'api_key' => env('VIRUSTOTAL_API_KEY'),
            ],
        ],
        'cache_duration' => 3600, // 1 hour
    ],

    // Device fingerprinting
    'device_fingerprinting' => [
        'enabled' => true,
        'components' => [
            'user_agent',
            'accept_language',
            'accept_encoding',
            'screen_resolution',
            'timezone',
            'plugins',
            'canvas_fingerprint',
        ],
        'hash_algorithm' => 'sha256',
    ],

    // Whitelist settings
    'whitelist' => [
        'ips' => [
            // Add trusted IPs here
            // '127.0.0.1',
            // '::1',
        ],
        'user_agents' => [
            // Add trusted user agents here
            // 'Googlebot',
            // 'Bingbot',
        ],
        'domains' => [
            // Add trusted domains here
            // 'google.com',
            // 'bing.com',
        ],
    ],

    // Blacklist settings
    'blacklist' => [
        'ips' => [
            // Add blocked IPs here
        ],
        'user_agents' => [
            // Add blocked user agents here
        ],
        'domains' => [
            // Add blocked domains here
        ],
    ],

    // Advanced settings
    'advanced' => [
        'learning_mode' => false, // Enable to collect data without blocking
        'auto_update_patterns' => true,
        'pattern_update_frequency' => 86400, // 24 hours
        'max_patterns_per_category' => 100,
        'pattern_similarity_threshold' => 0.8,
    ],
];
