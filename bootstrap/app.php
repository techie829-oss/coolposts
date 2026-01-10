<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'rate.limit' => \App\Http\Middleware\RateLimitingMiddleware::class,
            'webhook.security' => \App\Http\Middleware\WebhookSecurityMiddleware::class,
            'csrf.enhanced' => \App\Http\Middleware\EnhancedCsrfMiddleware::class,
            'api.key' => \App\Http\Middleware\ApiKeySecurityMiddleware::class,
            'request.logging' => \App\Http\Middleware\RequestLoggingMiddleware::class,
            'force.https.www' => \App\Http\Middleware\ForceHttpsAndWww::class,
        ]);

        // Apply request logging to all web routes
        $middleware->append(\App\Http\Middleware\RequestLoggingMiddleware::class);

        // Apply HTTPS and WWW enforcement to web routes
        $middleware->web(append: [
            \App\Http\Middleware\ForceHttpsAndWww::class,
        ]);

        // Trust all proxies (Heroku requirement)
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
