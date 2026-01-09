<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\MonetizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Test route for debugging
Route::get('/test-redirect', function () {
    return 'Test route working!';
})->name('test-redirect');

// Simple redirect test
Route::get('/test-link/{shortCode}', function ($shortCode) {
    $link = \App\Models\Link::where('short_code', $shortCode)->first();
    if (!$link) {
        return 'Link not found';
    }
    return "Link found: {$link->title} -> {$link->original_url}";
})->name('test-link');

// Laravel Breeze authentication routes (MUST come before catch-all routes)
require __DIR__ . '/auth.php';

// Monetization AJAX endpoints
Route::post('/monetization/validate-session', [MonetizationController::class, 'validateSession'])->name('monetization.validate-session');
Route::post('/monetization/countdown-status', [MonetizationController::class, 'getCountdownStatus'])->name('monetization.countdown-status');
Route::post('/monetization/verify-recaptcha', [MonetizationController::class, 'verifyRecaptcha'])->name('monetization.verify-recaptcha');

// Newsletter routes
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $currencyService = app(\App\Services\CurrencyService::class);
        $userCurrency = $user->preferred_currency ?? 'INR';

        $stats = [
            'total_links' => $user->links()->count(),
            'total_clicks' => $user->total_clicks,
            'total_earnings' => $user->getTotalEarningsInCurrency($userCurrency),
            'pending_earnings' => $user->getPendingEarningsInCurrency($userCurrency),
            'today_earnings' => $user->getTodayEarningsInCurrency($userCurrency),
            'this_month_earnings' => $user->getThisMonthEarningsInCurrency($userCurrency),
            'currency' => $userCurrency,
            'currency_symbol' => $currencyService->getSymbol($userCurrency),
            'total_blog_posts' => $user->blogPosts()->count(),
            'total_blog_views' => $user->blogPosts()->sum('views'),
        ];
        return view('dashboard', compact('stats'));
    })->name('dashboard');

    // Links management
    Route::resource('links', LinkController::class)->middleware('rate.limit');

    // Earnings
    Route::get('/earnings', [\App\Http\Controllers\EarningsController::class, 'index'])->name('earnings.index');

    // Withdrawals
    Route::resource('withdrawals', WithdrawalController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/withdrawals/{withdrawal}/cancel', [WithdrawalController::class, 'cancel'])->name('withdrawals.cancel');

    // Subscription and Payment routes
    Route::get('/subscriptions', [PaymentController::class, 'showPlans'])->name('subscriptions.plans');
    Route::post('/subscriptions/payment', [PaymentController::class, 'initiatePayment'])->name('subscriptions.payment')->middleware('rate.limit');
    Route::get('/subscriptions/payment/verify', [PaymentController::class, 'verifyPayment'])->name('subscriptions.verify-payment');
    Route::get('/subscriptions/payment/gateways', [PaymentController::class, 'getAvailableGateways'])->name('subscriptions.gateways');
    Route::post('/subscriptions/payment/test-gateway', [PaymentController::class, 'testGateway'])->name('subscriptions.test-gateway');

    // User subscription management
    Route::get('/subscriptions/dashboard', [PaymentController::class, 'dashboard'])->name('subscriptions.dashboard');
    Route::post('/subscriptions/cancel', [PaymentController::class, 'cancel'])->name('subscriptions.cancel');

    // Subscription management routes
    Route::post('/subscriptions/upgrade', [PaymentController::class, 'upgradeSubscription'])->name('subscriptions.upgrade');
    Route::post('/subscriptions/downgrade', [PaymentController::class, 'downgradeSubscription'])->name('subscriptions.downgrade');
    Route::get('/subscriptions/options', [PaymentController::class, 'getSubscriptionOptions'])->name('subscriptions.options');
    Route::get('/subscriptions/transactions/{id}', [PaymentController::class, 'showTransaction'])->name('subscriptions.transaction');
    Route::post('/subscriptions/transactions/{id}/retry', [PaymentController::class, 'retryPayment'])->name('subscriptions.retry-payment');
    Route::get('/subscriptions/transactions/{id}/receipt', [PaymentController::class, 'downloadReceipt'])->name('subscriptions.receipt');

    // Referral routes
    Route::get('/referrals', [ReferralController::class, 'dashboard'])->name('referrals.dashboard');
    Route::post('/referrals/process', [ReferralController::class, 'processReferral'])->name('referrals.process');
    Route::get('/referrals/link', [ReferralController::class, 'getReferralLink'])->name('referrals.link');
    Route::post('/referrals/share', [ReferralController::class, 'shareReferral'])->name('referrals.share');

    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    Route::get('/analytics/real-time', [AnalyticsController::class, 'getRealTimeData'])->name('analytics.real-time');
    Route::post('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');

    // Additional link routes
    Route::post('/links/{link}/toggle-status', [LinkController::class, 'toggleStatus'])->name('links.toggle-status');
    Route::get('/links/{link}/analytics', [LinkController::class, 'analytics'])->name('links.analytics');

    // Monetization stats
    Route::get('/links/{link}/monetization-stats', [MonetizationController::class, 'getStats'])->name('links.monetization-stats');

    // Profile management (Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.update-role');
        Route::get('/admin/links', [AdminController::class, 'links'])->name('admin.links.index');
        Route::get('/admin/links/{link}', [AdminController::class, 'showLink'])->name('admin.links.show');
        Route::patch('/admin/links/{link}/status', [AdminController::class, 'toggleLinkStatus'])->name('admin.links.toggle-status');
        Route::get('/admin/earnings', [AdminController::class, 'earnings'])->name('admin.earnings.index');
        Route::patch('/admin/earnings/{earning}/status', [AdminController::class, 'updateEarningsStatus'])->name('admin.earnings.update-status');
        Route::get('/admin/payouts', [AdminController::class, 'payouts'])->name('admin.payouts.index');
        Route::post('/admin/payouts/{user}', [AdminController::class, 'processPayout'])->name('admin.payouts.process');
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::patch('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
        // Global Settings
        Route::get('/global-settings', [AdminController::class, 'globalSettings'])->name('global-settings');
        Route::post('/global-settings', [AdminController::class, 'updateGlobalSettings'])->name('global-settings.update');

        // Branding Settings
        Route::get('/branding-settings', [AdminController::class, 'brandingSettings'])->name('branding-settings');
        Route::post('/branding-settings', [AdminController::class, 'updateBrandingSettings'])->name('branding-settings.update');

        // AI Settings
        Route::get('/ai-settings', [AdminController::class, 'aiSettings'])->name('ai-settings');
        Route::post('/ai-settings', [AdminController::class, 'updateAiSettings'])->name('ai-settings.update');

        // User Management
        Route::get('/admin/user-management', [AdminController::class, 'userManagement'])->name('admin.user-management');
        Route::post('/admin/user-management/{user}/ai-settings', [AdminController::class, 'updateUserAiSettings'])->name('admin.user.ai-settings');

        // Referral Settings
        Route::get('/admin/referral-settings', [AdminController::class, 'referralSettings'])->name('admin.referral-settings');
        Route::post('/admin/referral-settings', [AdminController::class, 'updateReferralSettings'])->name('admin.referral-settings.update');

        // Additional admin routes
        Route::get('/admin/system-analytics', [AdminController::class, 'systemAnalytics'])->name('admin.system-analytics');
        Route::get('/admin/test-payment-gateways', [AdminController::class, 'testAllPaymentGateways'])->name('admin.test-payment-gateways');
        Route::get('/admin/payment-system-health', [AdminController::class, 'getPaymentSystemHealth'])->name('admin.payment-system-health');
        Route::get('/admin/system-health', [AdminController::class, 'systemHealthCheck'])->name('admin.system-health');
        Route::get('/admin/performance-metrics', [AdminController::class, 'getPerformanceMetrics'])->name('admin.performance-metrics');

        // Payment Gateway Management
        Route::get('/admin/payment-gateways', [AdminController::class, 'paymentGateways'])->name('admin.payment-gateways.index');
        Route::get('/admin/payment-gateways/{gateway}/edit', [AdminController::class, 'editPaymentGateway'])->name('admin.payment-gateways.edit');
        Route::put('/admin/payment-gateways/{gateway}', [AdminController::class, 'updatePaymentGateway'])->name('admin.payment-gateways.update');
        Route::patch('/admin/payment-gateways/{gateway}/toggle', [AdminController::class, 'togglePaymentGateway'])->name('admin.payment-gateways.toggle');

        // Payment Transactions
        Route::get('/admin/payment-transactions', [AdminController::class, 'paymentTransactions'])->name('admin.payment-transactions.index');

        // Withdrawal Management
        Route::get('/admin/withdrawals', [AdminController::class, 'withdrawals'])->name('admin.withdrawals.index');
        Route::get('/admin/withdrawals/{withdrawal}', [AdminController::class, 'showWithdrawal'])->name('admin.withdrawals.show');
        Route::post('/admin/withdrawals/{withdrawal}/process', [AdminController::class, 'processWithdrawal'])->name('admin.withdrawals.process');
        Route::post('/admin/withdrawals/{withdrawal}/complete', [AdminController::class, 'completeWithdrawal'])->name('admin.withdrawals.complete');
        Route::post('/admin/withdrawals/{withdrawal}/cancel', [AdminController::class, 'cancelWithdrawal'])->name('admin.withdrawals.cancel');
        Route::post('/admin/withdrawals/{withdrawal}/fail', [AdminController::class, 'failWithdrawal'])->name('admin.withdrawals.fail');

        // Ad Network Management
        Route::get('/admin/ad-networks', [\App\Http\Controllers\Admin\AdNetworkController::class, 'index'])->name('admin.ad-networks.index');
        Route::post('/admin/ad-networks/account-settings', [\App\Http\Controllers\Admin\AdNetworkController::class, 'updateAccountSettings'])->name('admin.ad-networks.account-settings');
        Route::patch('/admin/ad-networks/settings', [\App\Http\Controllers\Admin\AdNetworkController::class, 'updateSettings'])->name('admin.ad-networks.update-settings');
        Route::post('/admin/ad-networks/test-connections', [\App\Http\Controllers\Admin\AdNetworkController::class, 'testConnections'])->name('admin.ad-networks.test-connections');
        Route::get('/admin/ad-networks/real-time-earnings', [\App\Http\Controllers\Admin\AdNetworkController::class, 'getRealTimeEarnings'])->name('admin.ad-networks.real-time-earnings');
        Route::post('/admin/ad-networks/earnings-report', [\App\Http\Controllers\Admin\AdNetworkController::class, 'getEarningsReport'])->name('admin.ad-networks.earnings-report');
        Route::post('/admin/ad-networks/generate-test-ad', [\App\Http\Controllers\Admin\AdNetworkController::class, 'generateTestAdCode'])->name('admin.ad-networks.generate-test-ad');
        Route::get('/admin/ad-networks/statistics', [\App\Http\Controllers\Admin\AdNetworkController::class, 'getStatistics'])->name('admin.ad-networks.statistics');
        Route::get('/admin/ad-networks/recommended-formats', [\App\Http\Controllers\Admin\AdNetworkController::class, 'getRecommendedFormats'])->name('admin.ad-networks.recommended-formats');
        Route::post('/admin/ad-networks/clear-cache', [\App\Http\Controllers\Admin\AdNetworkController::class, 'clearCache'])->name('admin.ad-networks.clear-cache');
        Route::get('/admin/ad-networks/configuration-guide', [\App\Http\Controllers\Admin\AdNetworkController::class, 'getConfigurationGuide'])->name('admin.ad-networks.configuration-guide');
    });

    // Performance Management
    Route::get('/admin/performance', [\App\Http\Controllers\Admin\PerformanceController::class, 'index'])->name('admin.performance');
    Route::post('/admin/performance/optimize-database', [\App\Http\Controllers\Admin\PerformanceController::class, 'optimizeDatabase'])->name('admin.performance.optimize-database');
    Route::post('/admin/performance/optimize-application', [\App\Http\Controllers\Admin\PerformanceController::class, 'optimizeApplication'])->name('admin.performance.optimize-application');
    Route::get('/admin/performance/cache-stats', [\App\Http\Controllers\Admin\PerformanceController::class, 'getCacheStats'])->name('admin.performance.cache-stats');
    Route::post('/admin/performance/clear-cache', [\App\Http\Controllers\Admin\PerformanceController::class, 'clearCache'])->name('admin.performance.clear-cache');
    Route::post('/admin/performance/optimize-cache', [\App\Http\Controllers\Admin\PerformanceController::class, 'optimizeCache'])->name('admin.performance.optimize-cache');
    Route::get('/admin/performance/metrics', [\App\Http\Controllers\Admin\PerformanceController::class, 'getMetrics'])->name('admin.performance.metrics');
    Route::get('/admin/performance/recommendations', [\App\Http\Controllers\Admin\PerformanceController::class, 'getRecommendations'])->name('admin.performance.recommendations');
    Route::post('/admin/performance/full-optimization', [\App\Http\Controllers\Admin\PerformanceController::class, 'runFullOptimization'])->name('admin.performance.full-optimization');
    Route::get('/admin/performance/system-health', [\App\Http\Controllers\Admin\PerformanceController::class, 'getSystemHealth'])->name('admin.performance.system-health');
    Route::get('/admin/performance/report', [\App\Http\Controllers\Admin\PerformanceController::class, 'getPerformanceReport'])->name('admin.performance.report');
    Route::get('/admin/performance/export', [\App\Http\Controllers\Admin\PerformanceController::class, 'exportPerformanceData'])->name('admin.performance.export');

    // Fraud Detection Routes
    Route::get('/admin/fraud-detection', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'index'])->name('admin.fraud-detection');
    Route::get('/admin/fraud-detection/settings', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'settings'])->name('admin.fraud-detection.settings');
    Route::post('/admin/fraud-detection/settings', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'updateSettings'])->name('admin.fraud-detection.update-settings');
    Route::get('/admin/fraud-detection/logs', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'logs'])->name('admin.fraud-detection.logs');
    Route::get('/admin/fraud-detection/ip-reputation', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'ipReputation'])->name('admin.fraud-detection.ip-reputation');
    Route::post('/admin/fraud-detection/block-ip', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'blockIP'])->name('admin.fraud-detection.block-ip');
    Route::post('/admin/fraud-detection/whitelist-ip', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'whitelistIP'])->name('admin.fraud-detection.whitelist-ip');
    Route::post('/admin/fraud-detection/unblock-ip', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'unblockIP'])->name('admin.fraud-detection.unblock-ip');
    Route::post('/admin/fraud-detection/remove-whitelist-ip', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'removeWhitelistIP'])->name('admin.fraud-detection.remove-whitelist-ip');
    Route::get('/admin/fraud-detection/export', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'export'])->name('admin.fraud-detection.export');

    // PWA Routes
    Route::get('/manifest.json', [\App\Http\Controllers\PWAController::class, 'manifest'])->name('pwa.manifest');
    Route::get('/sw.js', [\App\Http\Controllers\PWAController::class, 'serviceWorker'])->name('pwa.service-worker');
    Route::post('/pwa/install', [\App\Http\Controllers\PWAController::class, 'trackInstallation'])->name('pwa.install');
    Route::get('/pwa/stats', [\App\Http\Controllers\PWAController::class, 'getInstallationStats'])->name('pwa.stats');
    Route::get('/pwa/offline-data', [\App\Http\Controllers\PWAController::class, 'getOfflineData'])->name('pwa.offline-data');
    Route::post('/pwa/cache', [\App\Http\Controllers\PWAController::class, 'cacheForOffline'])->name('pwa.cache');
    Route::post('/pwa/share-target', [\App\Http\Controllers\PWAController::class, 'shareTarget'])->name('pwa.share-target');
    Route::get('/pwa/metrics', [\App\Http\Controllers\PWAController::class, 'getPerformanceMetrics'])->name('pwa.metrics');
    Route::get('/pwa/check-installation', [\App\Http\Controllers\PWAController::class, 'checkInstallation'])->name('pwa.check-installation');
    Route::get('/pwa/config', [\App\Http\Controllers\PWAController::class, 'getConfig'])->name('pwa.config');
    Route::post('/pwa/config', [\App\Http\Controllers\PWAController::class, 'updateConfig'])->name('pwa.update-config');
    Route::post('/pwa/generate-icons', [\App\Http\Controllers\PWAController::class, 'generateIcons'])->name('pwa.generate-icons');
    Route::get('/pwa/status', [\App\Http\Controllers\PWAController::class, 'getStatus'])->name('pwa.status');
    Route::post('/pwa/sync', [\App\Http\Controllers\PWAController::class, 'syncOfflineData'])->name('pwa.sync');
    Route::get('/pwa/install-guide', [\App\Http\Controllers\PWAController::class, 'getInstallationGuide'])->name('pwa.install-guide');
    Route::get('/pwa/check-updates', [\App\Http\Controllers\PWAController::class, 'checkForUpdates'])->name('pwa.check-updates');

    // Real-Time Analytics Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/realtime/dashboard', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getUserDashboard'])->name('realtime.dashboard');
        Route::get('/realtime/global-stats', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getGlobalStatistics'])->name('realtime.global-stats');
        Route::get('/realtime/link-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getLinkAnalytics'])->name('realtime.link-analytics');
        Route::get('/realtime/blog-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getBlogAnalytics'])->name('realtime.blog-analytics');
        Route::get('/realtime/live-visitors', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getLiveVisitorCount'])->name('realtime.live-visitors');
        Route::get('/realtime/earnings-summary', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getEarningsSummary'])->name('realtime.earnings-summary');
        Route::get('/realtime/notifications', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getUserNotifications'])->name('realtime.notifications');
        Route::get('/realtime/service-status', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getServiceStatus'])->name('realtime.service-status');
        Route::get('/realtime/analytics-summary', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getAnalyticsSummary'])->name('realtime.analytics-summary');
        Route::get('/realtime/mobile-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getMobileAnalytics'])->name('realtime.mobile-analytics');
        Route::get('/realtime/widget-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getWidgetAnalytics'])->name('realtime.widget-analytics');
        Route::get('/realtime/api-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getApiAnalytics'])->name('realtime.api-analytics');
    });

    // Public real-time analytics
    Route::get('/realtime/public-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getPublicAnalytics'])->name('realtime.public-analytics');

    // Admin real-time analytics
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/admin/realtime/analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getAdminAnalytics'])->name('admin.realtime.analytics');
        Route::get('/admin/realtime/user-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getUserAnalytics'])->name('admin.realtime.user-analytics');
        Route::get('/admin/realtime/link-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getLinkAnalyticsAdmin'])->name('admin.realtime.link-analytics');
        Route::get('/admin/realtime/blog-analytics', [\App\Http\Controllers\RealTimeAnalyticsController::class, 'getBlogAnalyticsAdmin'])->name('admin.realtime.blog-analytics');
    });

    // Real-Time Analytics Dashboard
    Route::get('/analytics/realtime', function () {
        return view('analytics.realtime');
    })->name('analytics.realtime')->middleware('auth');

    // Test real-time analytics (for development)
    Route::get('/test/realtime', function () {
        return response()->json([
            'message' => 'Real-time analytics system is working!',
            'timestamp' => now()->toISOString(),
            'status' => 'active'
        ]);
    })->name('test.realtime');

    // API Documentation Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/api/docs', function () {
            return view('api.documentation');
        })->name('api.docs');

        Route::get('/api/docs/json', function () {
            return response()->json([
                'openapi' => '3.0.0',
                'info' => [
                    'title' => 'Link Sharing App API',
                    'version' => '1.0.0',
                    'description' => 'Comprehensive REST API for external integrations'
                ],
                'servers' => [
                    ['url' => url('/api')]
                ],
                'paths' => [
                    '/status' => [
                        'get' => [
                            'summary' => 'Get API status',
                            'responses' => [
                                '200' => [
                                    'description' => 'Success',
                                    'content' => [
                                        'application/json' => [
                                            'schema' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'status' => ['type' => 'string'],
                                                    'message' => ['type' => 'string'],
                                                    'version' => ['type' => 'string']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        })->name('api.docs.json');

        Route::get('/api/docs/postman', function () {
            return response()->json([
                'info' => [
                    'name' => 'Link Sharing App API',
                    'description' => 'Postman collection for Link Sharing App API',
                    'version' => '1.0.0'
                ],
                'item' => [
                    [
                        'name' => 'Get API Status',
                        'request' => [
                            'method' => 'GET',
                            'url' => [
                                'raw' => url('/api/status'),
                                'protocol' => 'https',
                                'host' => [parse_url(url('/api/status'), PHP_URL_HOST)],
                                'path' => ['api', 'status']
                            ]
                        ]
                    ]
                ]
            ]);
        })->name('api.docs.postman');
    });

});

// Payment webhook and callback routes (public)
Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook'])->name('webhooks.stripe');
Route::post('/webhooks/paypal', [PaymentController::class, 'paypalWebhook'])->name('webhooks.paypal');
Route::post('/webhooks/paytm', [PaymentController::class, 'paytmCallback'])->name('webhooks.paytm');
Route::post('/webhooks/razorpay', [PaymentController::class, 'razorpayCallback'])->name('webhooks.razorpay');

// Payment success/cancel routes
Route::get('/payments/stripe/success', [PaymentController::class, 'stripeSuccess'])->name('payments.stripe.success');
Route::get('/payments/paypal/success', [PaymentController::class, 'paypalSuccess'])->name('payments.paypal.success');
Route::get('/payments/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('payments.paypal.cancel');

// Additional payment routes
Route::post('/payments/failure', [PaymentController::class, 'handlePaymentFailure'])->name('payments.failure');
Route::get('/payments/status', [PaymentController::class, 'getPaymentStatus'])->name('payments.status');

// Legal and Content Pages (Public)
Route::get('/terms-of-service', [LegalController::class, 'termsOfService'])->name('legal.terms');
Route::get('/privacy-policy', [LegalController::class, 'privacyPolicy'])->name('legal.privacy');
Route::get('/about', [LegalController::class, 'about'])->name('legal.about');
Route::redirect('/about-us', '/about', 301);
Route::get('/faq', [LegalController::class, 'faq'])->name('legal.faq');
Route::get('/help', [LegalController::class, 'help'])->name('legal.help');
Route::get('/contact', [LegalController::class, 'contact'])->name('legal.contact');
Route::get('/cookie-policy', [LegalController::class, 'cookiePolicy'])->name('legal.cookies');
Route::get('/refund-policy', [LegalController::class, 'refundPolicy'])->name('legal.refund');
Route::get('/acceptable-use', [LegalController::class, 'acceptableUse'])->name('legal.acceptable-use');
Route::get('/dmca', [LegalController::class, 'dmca'])->name('legal.dmca');
Route::get('/gdpr', [LegalController::class, 'gdpr'])->name('legal.gdpr');

// SEO and Search Engine Files
Route::get('/sitemap.xml', [LegalController::class, 'sitemap'])->name('legal.sitemap');
Route::get('/robots.txt', [LegalController::class, 'robots'])->name('legal.robots');

// Blog routes (Public)
// Redirect /blog to /blog-posts for SEO consolidation
Route::redirect('/blog', '/blog-posts', 301);

Route::get('/blog-posts', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog-posts/templates', [BlogController::class, 'templates'])->name('blog.templates');
Route::get('/how-we-work', [BlogController::class, 'howWeWork'])->name('blog.how-we-work');
Route::post('/blog-posts/{post}/track-leave', [BlogController::class, 'trackLeave'])->name('blog.track-leave');
Route::post('/blog-posts/{post}/track-visitor', [BlogController::class, 'trackVisitorAjax'])->name('blog.track-visitor');

// AI routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::post('/ai/generate-blog', [App\Http\Controllers\AiController::class, 'generateBlog'])->name('ai.generate-blog');
    Route::post('/ai/generate-image', [App\Http\Controllers\AiController::class, 'generateImage'])->name('ai.generate-image');
    Route::post('/ai/analyze-image', [App\Http\Controllers\AiController::class, 'analyzeImage'])->name('ai.analyze-image');
});

// Blog routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/blog-posts/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog-posts', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog-posts/analytics', [BlogController::class, 'analytics'])->name('blog.analytics');
    Route::get('/blog-posts/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog-posts/{post}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog-posts/{post}', [BlogController::class, 'destroy'])->name('blog.destroy');
    Route::get('/blog-posts/{post}/analytics', [BlogController::class, 'postAnalytics'])->name('blog.post-analytics');
});

// Blog show route (Public) - Must come after specific routes
Route::get('/blog-posts/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Link redirection routes (public) - MUST come AFTER all specific routes
Route::get('/{shortCode}', [MonetizationController::class, 'showIntermediate'])->name('link.redirect');
Route::post('/{shortCode}/verify', [LinkController::class, 'verifyPassword'])->name('link.verify');
Route::post('/{shortCode}/complete', [MonetizationController::class, 'completeMonetization'])->name('link.complete');
