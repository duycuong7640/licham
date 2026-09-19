<?php

namespace Modules\Pages\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;

class PostsController extends Controller
{

    /**
     * Show the specified resource.
     * @param $keyword
     * @return Object
     */
    public function search(Request $request)
    {
        try {
            $keyword = !empty($request->get('keyword')) ? $request->get('keyword') : '';
            if (!$keyword || count_chars($keyword) <= 3) return redirect(route('page.home'));

            $config = $request->get('configData');
            $siteName = !empty($config['setting']['title']) ? $config['setting']['title'] : env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Tìm kiếm ',
                'meta_des' => '',
                'meta_key' => '',
            ];

            $data['seo'] = $SEO;
            $data['common'] = Helpers::metaHead($SEO);
            $data['shareMXH'] = Helpers::renderShareMXH('home', $SEO);

            $data['lists'] = RequestHelpers::request($request, \dataApiRoutes::POSTS_SEARCH, \dataApiRoutes::POSTS_SEARCH, ['title' => $keyword, 'type' => implode(',', Helpers::mergeTypes(\dataType::TYPES, 'all')), 'limit' => 10], 'post');
            $data['tabWhereTravels'] = RequestHelpers::request($request, \dataApiRoutes::POSTS, \dataApiRoutes::POSTS, ['isPage' => 'lists', 'key' => 'home_tabWhereTravels', 'limit' => 5, 'isRand' => 1, 'type' => implode(',', Helpers::mergeRootRegionTypes([\dataType::TYPES['TRAVEL']], [\dataRegion::REGION['HA_NOI'], \dataRegion::REGION['DA_NANG'], \dataRegion::REGION['THANH_PHO-HO-CHI-MINH'], \dataRegion::REGION['PHU_QUOC'], \dataRegion::REGION['NHA_TRANG']]))], 'post');
            $data['tabEatWhatTravels'] = RequestHelpers::request($request, \dataApiRoutes::POSTS, \dataApiRoutes::POSTS, ['isPage' => 'lists', 'key' => 'home_tabEatWhatTravels', 'limit' => 5, 'isRand' => 1, 'type' => implode(',', Helpers::mergeRootTypes([\dataType::TYPES['TRAVEL_FOOD']]))], 'post');
            $data['tabPlayWhatTravels'] = RequestHelpers::request($request, \dataApiRoutes::POSTS, \dataApiRoutes::POSTS, ['isPage' => 'lists', 'key' => 'home_tabPlayWhatTravels', 'limit' => 5, 'isRand' => 1, 'type' => implode(',', Helpers::mergeRootTypes([\dataType::TYPES['TRAVEL_SUGGEST']]))], 'post');
            $data['search'] = 1;

            return view('pages::posts.search')->with('data', $data);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param $slug
     * @param Request $request
     * @return Object
     */
    public function show($slug, Request $request)
    {
        try {
            $cacheKey = 'post_detail_html_' . md5($slug);
            $ttl = now()->addMinutes(5);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', 'text/html')
                    ->header('Cache-Control', 'public, max-age=300');
            }

            $data['cache'] = 1;
            $data['detail'] = RequestHelpers::request($request, \dataApiRoutes::POST_DETAIL, str_replace(':slug', $slug, \dataApiRoutes::POST_DETAIL), ['DOMAIN_RUN' => env('DOMAIN_RUN')], 'get');
            if (empty($data['detail']['id'])) {
                return response()->view('errors.404', [], 404);
            }
            if (!strpos($data['detail']['id'] . '-' . $data['detail']['type'], 'CALENDARGOOD_')) return response()->view('errors.404', [], 404);
            $data['category'] = Helpers::findByType(str_replace('CALENDARGOOD_', '', $data['detail']['type']));
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey($data['category']['parent']);
            $dataMerge['showFoods'] = [];
            $dataMerge['showReviews'] = [];
            $data['detail']['tableOfContent'] = \App\Helpers\Helpers::buildTocContent(\App\Helpers\Helpers::renderContent($data['detail']), $dataMerge, ['title' => $data['category']['title'], 'key_seo' => $data['category']['meta_key']]);

            $canonical = route('page.post.show', ['slug' => $data['detail']['slug'],]);
            $articleImage = !empty($data['detail']['thumbnail']) ? Helpers::renderThumb($data['detail']['thumbnail']) : (!empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '');
            $plainDescription = trim(preg_replace('/\s+/u', ' ', strip_tags($data['detail']['description'] ?? '')));
            $metaDescription = !empty($data['detail']['metaDes']) ? $data['detail']['metaDes'] : Str::limit($plainDescription, 155, '');

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['detail']['titleSeo']) ? $data['detail']['titleSeo'] : $data['detail']['title'],
                'meta_des' => $metaDescription,
                'meta_key' => '',
                'canonical' => $canonical,
                'robots' => 'index, follow',
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;

            // return view('pages::posts.show')->with('data', $data);
            $html = view('pages::posts.show')->with('data', $data)->render();
            $html = Helpers::genCsrfToken($html, '1');
            $response = response($html)
                ->header('Content-Type', 'text/html; charset=UTF-8');
            $response = Helpers::optimize_html($response);
            Cache::put($cacheKey, $response->getContent(), $ttl);

            return $response->header('Content-Type', 'text/html')
                ->header('Cache-Control', 'public, max-age=300');
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function tags($slug, Request $request)
    {
        try {
            $page = !empty($request->get('page')) ? $request->get('page') : '1';

            $cacheKey = 'post_list_html_' . md5($slug) . '_' . $page;
            $ttl = now()->addMinutes(5);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $hashTags = RequestHelpers::request($request, \dataApiRoutes::HASHTAGS, \dataApiRoutes::HASHTAGS, ['isPage' => 'lists', 'keySlug' => 'hashtag', 'limit' => 100, 'orderField' => 'created_at', 'orderType' => 'ASC', 'type' => 'CALENDARGOOD'], 'get');
            $hashTagId = '';
            $data['hashTag'] = [];
            if (!empty($hashTags)) {
                foreach ($hashTags as $row) {
                    if ($row['slug'] == $slug) {
                        $hashTagId = $row['id'];
                        $data['hashTag'] = $row;
                    }
                }
            }
            if (!$hashTagId) return response()->view('errors.404', [], 404);

            if ($data['hashTag']['typeCategory'] == '12_CUNG') {
                $data['hashTags'] = RequestHelpers::request($request, \dataApiRoutes::HASHTAGS, \dataApiRoutes::HASHTAGS, ['isPage' => 'lists', 'keySlug' => '12-con-giap', 'limit' => 12, 'orderField' => 'created_at', 'orderType' => 'ASC', 'type' => 'CALENDARGOOD', 'typeCategory' => '12_CUNG'], 'get');
            }

            if ($data['hashTag']['typeCategory'] == '12_CUNG_HD') {
                $data['12CungHoangDao'] = RequestHelpers::request($request, \dataApiRoutes::HASHTAGS, \dataApiRoutes::HASHTAGS, ['isPage' => 'lists', 'keySlug' => '12-cung-hoang-dao', 'limit' => 12, 'orderField' => 'created_at', 'orderType' => 'ASC', 'type' => 'CALENDARGOOD', 'typeCategory' => '12_CUNG_HD'], 'get');
            }

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['hashTag']['titleSeo']) ? $data['hashTag']['titleSeo'] : '',
                'meta_des' => !empty($data['hashTag']['metaDes']) ? $data['hashTag']['metaDes'] : '',
                'meta_key' => !empty($data['hashTag']['metaKey']) ? $data['hashTag']['metaKey'] : '',
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);

            $data['lists'] = RequestHelpers::request($request, \dataApiRoutes::POST_BY_TAGS, \dataApiRoutes::POST_BY_TAGS, ['isPage' => 'cate', 'keySlug' => $slug, 'paginate' => 12, 'orderField' => 'created_at', 'orderType' => 'DESC', 'DOMAIN_RUN' => env('DOMAIN_RUN'), 'type' => 'CALENDARGOOD_HOROSCOPE_TAG_TV_12_CON_GIAP, CALENDARGOOD_HOROSCOPE_TAG_TV_12_CUNG_HOANG_DAO', "HASHTAG_IDS" => $hashTagId, 'page' => $page], 'post');

            // return view('pages::posts.index')->with('data', $data);
            $html = view('pages::posts.index')->with('data', $data)->render();
            $html = Helpers::genCsrfToken($html, '1');
            $response = response($html)
                ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE);
            $response = Helpers::optimize_html($response);
            Cache::put($cacheKey, $response->getContent(), $ttl);

