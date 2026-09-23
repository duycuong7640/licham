<?php

namespace Modules\Pages\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;
use function Symfony\Component\String\u;

class CopesController extends Controller
{
    public function copeYear($year, Request $request)
    {
        try {
            if (empty($year) || $year < 1900 || $year > 2050) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_year_html_' . $year;
//            $ttl = now()->addMinutes(10);
//            if (Cache::has($cacheKey)) {
//                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
//                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                    ->header('Cache-Control', \dataKey::CACHE);
//            }

            $ttl = now()->addMinutes(10);
            $htmlCache = Cache::store('html');
            if ($request->has('reset')) {
                $htmlCache->forget($cacheKey);
            } else {
                $cachedHtml = $htmlCache->get($cacheKey);
                if ($cachedHtml !== null) {
                    return response(Helpers::genCsrfToken($cachedHtml, ''), 200)->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)->header('Cache-Control', \dataKey::CACHE);
                }
            }

            $data['year'] = $year;
            $lists = RequestHelpers::request($request, \dataApiRoutes::COPE_YEAR, str_replace(':year', $year, \dataApiRoutes::COPE_YEAR), [], 'get');
            if (!count($lists['lists'])) {
                return response()->view('errors.404', [], 404);
            }

            $data['calendar'] = Helpers::generateYearCalendar($year, $lists['lists']);
            $data['yearData'] = !empty($lists['year']) ? $lists['year'] : [];

            // range index google
            $yearNumber = (int)$year;
            $yearName = '';
            foreach ($data['yearData']['options'] ?? [] as $option) {
                if (($option['key'] ?? '') !== 'YEAR_NAME') continue;
                $yearNameText = trim(strip_tags($option['value'] ?? ''));
                if (preg_match('/là năm\s+([^,]+)/u', $yearNameText, $matches)) {
                    $yearName = trim($matches[1]);
                }

                break;
            }
            $currentYear = (int)now('Asia/Ho_Chi_Minh')->year;
            $isIndexable = $yearNumber >= $currentYear - 1 && $yearNumber <= $currentYear + 2;
            $yearLabel = $yearName ? $yearNumber . ' (' . $yearName . ')' : (string)$yearNumber;

            // seo
            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => $year,
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Lịch vạn niên ' . $yearLabel . ' – Âm dương 12 tháng',
                'meta_des' => $yearNumber . ($yearName ? ' là năm ' . $yearName . '.' : '.') . ' Lịch 12 tháng hiển thị ngày âm, ngày dương,' . ' ngày Hoàng đạo, Hắc đạo' . ' cùng các ngày lễ trong năm.',
                'canonical' => $request->url(),
                'robots' => $isIndexable ? 'index, follow' : 'noindex, follow',
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['yearName'] = $yearName;
            $data['isPage'] = 'year';
            $data['hyperlinks'] = ['Tổng quan', 'Lịch âm năm khác', 'Ngày lễ & kỷ niệm'];

            // view
            // return view('pages::copes.year')->with('data', $data);
            $html = view('pages::copes.year')->with('data', $data)->render();

            $html = Helpers::genCsrfToken($html, '1');
            $response = response($html)->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE);
            $response = Helpers::optimize_html($response);
            $html = $response->getContent();
            $htmlCache->put($cacheKey, $html, $ttl);

            return $response->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)->header('Cache-Control', \dataKey::CACHE);

