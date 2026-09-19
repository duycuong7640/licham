<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class HtmlCacheMiddleware
{
    public function handle($request, Closure $next)
    {
//        $key = 'html_cache:' . $request->fullUrl();
//
//        if (Cache::has($key)) {
//            return response(Cache::get($key));
//        }

        $response = $next($request);

//        if ($response->isSuccessful() && $response->headers->get('Content-Type') === 'text/html') {
//            Cache::put($key, $response->getContent(), now()->addMinutes(15));
//        }

        return $response;
    }
}
