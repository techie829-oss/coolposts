# Security Documentation

## Overview

This document outlines the comprehensive security measures implemented in the CoolPosts link sharing application to protect against various cyber threats and ensure data integrity.

## Security Features Implemented

### 1. Rate Limiting

**Purpose**: Prevents brute force attacks, API abuse, and DoS attacks.

**Implementation**:
- **Authentication Routes**: 5 attempts per 15 minutes
- **Payment Routes**: 10 attempts per hour
- **Link Creation**: 50 attempts per hour
- **API Routes**: 100 attempts per hour
- **Admin Routes**: 200 attempts per hour
- **Webhooks**: 100 attempts per hour

**Files**:
- `app/Http/Middleware/RateLimitingMiddleware.php`
- `config/security.php`

### 2. Enhanced CSRF Protection

**Purpose**: Prevents Cross-Site Request Forgery attacks.

**Features**:
- Token validation for all POST/PUT/DELETE requests
- Double submission protection (replay attack prevention)
- Token expiration (30 minutes)
- Security headers injection

**Files**:
- `app/Http/Middleware/EnhancedCsrfMiddleware.php`

### 3. Input Validation & Sanitization

**Purpose**: Prevents XSS, SQL injection, and malicious input.

**Features**:
- Comprehensive validation rules for all user inputs
- URL safety validation (prevents javascript: and data: URLs)
- HTML tag stripping
- Special character encoding
- File upload validation

**Files**:
- `app/Services/ValidationService.php`

### 4. API Key Security

**Purpose**: Secure API authentication and access control.

**Features**:
- 64-character hex API keys
- Rate limiting per API key
- Permission-based access control
- API usage tracking
- Automatic key expiration

**Files**:
- `app/Http/Middleware/ApiKeySecurityMiddleware.php`
- `app/Models/User.php` (API key methods)

### 5. Webhook Security

**Purpose**: Secure payment gateway webhook processing.

**Features**:
- Signature verification for all major gateways
- Timestamp validation (5-minute tolerance)
- Rate limiting per IP
- Gateway detection and validation

**Supported Gateways**:
- Stripe (HMAC-SHA256)
- PayPal (Bearer token)
- Paytm (Checksum validation)
- Razorpay (HMAC-SHA256)

**Files**:
- `app/Http/Middleware/WebhookSecurityMiddleware.php`

### 6. User Authentication & Authorization

**Purpose**: Secure user access and role-based permissions.

**Features**:
- Laravel Breeze authentication
- Role-based access control (user, admin, moderator)
- Password strength requirements
- Account lockout after failed attempts
- Session security

### 7. Payment Security

**Purpose**: Secure payment processing and transaction handling.

**Features**:
- Payment gateway validation
- Transaction signature verification
- Secure payment data handling
- Webhook security for payment confirmations

## Security Headers

The application automatically injects the following security headers:

```http
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
```

## Configuration

Security settings can be configured in `config/security.php`:

```php
'rate_limiting' => [
    'auth' => [
        'max_attempts' => env('RATE_LIMIT_AUTH_ATTEMPTS', 5),
        'decay_minutes' => env('RATE_LIMIT_AUTH_DECAY', 15),
    ],
    // ... other rate limits
],

'csrf' => [
    'enabled' => env('CSRF_PROTECTION_ENABLED', true),
    'token_expiry_minutes' => env('CSRF_TOKEN_EXPIRY', 30),
],

'api' => [
    'key_length' => env('API_KEY_LENGTH', 64),
    'max_requests_per_hour' => env('API_MAX_REQUESTS_PER_HOUR', 1000),
],
```

## Environment Variables

Add these to your `.env` file for security configuration:

```env
# Rate Limiting
RATE_LIMIT_AUTH_ATTEMPTS=5
RATE_LIMIT_AUTH_DECAY=15
RATE_LIMIT_PAYMENT_ATTEMPTS=10
RATE_LIMIT_PAYMENT_DECAY=60
RATE_LIMIT_LINK_CREATION_ATTEMPTS=50
RATE_LIMIT_API_ATTEMPTS=100

# CSRF Protection
CSRF_PROTECTION_ENABLED=true
CSRF_TOKEN_EXPIRY=30
CSRF_DOUBLE_SUBMISSION_PROTECTION=true

# API Security
API_KEY_LENGTH=64
API_KEY_EXPIRY_DAYS=365
API_MAX_REQUESTS_PER_HOUR=1000
API_REQUIRE_HTTPS=true

# Webhook Security
WEBHOOK_SIGNATURE_VERIFICATION=true
WEBHOOK_TIMESTAMP_TOLERANCE=300

# Input Validation
URL_MAX_LENGTH=2048
TITLE_MAX_LENGTH=255
PASSWORD_MIN_LENGTH=8
FILE_UPLOAD_MAX_SIZE_KB=2048

# Session Security
SESSION_LIFETIME=120
SESSION_SECURE_COOKIES=true
SESSION_HTTP_ONLY_COOKIES=true
SESSION_SAME_SITE=lax

# Password Security
PASSWORD_MIN_LENGTH=8
PASSWORD_REQUIRE_UPPERCASE=true
PASSWORD_REQUIRE_LOWERCASE=true
PASSWORD_REQUIRE_NUMBERS=true
PASSWORD_REQUIRE_SYMBOLS=false
PASSWORD_MAX_AGE_DAYS=90
```

## Security Testing

Run the comprehensive security test suite:

```bash
php artisan test --filter=SecurityTest
```

The test suite covers:
- Rate limiting functionality
- CSRF protection
- Input validation
- API key authentication
- Webhook security
- User permissions
- SQL injection prevention
- XSS protection
- File upload security
- Session security
- Password requirements

## Security Best Practices

### For Developers

1. **Always validate and sanitize user input**
2. **Use the ValidationService for all form data**
3. **Never trust user-provided data**
4. **Use prepared statements for database queries**
5. **Implement proper error handling**
6. **Log security events**

### For Administrators

1. **Regularly review security logs**
2. **Monitor failed login attempts**
3. **Keep dependencies updated**
4. **Use HTTPS in production**
5. **Regular security audits**
6. **Backup data securely**

### For Users

1. **Use strong, unique passwords**
2. **Enable two-factor authentication (if available)**
3. **Never share API keys**
4. **Report suspicious activity**
5. **Keep software updated**

## Security Monitoring

The application logs various security events:

- Failed authentication attempts
- Rate limit violations
- CSRF token mismatches
- API key usage
- Webhook events
- Suspicious activity

Logs are stored in `storage/logs/laravel.log` and can be monitored for security threats.

## Incident Response

In case of a security incident:

1. **Immediate Actions**:
   - Review security logs
   - Identify affected users/systems
   - Implement temporary blocks if necessary

2. **Investigation**:
   - Analyze attack vectors
   - Determine scope of compromise
   - Document findings

3. **Remediation**:
   - Patch vulnerabilities
   - Reset compromised credentials
   - Update security measures

4. **Communication**:
   - Notify affected users
   - Update security documentation
   - Implement additional safeguards

## Compliance

This security implementation helps meet requirements for:

- **GDPR**: Data protection and privacy
- **PCI DSS**: Payment card security
- **SOC 2**: Security controls
- **ISO 27001**: Information security management

## Updates and Maintenance

Security features are regularly updated to address:

- New attack vectors
- Security vulnerabilities
- Compliance requirements
- Best practice improvements

## Support

For security-related questions or incidents:

1. Review this documentation
2. Check security logs
3. Run security tests
4. Contact the development team

---

**Last Updated**: August 25, 2025
**Version**: 1.0
**Security Level**: Enterprise Grade
