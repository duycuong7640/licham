<?php

namespace Modules\Pages\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeedController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return object
     */
    public function index(Request $request)
    {
        try {
            $posts = RequestHelpers::request($request, \dataApiRoutes::POSTS, \dataApiRoutes::POSTS, ['isPage' => 'rss', 'key' => 'rss', 'limit' => 50, 'orderField' => 'created_at', 'orderType' => 'ASC', 'DOMAIN_RUN' => env('DOMAIN_RUN'), 'page' => '1'], 'post');
            $config = $request->get('configData');
            return response()
                ->view('pages::rss.index', compact('posts', 'config'))
                ->header('Content-Type', 'application/xml');

        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

}
