<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class DataConfigMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Load api
            $config = RequestHelpers::request($request, \dataApiRoutes::CONFIG, \dataApiRoutes::CONFIG, ['domain' => env('DOMAIN_RUN')], 'get');
            $calendarMonth = RequestHelpers::request($request, \dataApiRoutes::COPE_CALENDAR_MONTH, str_replace(':month', date('m'), str_replace(':year', date('Y'), \dataApiRoutes::COPE_CALENDAR_MONTH)), [], 'get');
            $topRankPost = RequestHelpers::request($request, \dataApiRoutes::POSTS, \dataApiRoutes::POSTS, ['isPage' => 'rand', 'limit' => 10, 'keySlug' => 'top-rank-post', 'DOMAIN_RUN' => env('DOMAIN_RUN'), 'isRand' => '1', 'page' => '1'], 'post');

            // Set View
            View::share('configData', $config);
            View::share('calendarMonth', $calendarMonth);
            View::share('topRankPost', $topRankPost);
            View::share('menu', \dataMenu::menus());

            $request->attributes->add(['configData' => $config]);
        } catch (\Exception $e) {
        }
        return $next($request);
    }
}
