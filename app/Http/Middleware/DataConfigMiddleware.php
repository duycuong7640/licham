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
            $hashTags = RequestHelpers::request($request, \dataApiRoutes::HASHTAGS, \dataApiRoutes::HASHTAGS, ['isPage' => 'lists', 'keySlug' => 'lich-am', 'limit' => 12, 'orderField' => 'created_at', 'orderType' => 'ASC', 'type' => 'CALENDARLUNAR', 'typeCategory' => 'LICH_AM', 'page' => 1], 'get');

            // Set View
            View::share('configData', $config);
            View::share('hashTags', $hashTags);
            View::share('menu', \dataMenu::menus());

            $request->attributes->add(['configData' => $config]);
            $request->attributes->add(['hashTags' => $hashTags]);
        } catch (\Exception $e) {
        }
        return $next($request);
    }
}
