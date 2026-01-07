<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Link;
use App\Models\PaymentGateway;
use App\Services\ValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create([
            'role' => 'user',
        ]);

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function rate_limiting_prevents_brute_force_attacks()
    {
        // Test login rate limiting
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertEquals(429, $response->status());
        $this->assertStringContainsString('Rate limit exceeded', $response->getContent());
    }

    /** @test */
    public function csrf_protection_prevents_cross_site_request_forgery()
    {
        $this->actingAs($this->user);

        // Test without CSRF token
        $response = $this->post('/links', [
            'title' => 'Test Link',
            'original_url' => 'https://example.com',
            'ad_type' => 'no_ads',
        ]);

        $this->assertEquals(419, $response->status());
    }

    /** @test */
    public function input_validation_prevents_malicious_input()
    {
        $validationService = app(ValidationService::class);

        // Test malicious URL
        $maliciousData = [
            'title' => 'Test Link',
            'original_url' => 'javascript:alert("xss")',
            'ad_type' => 'no_ads',
        ];

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $validationService->validateLinkCreation($maliciousData);
    }

    /** @test */
    public function api_key_authentication_works_correctly()
    {
        // Generate API key for user
        $apiKey = $this->user->generateApiKey();

        // Test valid API key
        $response = $this->withHeaders([
            'X-API-Key' => $apiKey,
        ])->get('/api/links');

        $this->assertEquals(200, $response->status());

        // Test invalid API key
        $response = $this->withHeaders([
            'X-API-Key' => 'invalid_key',
        ])->get('/api/links');

        $this->assertEquals(401, $response->status());
    }

    /** @test */
    public function webhook_security_prevents_unauthorized_webhooks()
    {
        // Create test payment gateway
        $gateway = PaymentGateway::create([
            'name' => 'Test Gateway',
            'slug' => 'test_gateway',
            'is_active' => true,
            'description' => 'Test Gateway Description',
            'environment' => 'test',
            'config' => [
                'webhook_secret' => 'test_secret',
            ],
        ]);

        // Test webhook without signature
        $response = $this->post('/webhooks/test_gateway', [
            'event' => 'payment.success',
        ]);

        $this->assertEquals(401, $response->status());

        // Test webhook with invalid signature
        $response = $this->withHeaders([
            'X-Webhook-Signature' => 'invalid_signature',
        ])->post('/webhooks/test_gateway', [
                    'event' => 'payment.success',
                ]);

        $this->assertEquals(401, $response->status());
    }

    /** @test */
    public function user_permissions_are_enforced()
    {
        $this->actingAs($this->user);

        // Test user cannot access admin routes
        $response = $this->get('/admin');
        $this->assertEquals(403, $response->status());

        // Test user cannot modify other user's links
        $otherUser = User::factory()->create();
        $otherUserLink = Link::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->put("/links/{$otherUserLink->id}", [
            'title' => 'Modified Title',
            'original_url' => 'https://example.com',
            'ad_type' => 'no_ads',
        ]);

        $this->assertEquals(403, $response->status());
    }

    /** @test */
    public function sql_injection_is_prevented()
    {
        $this->actingAs($this->user);

        // Test SQL injection in search
        $response = $this->get('/links?search=1%27%20OR%201%3D1%20--');

        // Should not throw SQL error
        $this->assertNotEquals(500, $response->status());
    }

    /** @test */
    public function xss_protection_works()
    {
        $this->actingAs($this->user);

        // Test XSS in link title
        $response = $this->post('/links', [
            'title' => '<script>alert("xss")</script>',
            'original_url' => 'https://example.com',
            'ad_type' => 'no_ads',
        ]);

        // Should sanitize the input
        $link = Link::latest()->first();
        $this->assertStringNotContainsString('<script>', $link->title);
    }

    /** @test */
    public function file_upload_security_works()
    {
        $validationService = app(ValidationService::class);

        // Create dummy file
        $filePath = storage_path('app/test.php');
        file_put_contents($filePath, '<?php echo "test"; ?>');

        // Test malicious file upload
        $maliciousFile = new \Illuminate\Http\UploadedFile(
            $filePath,
            'test.php',
            'text/php',
            null,
            true
        );

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $validationService->validateFileUpload($maliciousFile);
    }

    /** @test */
    public function session_security_is_maintained()
    {
        $this->actingAs($this->user);

        // Test session timeout
        $this->travel(2)->hours(); // Simulate 2 hours passing

        $response = $this->get('/dashboard');
        $this->assertEquals(302, $response->status()); // Should redirect to login
    }

    /** @test */
    public function password_security_requirements_are_enforced()
    {
        // Test weak password
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirmation' => '123',
            'preferred_currency' => 'INR',
        ]);

        $this->assertEquals(422, $response->status());
        $this->assertStringContainsString('password', $response->getContent());
    }

    /** @test */
    public function referral_code_security_works()
    {
        // Test invalid referral code
        $response = $this->post('/referrals/process', [
            'referral_code' => 'INVALID123',
        ]);

        $this->assertEquals(422, $response->status());
    }

    /** @test */
    public function payment_security_is_enforced()
    {
        $this->actingAs($this->user);

        // Test payment with invalid data
        $response = $this->post('/subscriptions/payment', [
            'plan_id' => 999999, // Non-existent plan
            'gateway' => 'invalid_gateway',
            'currency' => 'INVALID',
        ]);

        $this->assertEquals(422, $response->status());
    }

    /** @test */
    public function admin_security_is_maintained()
    {
        // Test non-admin accessing admin routes
        $this->actingAs($this->user);

        $response = $this->get('/admin/users');
        $this->assertEquals(403, $response->status());

        // Test admin can access admin routes
        $this->actingAs($this->admin);

        $response = $this->get('/admin/users');
        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function cache_security_prevents_information_disclosure()
    {
        $this->actingAs($this->user);

        // Test that sensitive data is not cached inappropriately
        $response = $this->get('/profile');

        // Check that sensitive headers are not exposed
        $this->assertNull($response->headers->get('X-Powered-By'));
        $this->assertNotNull($response->headers->get('X-Content-Type-Options'));
    }
}
