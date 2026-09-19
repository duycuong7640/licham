<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $csp = "default-src 'self' abc.com;";
        $csp .= "script-src 'self' abc.com https://www.google.com https://www.gstatic.com;";
        $csp .= "style-src 'self' abc.com https://fonts.googleapis.com;";
        $csp .= "img-src 'self' abc.com https://www.google-analytics.com;";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