            return $response->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                ->header('Cache-Control', \dataKey::CACHE);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function policy($slug, Request $request)
    {
        switch ($slug) {
            case 'gioi-thieu':
                return $this->pageAbout($request);
            case 'dieu-khoan-su-dung':
                return $this->pageDKSD($request);
            case 'chinh-sach-bao-mat':
                return $this->pageCSBM($request);
//            case 'lien-he':
//                return $this->pageContact($request);
        }

        return response()->view('errors.404', [], 404);
    }

    public function pageAbout($request)
    {
        try {
            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Giới thiệu Lịch Số - Nội dung và công cụ tra cứu hữu ích',
                'meta_des' => 'Giới thiệu Lịch Số, website cung cấp nội dung và công cụ tra cứu về lịch, tử vi, phong thủy, văn hóa dân gian cùng nhiều chủ đề đời sống.',
                'meta_key' => '',
                'canonical' => route('page.policy.index', ['slug' => 'gioi-thieu']),
                'robots' => 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['category'] = ["title" => 'Giới thiệu', 'slug' => 'gioi-thieu'];

            return view('pages::pages.about')->with('data', $data);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function pageDKSD($request)
    {
        try {
            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Điều khoản sử dụng website - Lịch Số',
                'meta_des' => 'Tìm hiểu các điều khoản và quy định khi truy cập, sử dụng nội dung, dữ liệu và các tiện ích được cung cấp trên website Lịch Số.',
                'meta_key' => '',
                'canonical' => route('page.policy.index', ['slug' => 'dieu-khoan-su-dung']),
                'robots' => 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['category'] = ["title" => 'Điều khoản sử dụng', 'slug' => 'dieu-khoan-su-dung'];

            return view('pages::pages.term')->with('data', $data);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function pageCSBM($request)
    {
        try {
            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Điều khoản sử dụng - Lịch Số',
                'meta_des' => 'Điều khoản sử dụng quy định việc truy cập, sử dụng nội dung và các công cụ về tử vi, phong thủy, xem ngày, xem tuổi cùng các tiện ích trên Lịch Số.',
                'meta_key' => '',
                'canonical' => route('page.policy.index', ['slug' => 'chinh-sach-bao-mat']),
                'robots' => 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['category'] = ["title" => 'Chính sách bảo mật', 'slug' => 'chinh-sach-bao-mat'];

            return view('pages::pages.policy')->with('data', $data);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function pageContact($request)
    {
        try {

        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }
}
