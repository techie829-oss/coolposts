<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Google reCAPTCHA v3 integration.
    | You need to get your site key and secret key from:
    | https://www.google.com/recaptcha/admin
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Settings
    |--------------------------------------------------------------------------
    |
    | score_threshold: Minimum score (0.0 to 1.0) required to pass verification
    | action: The action name for this reCAPTCHA instance
    |
    */

    'score_threshold' => env('RECAPTCHA_SCORE_THRESHOLD', 0.5),
    'action' => env('RECAPTCHA_ACTION', 'monetization'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA URLs
    |--------------------------------------------------------------------------
    |
    | These are the Google reCAPTCHA API endpoints
    |
    */

    'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
    'script_url' => 'https://www.google.com/recaptcha/api.js?render=',
];
