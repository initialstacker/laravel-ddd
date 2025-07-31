<?php declare(strict_types=1);

namespace Initialstack\Middleware;

use Illuminate\Http\Request;

final class SecurityHeaders
{
    /**
     * Handle an incoming request and add security headers to the response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * 
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $response = $next($request);

        $response->headers->set(key: 'X-XSS-Protection', values: '1; mode=block');
        $response->headers->set(key: 'X-Frame-Options', values: 'DENY');
        $response->headers->set(key: 'X-Content-Type-Options', values: 'nosniff');

        $response->headers->set(
            key: 'Content-Security-Policy',
            values: "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'"
        );

        $inProduction = in_array('production', (array) app()->environment(), true);

        $referrerPolicy = $inProduction
            ? 'strict-origin-when-cross-origin'
            : 'no-referrer-when-downgrade';

        $response->headers->set(key: 'Referrer-Policy', values: $referrerPolicy);

        if ($inProduction) {
            $response->headers->set(
                key: 'Strict-Transport-Security',
                values: 'max-age=31536000; includeSubDomains; preload'
            );

            $response->headers->set(
                key: 'Permissions-Policy',
                values: 'geolocation=(), microphone=(), camera=()'
            );

            $response->headers->set(
                key: 'Expect-CT',
                values: 'max-age=86400, enforce'
            );

            $response->headers->set(
                key: 'Cache-Control',
                values: 'no-store, no-cache, must-revalidate, max-age=0'
            );
        }

        return $response;
    }
}
