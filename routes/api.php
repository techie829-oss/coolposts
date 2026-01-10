<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API endpoints (no authentication required)
Route::get('/status', [ApiController::class, 'status']);

// Protected API endpoints (authentication required)
Route::middleware('auth:sanctum')->group(function () {

    // API Status and Info
    Route::get('/info', [ApiController::class, 'status']);

    // Links Management
    Route::prefix('links')->group(function () {
        Route::get('/', [ApiController::class, 'getLinks']);
        Route::post('/', [ApiController::class, 'createLink']);
        Route::get('/{link}', [ApiController::class, 'getLink']);
        Route::put('/{link}', [ApiController::class, 'updateLink']);
        Route::delete('/{link}', [ApiController::class, 'deleteLink']);
    });

    // Blog Posts Management
    Route::prefix('blog-posts')->group(function () {
        Route::get('/', [ApiController::class, 'getBlogPosts']);
        Route::get('/{post}', [ApiController::class, 'getBlogPost']);
    });

    // Analytics
    Route::prefix('analytics')->group(function () {
        Route::get('/', [ApiController::class, 'getAnalytics']);
        Route::get('/realtime', [ApiController::class, 'getRealTimeAnalytics']);
    });

    // Earnings
    Route::prefix('earnings')->group(function () {
        Route::get('/', [ApiController::class, 'getEarnings']);
    });

    // Admin only endpoints
    Route::middleware('admin')->group(function () {
        Route::get('/settings', [ApiController::class, 'getGlobalSettings']);
    });
});

// Webhook endpoints (for external integrations)
Route::prefix('webhooks')->group(function () {
    Route::post('/adsense', function (Request $request) {
        // Handle AdSense webhook
        Log::info('AdSense webhook received', $request->all());
        return response()->json(['status' => 'received']);
    });

    Route::post('/admob', function (Request $request) {
        // Handle AdMob webhook
        Log::info('AdMob webhook received', $request->all());
        return response()->json(['status' => 'received']);
    });

    Route::post('/payment', function (Request $request) {
        // Handle payment gateway webhook
        Log::info('Payment webhook received', $request->all());
        return response()->json(['status' => 'received']);
    });
});

// Rate limiting for API endpoints
Route::middleware('throttle:api')->group(function () {
    // Additional rate-limited endpoints can be added here
});
