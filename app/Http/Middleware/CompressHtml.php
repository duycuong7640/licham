<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class CompressHtml
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
//    public function handle($request, Closure $next)
//    {
//        $cacheKey = 'compressed_html_' . md5($request->fullUrl());
//        $response = $next($request);
//
//        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
//            if (Cache::has($cacheKey)) {
//                $compressedContent = Cache::get($cacheKey);
//            } else {
//                $responseContent = $response->getContent();
//                $compressedContent = preg_replace('/<!--.*?-->/', '', $responseContent);
//                $compressedContent = preg_replace('/>\s+</', '><', $compressedContent);
//                $compressedContent = preg_replace('/\s+/', ' ', $compressedContent);
//                 Cache::put($cacheKey, $compressedContent, 30); // Cache 60 phút
//            }
//
//            // Đặt nội dung đã nén vào phản hồi
//            $response->setContent($compressedContent);
//        }
//
//        return $response;
//    }

//    public function handle($request, Closure $next)
//    {
//        $cacheKey = 'compressed_html_' . md5($request->fullUrl());
//        $compressedContent = RequestHelpers::getRedis($cacheKey);
//        $compressedContent = !empty($compressedContent) ? @json_decode($compressedContent) : '';
//        if ($compressedContent) return response($compressedContent);
//
//        $response = $next($request);
//        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
//            $responseContent = $response->getContent();
//            $compressedContent = preg_replace('/<!--.*?-->/', '', $responseContent);
//            $compressedContent = preg_replace('/>\s+</', '><', $compressedContent);
//            $compressedContent = preg_replace('/\s+/', ' ', $compressedContent);
//            RequestHelpers::setRedis($cacheKey, $compressedContent, env('COMPRESSED_HTML_EXPIRE', '900'));
//            $response->setContent($compressedContent);
//        }
//
//        return $response;
//    }
}
