<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReferer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowed_domains = ['novelfe', 'dicnovel', 'facebook', 'twitter', 'fb.com', 'youtube', 'linkedin', 'pinterest', 'instagram', 'flickr', 'tumblr', 'google'];
        $referer = $request->headers->get('referer');

        if ($referer) {
            $allowed = false;
            foreach ($allowed_domains as $domain) {
                if (strpos($referer, $domain) !== false) {
                    $allowed = true;
                    break;
                }
            }

            if (!$allowed) {
                return response('Access denied', 403);
            }
        }

        return $next($request);
    }
}
