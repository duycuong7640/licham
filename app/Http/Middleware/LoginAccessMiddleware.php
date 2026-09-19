<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LoginAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        if (!Session::has(\dataKey::SESSION_LOGIN_TOKEN)) {
//            return redirect(route('page.login'));
//        }

        if (!Helpers::checkCookie($request, \dataKey::SESSION_LOGIN_TOKEN)) {
            return redirect(route('page.login'));
        }

        return $next($request);
    }
}
