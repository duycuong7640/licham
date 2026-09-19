<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GzipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof \Illuminate\Http\Response) {
            $content = $response->getContent();

            if (substr_count($request->header('Accept-Encoding'), 'gzip')) {
                $response->header('Content-Encoding', 'gzip');
                $response->setContent(gzencode($content, 9));
            }
        }

        return $response;
    }
}