//            $html = Helpers::genCsrfToken($html, '1');
//            $response = response($html)
//                ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE);
//            $response = Helpers::optimize_html($response);
//            Cache::put($cacheKey, $response->getContent(), $ttl);
//
//            return $response->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                ->header('Cache-Control', \dataKey::CACHE);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function copeMonth($m, $y, Request $request)
    {
        try {
            $month = Helpers::getMonthDates($y, $m);
            if (empty($month['year'])) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_month_html_' . $m . '_' . $y;
//            $ttl = now()->addMinutes(10);
//            if (Cache::has($cacheKey)) {
//                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
//                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                    ->header('Cache-Control', \dataKey::CACHE);
//            }
            $ttl = now()->addMinutes(10);
            $htmlCache = Cache::store('html');
            if ($request->has('reset')) {
                $htmlCache->forget($cacheKey);
            } else {
                $cachedHtml = $htmlCache->get($cacheKey);
                if ($cachedHtml !== null) {
                    return response(Helpers::genCsrfToken($cachedHtml, ''), 200)->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)->header('Cache-Control', \dataKey::CACHE);
                }
            }

            $now = Carbon::now('Asia/Ho_Chi_Minh');
            $day = $now->format('d-m-Y');
            $data['mData'] = $month;
            $data['lists'] = RequestHelpers::request($request, \dataApiRoutes::COPE_MONTH, str_replace(':month', $month['month'], str_replace(':year', $month['year'], \dataApiRoutes::COPE_MONTH)), [], 'get');
            if (!count($data['lists'])) {
                return response()->view('errors.404', [], 404);
            }
            $data['historical_events'] = RequestHelpers::request($request, \dataApiRoutes::COPE_HISTORICAL_EVENT, \dataApiRoutes::COPE_HISTORICAL_EVENT, ['month' => $month['month']], 'get');
            $data['day'] = RequestHelpers::request($request, \dataApiRoutes::COPE_DETAIL, str_replace(':day', $day, \dataApiRoutes::COPE_DETAIL), [], 'get');

            // range index google
            $totalDays = count($data['lists']);
            $goodDayCount = count(array_filter($data['lists'], static fn(array $row): bool => !empty($row['isDay'])));
            $badDayCount = $totalDays - $goodDayCount;
            $monthNumber = (int)$month['month'];
            $yearNumber = (int)$month['year'];
            $monthDate = Carbon::create($yearNumber, $monthNumber, 1, 0, 0, 0, 'Asia/Ho_Chi_Minh')->startOfDay();
            $now = now('Asia/Ho_Chi_Minh');
            $indexFrom = $now->copy()->subYear()->startOfYear();
            $indexTo = $now->copy()->addYears(2)->endOfYear();
            $isIndexable = $monthDate->betweenIncluded($indexFrom, $indexTo);

            // seo
            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => $month,
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Tháng ' . $monthNumber . '/' . $yearNumber . ' có những ngày nào tốt?',
                'meta_des' => 'Tháng ' . $monthNumber . '/' . $yearNumber . ' có ' . $goodDayCount . ' ngày Hoàng đạo' . ' và ' . $badDayCount . ' ngày Hắc đạo.' . ' Mỗi ngày đều ghi rõ ngày âm, giờ đẹp,' . ' việc nên làm và điều cần tránh.',
                'meta_key' => '',
                'canonical' => $request->url(),
                'robots' => $isIndexable ? 'index, follow' : 'noindex, follow',
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['goodDayCount'] = $goodDayCount;
            $data['badDayCount'] = $badDayCount;
            $data['isPage'] = 'month';
            $data['hyperlinks'] = ['Lịch tháng', 'Ngày hoàng đạo', 'Ngày xuất hành', 'Sự kiện'];

            // view
            // return view('pages::copes.month')->with('data', $data);
            $html = view('pages::copes.month')->with('data', $data)->render();
            $html = Helpers::genCsrfToken($html, '1');
            $response = response($html)->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE);
            $response = Helpers::optimize_html($response);
            $html = $response->getContent();
            $htmlCache->put($cacheKey, $html, $ttl);

            return $response->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)->header('Cache-Control', \dataKey::CACHE);

