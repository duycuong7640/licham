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
        if ($slug == 'xem-tinh-duyen') {
            return $this->xemTinhDuyen($slug, $request);
        } elseif ($slug == 'xem-nam-lay-chong') {
            return $this->xemNamLayChong($slug, $request);
        } elseif ($slug == 'xem-nam-lay-vo') {
            return $this->xemNamLayVo($slug, $request);
        } elseif ($slug == 'sinh-con-hop-tuoi') {
            return $this->xemSinhConHopTuoi($slug, $request);
        } elseif ($slug == 'sinh-con-theo-y-muon') {
            return $this->xemSinhConTheoYMuon($slug, $request);
        } elseif ($slug == 'xem-tuoi-xay-nha') {
            return $this->xemXemTuoiXayNha($slug, $request);
        } elseif ($slug == 'can-xuong-tinh-so') {
            return $this->xemCanXuongTinhSo($slug, $request);
        } elseif ($slug == 'cach-tinh-trung-tang') {
            return $this->xemTinhTrungTang($slug, $request);
        } elseif ($slug == 'xem-boi-ngay-sinh-gio-sinh') {
            return $this->xemBoiNgaySinhGioSinh($slug, $request);
        } elseif ($slug == 'tra-cuu-than-so-hoc' || $slug == 'than-so-hoc') {
            return $this->xemThanSoHoc($slug, $request);
        } elseif ($slug == 'lap-la-so-tu-vi') {
            return $this->xemLaSoTuVi($slug, $request);
        } elseif (strpos('PRE_' . $slug, 'tu-vi-12-cung-hoang-dao-hang-ngay')) {
            return $this->xemTuViHangNgay($slug, $request);
        } elseif ($slug == 'xem-tuoi-xong-dat') {
            return $this->xemTuoiXongDat($slug, $request);
        } elseif ($slug == 'xem-tuoi-vo-chong') {
            return $this->xemTuoiVoChong($slug, $request);
        } elseif ($slug == 'xem-tuoi-sinh-con') {
            return $this->xemTuoiSinhCon($slug, $request);
        } elseif ($slug == 'xem-tuoi-ket-hon') {
            return $this->xemTuoiKetHon($slug, $request);
        } elseif ($slug == 'xem-tuoi-hop-nhau') {
            return $this->xemTuoiHopNhau($slug, $request);
        } elseif ($slug == 'xem-tuoi-lam-an') {
            return $this->xemTuoiLamAn($slug, $request);
        } elseif ($slug == 'xem-tuoi-lam-nha') {
            return $this->xemTuoiLamNha($slug, $request);
        } else {
            return $this->listPosts($slug, $request);
        }
    }

    public function listPosts($slug, $request)
    {
        try {
            $page = !empty($request->get('page')) ? $request->get('page') : '1';
            $cacheKey = 'category_news_page_html_' . $slug . '_' . $page;
            $ttl = now()->addMinutes(5);
            if (Cache::has($cacheKey) && !$request->has('reset')) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }
            $data['cache'] = 1;

            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $canonical = route('page.cate.index', ['slug' => $data['category']['slug']]);
            if ($page > 1) $canonical .= '?page=' . $page;
            $titleSeo = $data['category']['title_seo'] ?? '';
            if ($page > 1) $titleSeo .= ' - Trang ' . $page;
            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => $titleSeo,
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => $canonical,
                'robots' => 'index, follow',
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['category']['type'] = Helpers::genTypes($data['category']);
            $data['lists'] = RequestHelpers::request($request, \dataApiRoutes::POSTS, \dataApiRoutes::POSTS, ['isPage' => 'cate', 'keySlug' => $slug, 'limit' => 12, 'paginate' => 12, 'orderField' => 'created_at', 'orderType' => 'DESC', 'DOMAIN_RUN' => env('DOMAIN_RUN'), 'type' => $data['category']['type'], 'page' => $page], 'post');
            $data['typeCalendar'] = Helpers::genMenu(\dataMenu::menus());

            if (strpos('PRE_' . $data['category']['type'], 'HOROSCOPE_')) {
                $data['hashTags'] = RequestHelpers::request($request, \dataApiRoutes::HASHTAGS, \dataApiRoutes::HASHTAGS, ['isPage' => 'lists', 'keySlug' => '12-con-giap', 'limit' => 12, 'orderField' => 'created_at', 'orderType' => 'ASC', 'type' => 'CALENDARGOOD', 'typeCategory' => '12_CUNG'], 'get');
            }

            if (strpos('PRE_' . $data['category']['type'], 'ZODIAC_')) {
                $data['12CungHoangDao'] = RequestHelpers::request($request, \dataApiRoutes::HASHTAGS, \dataApiRoutes::HASHTAGS, ['isPage' => 'lists', 'keySlug' => '12-cung-hoang-dao', 'limit' => 12, 'orderField' => 'created_at', 'orderType' => 'ASC', 'type' => 'CALENDARGOOD', 'typeCategory' => '12_CUNG_HD'], 'get');
            }

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

    public function xemLaSoTuVi($slug, $request)
    {
        try {
            $data['detail'] = [];
            $params = $request->only(['name', 'day', 'month', 'year', 'calendarType', 'timeOfBirth', 'gender', 'viewYear']);
            $hasResultParams = $request->query->count() > 0;
            if (
                !empty($params['name']) &&
                !empty($params['day']) &&
                !empty($params['month']) &&
                !empty($params['year']) &&
                !empty($params['calendarType']) &&
                !empty($params['timeOfBirth']) &&
                !empty($params['gender']) &&
                !empty($params['viewYear'])
            ) {
                $params['timeZone'] = 7;
                $time = Helpers::getHourMinuteByChi($params['timeOfBirth']);
                if (empty($time['hour'])) $params['timeOfBirth'] = '';
                $params['hour'] = !empty($time['hour']) ? $time['hour'] : '';
                $params['minute'] = !empty($time['minute']) ? $time['minute'] : '';
                $params['clientId'] = !empty($user['id']) ? $user['id'] : '';
                $params['browserId'] = 'HS-' . Helpers::getCookie($request, 'device_id');

                $data['page'] = 'chart-detail-page';
                $data['params'] = $params;
                $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_LAS_SO_TU_VI, \dataApiRoutes::FORTUNE_LAS_SO_TU_VI, array_merge($params), 'post');
            }

            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);

            return view('pages::horoscope.laso')->with('data', $data);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function xemTinhDuyen($slug, $request)
    {
        try {
            // check post
            $params = $request->only('maleSolarDate', 'femaleSolarDate');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['maleSolarDate'])) $params['maleSolarDate'] = '1995-05-15';
            if (empty($params['femaleSolarDate'])) $params['femaleSolarDate'] = '1998-08-18';

            $params['maleSolarDate'] = Carbon::createFromFormat('Y-m-d', $params['maleSolarDate'])->format('Y-m-d');
            $params['femaleSolarDate'] = Carbon::createFromFormat('Y-m-d', $params['femaleSolarDate'])->format('Y-m-d');

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);
            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_TINH_DUYEN, \dataApiRoutes::FORTUNE_TINH_DUYEN, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.tinhduyen')->with('data', $data);
            $html = view('pages::fortune.tinhduyen')->with('data', $data)->render();
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

    public function xemNamLayChong($slug, $request)
    {
        try {
            // check post
            $params = $request->only('femaleSolarDate', 'weddingYear');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['femaleSolarDate'])) $params['femaleSolarDate'] = '18/08/1998';
            if (empty($params['weddingYear'])) $params['weddingYear'] = 2020;
            $params['femaleSolarDate'] = Carbon::createFromFormat('d/m/Y', $params['femaleSolarDate'])->format('Y-m-d');

            if ($params['weddingYear'] < 1900 || $params['weddingYear'] > 2050) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);
            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_NAM_LAY_CHONG, \dataApiRoutes::FORTUNE_NAM_LAY_CHONG, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.laychong')->with('data', $data);
            $html = view('pages::fortune.laychong')->with('data', $data)->render();
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

    public function xemNamLayVo($slug, $request)
    {
        try {
            // check post
            $params = $request->only('maleSolarDate', 'weddingYear');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['maleSolarDate'])) $params['maleSolarDate'] = '18/08/1998';
            if (empty($params['weddingYear'])) $params['weddingYear'] = 2020;
            $params['maleSolarDate'] = Carbon::createFromFormat('d/m/Y', $params['maleSolarDate'])->format('Y-m-d');

            if ($params['weddingYear'] < 1900 || $params['weddingYear'] > 2050) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);
            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_NAM_LAY_VO, \dataApiRoutes::FORTUNE_NAM_LAY_VO, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.layvo')->with('data', $data);
            $html = view('pages::fortune.layvo')->with('data', $data)->render();
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

    public function xemSinhConHopTuoi($slug, $request)
    {
        try {
            // check post
            $params = $request->only('fatherSolarDate', 'motherSolarDate', 'expectedBirthYear');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['fatherSolarDate'])) $params['fatherSolarDate'] = '01/06/1990';
            if (empty($params['motherSolarDate'])) $params['motherSolarDate'] = '01/06/1996';
            if (empty($params['expectedBirthYear'])) $params['expectedBirthYear'] = '2022';
            $params['fatherSolarDate'] = Carbon::createFromFormat('d/m/Y', $params['fatherSolarDate'])->format('Y-m-d');
            $params['motherSolarDate'] = Carbon::createFromFormat('d/m/Y', $params['motherSolarDate'])->format('Y-m-d');

            if ($params['expectedBirthYear'] < 1900 || $params['expectedBirthYear'] > 2050) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);
            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_SINH_CON_HOP_TUOI, \dataApiRoutes::FORTUNE_SINH_CON_HOP_TUOI, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.sinhconhoptuoi')->with('data', $data);
            $html = view('pages::fortune.sinhconhoptuoi')->with('data', $data)->render();
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

    public function xemSinhConTheoYMuon($slug, $request)
    {
        try {
            $params = $request->only('fatherSolarDate', 'motherSolarDate', 'conceptionMonth', 'conceptionYear', 'birthMonth', 'birthYear');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['fatherSolarDate'])) $params['fatherSolarDate'] = '01/06/1990';
            if (empty($params['motherSolarDate'])) $params['motherSolarDate'] = '01/06/1996';
            if (empty($params['conceptionMonth'])) $params['conceptionMonth'] = '6';
            if (empty($params['conceptionYear'])) $params['conceptionYear'] = '2021';
            if (empty($params['birthMonth'])) $params['birthMonth'] = '4';
            if (empty($params['birthYear'])) $params['birthYear'] = '2022';
            $params['fatherSolarDate'] = Carbon::createFromFormat('d/m/Y', $params['fatherSolarDate'])->format('Y-m-d');
            $params['motherSolarDate'] = Carbon::createFromFormat('d/m/Y', $params['motherSolarDate'])->format('Y-m-d');

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_SINH_CON_THEO_Y_MUON, \dataApiRoutes::FORTUNE_SINH_CON_THEO_Y_MUON, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.sinhcontheoymuon')->with('data', $data);
            $html = view('pages::fortune.sinhcontheoymuon')->with('data', $data)->render();
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

    public function xemXemTuoiXayNha($slug, $request)
    {
        try {
            $params = $request->only('solarDate', 'year');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['solarDate'])) $params['solarDate'] = '18/08/1998';
            if (empty($params['year'])) $params['year'] = 2020;
            $params['solarDate'] = Carbon::createFromFormat('d/m/Y', $params['solarDate'])->format('Y-m-d');
            if ($params['year'] < 1900 || $params['year'] > 2050) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_XEM_TUOI_XAY_NHA, \dataApiRoutes::FORTUNE_XEM_TUOI_XAY_NHA, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.xemtuoixaynha')->with('data', $data);
            $html = view('pages::fortune.xemtuoixaynha')->with('data', $data)->render();

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

    public function xemCanXuongTinhSo($slug, $request)
    {
        try {
            $params = $request->only('solarDate', 'birthHour');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['solarDate'])) $params['solarDate'] = '18/08/1998';
            if (empty($params['birthHour'])) $params['birthHour'] = 'thin';
            $params['solarDate'] = Carbon::createFromFormat('d/m/Y', $params['solarDate'])->format('Y-m-d');
            if (!in_array($params['birthHour'], array_keys(\dataKey::GIO_SINH))) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_CAN_XUONG_TINH_SO, \dataApiRoutes::FORTUNE_CAN_XUONG_TINH_SO, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.canxuongtinhso')->with('data', $data);
            $html = view('pages::fortune.canxuongtinhso')->with('data', $data)->render();
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

    public function xemTinhTrungTang($slug, $request)
    {
        try {
            $params = $request->only('deathSolarDate', 'deathHour', 'gender', 'birthLunarYear');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['deathSolarDate'])) $params['deathSolarDate'] = '01/06/2026';
            if (empty($params['deathHour'])) $params['deathHour'] = 'suu';
            if (empty($params['gender'])) $params['gender'] = 'male';
            if (empty($params['birthLunarYear'])) $params['birthLunarYear'] = '1945';
            $params['deathSolarDate'] = Carbon::createFromFormat('d/m/Y', $params['deathSolarDate'])->format('Y-m-d');
            if (!in_array($params['gender'], ['male', 'female'])) return response()->view('errors.404', [], 404);
            if (!in_array($params['deathHour'], array_keys(\dataKey::GIO_SINH))) return response()->view('errors.404', [], 404);
            if ($params['birthLunarYear'] < 1900 || $params['birthLunarYear'] > 2050) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_TINH_TRUNG_TANG, \dataApiRoutes::FORTUNE_TINH_TRUNG_TANG, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.tinhtrungtang')->with('data', $data);
            $html = view('pages::fortune.tinhtrungtang')->with('data', $data)->render();
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

    public function xemBoiNgaySinhGioSinh($slug, $request)
    {
        try {
            $params = $request->only('birthDate', 'gender', 'birthTime', 'birthPlace');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['birthDate'])) $params['birthDate'] = '01/06/1995';
            if (!empty($params['gender']) && !in_array($params['gender'], ['male', 'female', 'other'])) $params['gender'] = '';

            $cacheKey = 'cope_fortune_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_BOI_NGAY_SINH, \dataApiRoutes::FORTUNE_BOI_NGAY_SINH, array_merge($params, ['slug' => $slug]), 'post');

            // return view('pages::fortune.boingaysinhgiosinh')->with('data', $data);
            $html = view('pages::fortune.boingaysinhgiosinh')->with('data', $data)->render();
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

    public function xemThanSoHoc($slug, $request)
    {
        try {
            $params = $request->only('fullName', 'solarDate', 'gender');
            $hasResultParams = $request->query->count() > 0;
            if (!empty($params['solarDate'])) $params['solarDate'] = Carbon::createFromFormat('d/m/Y', $params['solarDate'])->format('Y-m-d');

            $cacheKey = 'cope_tsh_html_' . md5($slug) . '_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addMinutes(15);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            if ($data['category']['level'] == 2) $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = [];
            if (!empty($params['fullName']) && !empty($params['solarDate']) && !empty($params['gender'])) {
                $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_THAN_SO_HOC, \dataApiRoutes::FORTUNE_THAN_SO_HOC, array_merge($params, ['slug' => $slug]), 'post');
            }

            // return view('pages::tsh.search')->with('data', $data);
            $html = view('pages::tsh.search')->with('data', $data)->render();
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

    public function xemTuViHangNgay($slug, $request)
    {
        try {
            $date = Helpers::getDateFromSlug($slug);
            if (empty($date['day'])) return response()->view('errors.404', [], 404);
            if (!Helpers::checkDateWithinDays($date['date'], 15)) {
                $dateView = Carbon::createFromFormat('d-m-Y', $date['date']);
                $dateMax = $dateView->copy()->subDay()->format('d-m-Y');
                return redirect(route('page.cate.index', ['slug' => 'tu-vi-12-cung-hoang-dao-hang-ngay-' . $dateMax]));
            }

            $cacheKey = 'cope_tvhn_html_' . md5($slug);
            $ttl = now()->addDay(1);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_12_CHD_HANG_NGAY, \dataApiRoutes::FORTUNE_12_CHD_HANG_NGAY, ['targetDate' => $date['year'] . '-' . $date['month'] . '-' . $date['day']], 'post');
            if (empty($data['detail']['article']['seo']['h1'])) return response()->view('errors.404', [], 404);

            $data['category'] = [
                'title' => 'Tử vi 12 cung hàng ngày',
                'slug' => 'tu-vi-12-cung-hoang-dao-hang-ngay-' . $date['day'] . '-' . $date['month'] . '-' . $date['year'],
                'type' => 'ZODIAC_12_CUNG_EVERY_DAY'
            ];

            // range index google
            $pageDate = Carbon::createFromFormat('d-m-Y', $date['date'], 'Asia/Ho_Chi_Minh')->startOfDay();
            $currentDate = now('Asia/Ho_Chi_Minh')->startOfDay();
            $indexFrom = $currentDate->copy()->subYear();
            $indexTo = $currentDate->copy()->addDays(15);
            $isIndexable = $pageDate->betweenIncluded($indexFrom, $indexTo);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $dateDisplay = $date['day'] . '/' . $date['month'] . '/' . $date['year'];
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => "Tử vi 12 cung hoàng đạo ngày {$dateDisplay}: Cung nào may mắn?",
                'meta_des' => "Tử vi 12 cung hoàng đạo ngày {$dateDisplay}: xem công việc, tiền bạc, tình cảm và sức khỏe của từng cung; ai gặp thuận lợi, ai nên thận trọng.",
                'meta_key' => '',
                'canonical' => route('page.cate.index', ['slug' => $data['category']['slug']]),
                'robots' => $isIndexable ? 'index, follow' : 'noindex, follow',
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['12CungHoangDao'] = RequestHelpers::request($request, \dataApiRoutes::HASHTAGS, \dataApiRoutes::HASHTAGS, ['isPage' => 'lists', 'keySlug' => '12-cung-hoang-dao', 'limit' => 12, 'orderField' => 'created_at', 'orderType' => 'ASC', 'type' => 'CALENDARGOOD', 'typeCategory' => '12_CUNG_HD'], 'get');

            // return view('pages::chd.chd_every_day')->with('data', $data);
            $html = view('pages::chd.chd_every_day')->with('data', $data)->render();
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

    public function xemTuoiXongDat($slug, $request)
    {
        try {
            $params = $request->only('solarDate', 'targetYear');
            $hasResultParams = $request->query->count() > 0;
            if (empty($params['solarDate'])) $params['solarDate'] = '18/08/1998';
            if (empty($params['targetYear'])) $params['targetYear'] = date('Y');
            $params['solarDate'] = Carbon::createFromFormat('d/m/Y', $params['solarDate'])->format('Y-m-d');
            if ($params['targetYear'] <= 1900 && $params['targetYear'] > 2050) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_xong_dat_html_' . md5(@json_encode($params)) . '_' . $hasResultParams;
            $ttl = now()->addDay(30);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($slug);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
            if ($data['category']['level'] == 2) $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                'canonical' => route('page.cate.index', ['slug' => $slug]),
                'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['detail'] = [];
            if (!empty($params['solarDate']) && !empty($params['targetYear'])) {
                $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_XONG_DAT, \dataApiRoutes::FORTUNE_XONG_DAT, $params, 'post');
            }

            //return view('pages::checkage.xongdat')->with('data', $data);
            $html = view('pages::checkage.xongdat')->with('data', $data)->render();
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

    public function xemTuoiVoChong($slug, $request)
    {
        try {
            try {
                $params = $request->only('husbandSolarDate', 'wifeSolarDate');
                $hasResultParams = $request->query->count() > 0;
                if (empty($params['husbandSolarDate'])) $params['husbandSolarDate'] = '1995-05-15';
                if (empty($params['wifeSolarDate'])) $params['wifeSolarDate'] = '1998-08-18';
                $params['husbandSolarDate'] = Carbon::createFromFormat('Y-m-d', $params['husbandSolarDate'])->format('Y-m-d');
                $params['wifeSolarDate'] = Carbon::createFromFormat('Y-m-d', $params['wifeSolarDate'])->format('Y-m-d');

                $cacheKey = 'cope_tuoi_vo_chong_html_' . md5(@json_encode($params)) . '_' . $hasResultParams;
                $ttl = now()->addDay(30);

                if (Cache::has($cacheKey)) {
                    return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                        ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                        ->header('Cache-Control', \dataKey::CACHE);
                }

                $data['cache'] = 1;
                $data['category'] = Helpers::findBySlug($slug);
                if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
                if ($data['category']['level'] == 2) $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

                $config = $request->get('configData');
                $siteName = env('SITE_NAME');
                $SEO = [
                    'name' => $siteName,
                    'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                    'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                    'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                    'fav' => asset('static/web/images/favicon/favicon.ico'),
                    'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                    'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                    'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                    'canonical' => route('page.cate.index', ['slug' => $slug]),
                    'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
                ];

                $data['seo'] = $SEO;
                $data['common'] = $SEO;
                $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
                $data['show'] = 1;
                $data['detail'] = [];
                if (!empty($params['husbandSolarDate']) && !empty($params['wifeSolarDate'])) {
                    $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_TUOI_VO_CHONG, \dataApiRoutes::FORTUNE_TUOI_VO_CHONG, $params, 'post');
                }

                // return view('pages::checkage.tuoivochong')->with('data', $data);
                $html = view('pages::checkage.tuoivochong')->with('data', $data)->render();
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
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function xemTuoiSinhCon($slug, $request)
    {
        try {
            try {
                $params = $request->only('fatherSolarDate', 'motherSolarDate', 'childSolarDate');
                $hasResultParams = $request->query->count() > 0;
                if (empty($params['fatherSolarDate'])) $params['fatherSolarDate'] = '1995-05-15';
                if (empty($params['motherSolarDate'])) $params['motherSolarDate'] = '1998-08-18';
                if (empty($params['childSolarDate'])) $params['childSolarDate'] = '2022-09-25';
                $params['fatherSolarDate'] = Carbon::createFromFormat('Y-m-d', $params['fatherSolarDate'])->format('Y-m-d');
                $params['motherSolarDate'] = Carbon::createFromFormat('Y-m-d', $params['motherSolarDate'])->format('Y-m-d');
                $params['childSolarDate'] = Carbon::createFromFormat('Y-m-d', $params['childSolarDate'])->format('Y-m-d');

                $cacheKey = 'cope_tuoi_sinh_con_html_' . md5(@json_encode($params)) . '_' . $hasResultParams;
                $ttl = now()->addDay(30);

                if (Cache::has($cacheKey)) {
                    return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                        ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                        ->header('Cache-Control', \dataKey::CACHE);
                }

                $data['cache'] = 1;
                $data['category'] = Helpers::findBySlug($slug);
                if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
                if ($data['category']['level'] == 2) $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

                $config = $request->get('configData');
                $siteName = env('SITE_NAME');
                $SEO = [
                    'name' => $siteName,
                    'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                    'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                    'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                    'fav' => asset('static/web/images/favicon/favicon.ico'),
                    'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                    'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                    'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                    'canonical' => route('page.cate.index', ['slug' => $slug]),
                    'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
                ];

                $data['seo'] = $SEO;
                $data['common'] = $SEO;
                $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
                $data['show'] = 1;
                $data['detail'] = [];
                if (!empty($params['fatherSolarDate']) && !empty($params['fatherSolarDate']) && !empty($params['childSolarDate'])) {
                    $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_TUOI_SINH_CON, \dataApiRoutes::FORTUNE_TUOI_SINH_CON, $params, 'post');
                }

                // return view('pages::checkage.tuoisinhcon')->with('data', $data);
                $html = view('pages::checkage.tuoisinhcon')->with('data', $data)->render();
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
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function xemTuoiKetHon($slug, $request)
    {
        try {
            try {
                $params = $request->only('birthYear', 'weddingYear', 'gender');
                $hasResultParams = $request->query->count() > 0;
                if (empty($params['birthYear'])) $params['birthYear'] = '2000';
                if (empty($params['weddingYear'])) $params['weddingYear'] = date('Y');
                if (empty($params['gender'])) $params['gender'] = 'nam';
                if (!in_array($params['gender'], ['nam', 'nu'])) return response()->view('errors.404', [], 404);
                if ($params['birthYear'] < 1900 || $params['birthYear'] > 2050) return response()->view('errors.404', [], 404);
                if ($params['weddingYear'] < 1900 || $params['weddingYear'] > 2050) return response()->view('errors.404', [], 404);

                $cacheKey = 'cope_tuoi_ket_hon_html_' . md5(@json_encode($params)) . '_' . $hasResultParams;
                $ttl = now()->addDay(30);

                if (Cache::has($cacheKey)) {
                    return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                        ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                        ->header('Cache-Control', \dataKey::CACHE);
                }

                $data['cache'] = 1;
                $data['category'] = Helpers::findBySlug($slug);
                if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
                if ($data['category']['level'] == 2) $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

                $config = $request->get('configData');
                $siteName = env('SITE_NAME');
                $SEO = [
                    'name' => $siteName,
                    'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                    'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                    'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                    'fav' => asset('static/web/images/favicon/favicon.ico'),
                    'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                    'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                    'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                    'canonical' => route('page.cate.index', ['slug' => $slug]),
                    'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
                ];

                $data['seo'] = $SEO;
                $data['common'] = $SEO;
                $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
                $data['show'] = 1;
                $data['detail'] = [];
                if (!empty($params['birthYear']) && !empty($params['weddingYear']) && !empty($params['gender'])) {
                    $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_TUOI_KET_HON, \dataApiRoutes::FORTUNE_TUOI_KET_HON, $params, 'post');
                }

                // return view('pages::checkage.tuoikethon')->with('data', $data);
                $html = view('pages::checkage.tuoikethon')->with('data', $data)->render();
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
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function xemTuoiHopNhau($slug, $request)
    {
        try {
            try {
                $params = $request->only('lunarYear');
                $hasResultParams = $request->query->count() > 0;
                if (empty($params['lunarYear'])) $params['lunarYear'] = '2000';
                if ($params['lunarYear'] < 1900 || $params['lunarYear'] > 2050) return response()->view('errors.404', [], 404);

                $cacheKey = 'cope_tuoi_hop_nhau_html_' . md5(@json_encode($params)) . '_' . $hasResultParams;
                $ttl = now()->addDay(30);

                if (Cache::has($cacheKey)) {
                    return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                        ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                        ->header('Cache-Control', \dataKey::CACHE);
                }

                $data['cache'] = 1;
                $data['category'] = Helpers::findBySlug($slug);
                if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
                if ($data['category']['level'] == 2) $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

                $config = $request->get('configData');
                $siteName = env('SITE_NAME');
                $SEO = [
                    'name' => $siteName,
                    'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                    'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                    'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                    'fav' => asset('static/web/images/favicon/favicon.ico'),
                    'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                    'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                    'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                    'canonical' => route('page.cate.index', ['slug' => $slug]),
                    'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
                ];

                $data['seo'] = $SEO;
                $data['common'] = $SEO;
                $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
                $data['show'] = 1;
                $data['detail'] = [];
                if (!empty($params['lunarYear'])) {
                    $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_TUOI_HOP_NHAU, \dataApiRoutes::FORTUNE_TUOI_HOP_NHAU, $params, 'post');
                }

                // return view('pages::checkage.tuoihopnhau')->with('data', $data);
                $html = view('pages::checkage.tuoihopnhau')->with('data', $data)->render();
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
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function xemTuoiLamAn($slug, $request)
    {
        try {
            try {
                $params = $request->only('lunarYear');
                $hasResultParams = $request->query->count() > 0;
                if (empty($params['lunarYear'])) $params['lunarYear'] = '2000';
                if ($params['lunarYear'] < 1900 || $params['lunarYear'] > 2050) return response()->view('errors.404', [], 404);

                $cacheKey = 'cope_tuoi_lam_an_html_' . md5(@json_encode($params)) . '_' . $hasResultParams;
                $ttl = now()->addDay(30);

                if (Cache::has($cacheKey)) {
                    return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                        ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                        ->header('Cache-Control', \dataKey::CACHE);
                }

                $data['cache'] = 1;
                $data['category'] = Helpers::findBySlug($slug);
                if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
                if ($data['category']['level'] == 2) $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

                $config = $request->get('configData');
                $siteName = env('SITE_NAME');
                $SEO = [
                    'name' => $siteName,
                    'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                    'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                    'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                    'fav' => asset('static/web/images/favicon/favicon.ico'),
                    'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                    'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                    'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                    'canonical' => route('page.cate.index', ['slug' => $slug]),
                    'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
                ];

                $data['seo'] = $SEO;
                $data['common'] = $SEO;
                $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
                $data['show'] = 1;
                $data['detail'] = [];
                if (!empty($params['lunarYear'])) {
                    $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_TUOI_LAM_AN, \dataApiRoutes::FORTUNE_TUOI_LAM_AN, $params, 'post');
                }

                // return view('pages::checkage.tuoilaman')->with('data', $data);
                $html = view('pages::checkage.tuoilaman')->with('data', $data)->render();
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
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function xemTuoiLamNha($slug, $request)
    {
        try {
            try {
                $params = $request->only('solarDate', 'birthHour', 'gender', 'buildingYear');
                $hasResultParams = $request->query->count() > 0;
                if (empty($params['solarDate'])) $params['solarDate'] = '1995-05-15';
                if (empty($params['birthHour'])) $params['birthHour'] = 'ty';
                if (empty($params['gender'])) $params['gender'] = 'male';
                if (empty($params['buildingYear'])) $params['buildingYear'] = date('Y');
                $params['solarDate'] = Carbon::createFromFormat('Y-m-d', $params['solarDate'])->format('Y-m-d');
                if (!in_array($params['gender'], ['male', 'female'])) return response()->view('errors.404', [], 404);
                if (!in_array($params['birthHour'], array_keys(\dataKey::GIO_SINH))) return response()->view('errors.404', [], 404);
                if ($params['buildingYear'] < 1900 || $params['buildingYear'] > 2050) return response()->view('errors.404', [], 404);

                $cacheKey = 'cope_tuoi_lam_nha_html_' . md5(@json_encode($params)) . '_' . $hasResultParams;
                $ttl = now()->addDay(30);

                if (Cache::has($cacheKey)) {
                    return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                        ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                        ->header('Cache-Control', \dataKey::CACHE);
                }

                $data['cache'] = 1;
                $data['category'] = Helpers::findBySlug($slug);
                if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
                if ($data['category']['level'] == 2) $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

                $config = $request->get('configData');
                $siteName = env('SITE_NAME');
                $SEO = [
                    'name' => $siteName,
                    'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                    'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                    'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                    'fav' => asset('static/web/images/favicon/favicon.ico'),
                    'title_seo' => !empty($data['category']['title_seo']) ? $data['category']['title_seo'] : '',
                    'meta_des' => !empty($data['category']['meta_des']) ? $data['category']['meta_des'] : '',
                    'meta_key' => !empty($data['category']['meta_key']) ? $data['category']['meta_key'] : '',
                    'canonical' => route('page.cate.index', ['slug' => $slug]),
                    'robots' => $hasResultParams ? 'noindex, follow' : 'index, follow'
                ];

                $data['seo'] = $SEO;
                $data['common'] = $SEO;
                $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
                $data['show'] = 1;
                $data['detail'] = [];
                if (!empty($params['solarDate']) && !empty($params['birthHour']) && !empty($params['gender']) && !empty($params['buildingYear'])) {
                    $data['detail'] = RequestApiHelpers::request($request, \dataApiRoutes::FORTUNE_TUOI_LAM_NHA, \dataApiRoutes::FORTUNE_TUOI_LAM_NHA, $params, 'post');
                }

                // return view('pages::checkage.tuoilamnha')->with('data', $data);
                $html = view('pages::checkage.tuoilamnha')->with('data', $data)->render();
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
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }
}
