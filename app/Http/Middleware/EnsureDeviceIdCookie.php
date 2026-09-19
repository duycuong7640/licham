<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureDeviceIdCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookieName = 'device_id';

        $deviceId = $request->cookie($cookieName);
        $needSetCookie = false;

        if (empty($deviceId)) {
            $deviceId = (string) Str::uuid();
            $needSetCookie = true;

            // Để controller/service dùng được ngay
            $request->cookies->set($cookieName, $deviceId);
        }

        $response = $next($request);

        if ($needSetCookie) {
            $response->headers->setCookie(
                Cookie::make(
                    $cookieName,
                    $deviceId,
                    60 * 24 * 365 * 3,
                    '/',
                    null,
                    app()->environment('production'),
                    true,
                    false,
                    'Lax'
                )
            );
        }

        return $response;
    }
}