//            $html = Helpers::genCsrfToken($html, '1');
//            $response = response($html)
//                ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE);
//            $response = Helpers::optimize_html($response);
//            Cache::put($cacheKey, $response->getContent(), $ttl);
//
//            return $response->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                ->header('Cache-Control', \dataKey::CACHE);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

    public function copeDay($d, $m, $y, Request $request)
    {
        try {
            if (empty((int)$d) && empty((int)$m) && empty((int)$y)) return response()->view('errors.404', [], 404);
            $day = $d . '-' . $m . '-' . $y;

            $cacheKey = 'cope_day_html_' . $day;
//            $ttl = now()->addMinutes(10);
//            if (Cache::has($cacheKey)) {
//                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
//                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                    ->header('Cache-Control', \dataKey::CACHE);
//            }
            $ttl = now()->addMinutes(10);
            $htmlCache = Cache::store('html');
            if ($request->has('reset')) {
                $htmlCache->forget($cacheKey);
            } else {
                $cachedHtml = $htmlCache->get($cacheKey);
                if ($cachedHtml !== null) {
                    return response(Helpers::genCsrfToken($cachedHtml, ''), 200)->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)->header('Cache-Control', \dataKey::CACHE);
                }
            }

            $data['day'] = RequestHelpers::request($request, \dataApiRoutes::COPE_DETAIL, str_replace(':day', $day, \dataApiRoutes::COPE_DETAIL), [], 'get');
            if (empty($data['day']['id'])) {
                return response()->view('errors.404', [], 404);
            }

            $data['mData'] = Helpers::getMonthDates($y, $m);
            $data['months'] = RequestHelpers::request($request, \dataApiRoutes::COPE_MONTH, str_replace(':month', $m, str_replace(':year', $y, \dataApiRoutes::COPE_MONTH)), [], 'get');
            $data['month'] = $m;
            $data['year'] = $y;

            // range index google
            $pageDate = Carbon::createFromFormat('d-m-Y', $day, 'Asia/Ho_Chi_Minh')->startOfDay();
            $displayDate = $pageDate->format('d/m/Y');
            $now = now('Asia/Ho_Chi_Minh');
            $indexFrom = $now->subYear()->startOfYear();
            $indexTo = $now->addYears(2)->endOfYear();
            $isIndexable = $pageDate->betweenIncluded($indexFrom, $indexTo);
            $lunarTimestamp = strtotime($data['day']['lunarDay']);
            $lunarDate = date('d/m/Y', $lunarTimestamp);
            $dayType = !empty($data['day']['isDay']) ? 'Hoàng đạo' : 'Hắc đạo';

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['day']['slug']) ? $data['day']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Ngày ' . $displayDate . ' (' . $data['day']['strDay'] . ') tốt hay xấu?',
                'meta_des' => 'Ngày ' . $displayDate . ' là ngày ' . $data['day']['strDay'] . ', ' . $lunarDate . ' âm lịch, thuộc ' . $dayType . '. Xem ngày này hợp làm việc gì, ' . 'nên tránh gì và giờ nào đẹp.',
                'meta_key' => '',
                'canonical' => $request->url(),
                'robots' => $isIndexable ? 'index, follow' : 'noindex, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;
            $data['isPage'] = 'day';
            $data['hyperlinks'] = ['Lịch âm hôm nay', 'Giờ hoàng đạo', 'Xuất hành', 'Kiến thức'];

            // return view('pages::copes.day')->with('data', $data);
            $html = view('pages::copes.day')->with('data', $data)->render();
            $html = Helpers::genCsrfToken($html, '1');
            $response = response($html)->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE);
            $response = Helpers::optimize_html($response);
            $html = $response->getContent();
            $htmlCache->put($cacheKey, $html, $ttl);

            return $response->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)->header('Cache-Control', \dataKey::CACHE);

//            $html = Helpers::genCsrfToken($html, '1');
//            $response = response($html)->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE);
//            $response = Helpers::optimize_html($response);
//            Cache::put($cacheKey, $response->getContent(), $ttl);
//            return $response->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                ->header('Cache-Control', \dataKey::CACHE);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }
}
