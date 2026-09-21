<?php

namespace Modules\Pages\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\RequestApiHelpers;
use App\Helpers\RequestHelpers;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use function Psy\debug;

class CategoriesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @param $slug
     * @param Request $request
     * @return object
     */

    public function bridge($slug, Request $request)
    {
        if ($slug == 'doi-ngay-am-duong') {
            return $this->changeDuongAm($slug, $request);
        } elseif ($slug == 'bai-viet') {
            return $this->listPosts($slug, $request);
        }
    }

    public function changeDuongAm($slug, $request)
    {
        try {
            $params = $request->only('date');
            $d = date('d');
            $m = date('m');
            $y = date('Y');
            if (!empty($params['date'])) {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $params['date']);
                $d = $date->day;
                $m = $date->month;
                $y = $date->year;
            }
            $day = $d . '-' . $m . '-' . $y;


//            $page = !empty($request->get('page')) ? $request->get('page') : '1';
//            $cacheKey = 'category_news_page_html_' . $slug . '_' . $page;
//            $ttl = now()->addMinutes(5);
//            if (Cache::has($cacheKey) && !$request->has('reset')) {
//                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
//                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                    ->header('Cache-Control', \dataKey::CACHE);
//            }
//            $data['cache'] = 1;

            $data['day'] = RequestHelpers::request($request, \dataApiRoutes::COPE_DETAIL, str_replace(':day', $day, \dataApiRoutes::COPE_DETAIL), [], 'get');
            if (empty($data['day']['id'])) {
                return response()->view('errors.404', [], 404);
            }
            $data['months'] = RequestHelpers::request($request, \dataApiRoutes::COPE_MONTH, str_replace(':month', $m, str_replace(':year', $y, \dataApiRoutes::COPE_MONTH)), [], 'get');

            $canonical = route('page.cate.index', ['slug' => $slug]);
            $titleSeo = '';
            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => $titleSeo,
                'meta_des' => '',
                'meta_key' => '',
                'canonical' => $canonical,
                'robots' => 'index, follow',
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['isPage'] = 'fixed';
            $data['category'] = ['title' => 'Đổi ngày âm dương', 'slug' => 'doi-ngay-am-duong'];

            return view('pages::pages.changeAD')->with('data', $data);
//            $html = view('pages::posts.index')->with('data', $data)->render();
//            $html = Helpers::genCsrfToken($html, '1');
//            $response = response($html)
//                ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE);
//            $response = Helpers::optimize_html($response);
//            Cache::put($cacheKey, $response->getContent(), $ttl);
//
//            return $response->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                ->header('Cache-Control', \dataKey::CACHE);
        } catch (\Exception $e) {
            Helpers::pre($e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }

    public function listPosts($slug, $request)
    {
        try {
            $page = !empty($request->get('page')) ? $request->get('page') : '1';

//            $cacheKey = 'post_list_hashtag_html_' . md5($slug) . '_' . $page;
//            $ttl = now()->addMinutes(5);
//
//            if (Cache::has($cacheKey)) {
//                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
//                    ->header('Content-Type', 'text/html')
//                    ->header('Cache-Control', 'public, max-age=1800');
//            }

            $config = $request->get('configData', []);
            $setting = $config['setting'] ?? [];
            $siteName = !empty($setting['title']) ? $setting['title'] : env('SITE_NAME');
            $canonical = route('page.cate.index', ['slug' => $slug]);
            if ((int)$page > 1) $canonical .= '?page=' . (int)$page;
            $SEO = [
                'name' => $siteName,
                'slug' => 'bai-viet',
                'logo' => !empty($setting['thumbnail']) ? Helpers::renderThumb($setting['thumbnail']) : asset('static/web/images/logo.png'),
                'logo_share' => !empty($setting['articleThumbnailShare']) ? Helpers::renderThumb($setting['articleThumbnailShare']) : (!empty($setting['thumbnailShare']) ? Helpers::renderThumb($setting['thumbnailShare']) : asset('static/web/images/share.png')),
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => '',
                'meta_des' => '',
                'meta_key' => '',
                'canonical' => $canonical,
            ];

            $data['seo'] = $SEO;
            $data['common'] = Helpers::metaHead($SEO);
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO, $config);
            $data['show'] = 1;
            $data['page'] = 'bai-viet';
            $data['category'] = ["title" => 'Bài viết', 'slug' => 'bai-viet', 'isLast' => true];

            $data['hashTags'] = RequestHelpers::request($request, \dataApiRoutes::HASHTAGS, \dataApiRoutes::HASHTAGS, ['isPage' => 'lists', 'keySlug' => 'lich-am', 'limit' => 12, 'orderField' => 'created_at', 'orderType' => 'ASC', 'type' => 'CALENDARLUNAR', 'typeCategory' => 'LICH_AM', 'page' => $page], 'get');
            $hastag_ids = [];
            foreach ($data['hashTags'] as $k => $row) {
                $hastag_ids[] = $row['id'];
            }
            $data['lists'] = RequestHelpers::request($request, \dataApiRoutes::POST_BY_TAGS, \dataApiRoutes::POST_BY_TAGS, ['isPage' => 'cate', 'keySlug' => $slug, 'limit' => 5, 'orderField' => 'created_at', 'orderType' => 'DESC', 'DOMAIN_RUN' => env('DOMAIN_RUN'), 'type' => 'CALENDARLUNAR_NEWS', "HASHTAG_IDS" => implode(',', $hastag_ids), 'page' => $page], 'post');

            return view('pages::posts.index')->with('data', $data);
//            $html = view('pages::pages.baiviet')->with('data', $data)->render();
//            $html = Helpers::genCsrfToken($html, '1');
//            $response = response($html)
//                ->header('Content-Type', 'text/html; charset=UTF-8');
//            $response = Helpers::optimize_html($response);
//            Cache::put($cacheKey, $response->getContent(), $ttl);
//
//            return $response->header('Content-Type', 'text/html')
//                ->header('Cache-Control', 'public, max-age=1800');
        } catch (\Exception $e) {
            Helpers::pre($e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }
}
