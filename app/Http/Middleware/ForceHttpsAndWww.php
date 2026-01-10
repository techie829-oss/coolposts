<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsAndWww
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production')) {
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri(), 301);
            }

            if (!str_starts_with($request->getHost(), 'www.')) {
                return redirect()->to(
                    'https://www.' . $request->getHost() . $request->getRequestUri(),
                    301
                );
            }
        }

        return $next($request);
    }
}
