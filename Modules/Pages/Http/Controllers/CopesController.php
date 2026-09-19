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
    public function show($path, Request $request)
    {
        if (Helpers::isDateFormatDMY($path) || $path == 'tot-xau') {
            return $this->copeDay($path, $request);
        } else if ((!empty(Helpers::parseMonthSlug($path)['month']) && strpos($request->segments()[0], 'thang-' . $path))) {
            return $this->copeMonth($path, $request);
        } else if (is_numeric($path) || $path == 'tot-trong-nam') {
            return $this->copeYear($path, $request);
        } else if (Helpers::parseDayMonthYear($path) || $path == 'duong') {
            return $this->copeLunar($path, $request);
        }
    }

    public function copeLunar($path, $request)
    {
        try {
            $day = '';
            if ($path == 'duong') {
                $slug = 'lich-am-duong';
                $find = $slug;
                $day = now()->format('d-m-Y');

                return redirect()->route(
                    'page.cope.show.lunar', ['slug' => 'duong-ngay-' . $day],
                    302
                );
            }

            if (Helpers::parseDayMonthYear($path)) {
                $slug = 'lich-am-' . $path;
                $find = 'lich-am-duong';
                $parse = Helpers::parseDayMonthYear($path);
                $day = $parse['day'] . '-' . $parse['month'] . '-' . $parse['year'];
            }

            if (empty($day)) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_lunar_html_' . md5($slug . '-' . $day);
            $ttl = now()->addMinutes(15);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($find);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
//            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $data['detail'] = RequestHelpers::request($request, \dataApiRoutes::COPE_DETAIL, str_replace(':day', $day, \dataApiRoutes::COPE_DETAIL), [], 'get');
            if (empty($data['detail']['id'])) {
                return response()->view('errors.404', [], 404);
            }

            // range index google
            $pageDate = Carbon::createFromFormat('d-m-Y', $day, 'Asia/Ho_Chi_Minh')->startOfDay();
            $currentDate = now('Asia/Ho_Chi_Minh');
            $lunarTimestamp = strtotime($data['detail']['lunarDay']);
            $lunarDate = date('d/m', $lunarTimestamp);
            $dayType = !empty($data['detail']['isDay']) ? 'Hoàng đạo' : 'Hắc đạo';
            $goodHourNames = [];
            $indexFrom = $currentDate->copy()->subYear()->startOfYear();
            $indexTo = $currentDate->copy()->addYears(2)->endOfYear();
            $isIndexable = $pageDate->betweenIncluded($indexFrom, $indexTo);
            $displayDate = $pageDate->format('d/m/Y');
            foreach ($data['detail']['options']['AUSPICIOUS_HOUR'] ?? [] as $item) {
                $hour = Helpers::matchHour($item['value']);
                if (!empty($hour['title'])) {
                    $goodHourNames[] = trim($hour['title']);
                }
            }
            $goodHourNames = array_values(array_unique($goodHourNames));
            if (count($goodHourNames) > 1) {
                $lastGoodHour = array_pop($goodHourNames);
                $goodHourText = implode(', ', $goodHourNames) . ' và ' . $lastGoodHour;
            } else {
                $goodHourText = $goodHourNames[0] ?? '';
            }

            $titleSeo = 'Lịch âm ngày ' . $displayDate . ' - Ngày ' . $lunarDate . ' năm ' . $data['detail']['strYear'];
            $metaDescription = 'Ngày ' . $displayDate . ' dương lịch nhằm ' . $lunarDate . ' năm ' . $data['detail']['strYear'] . ', ngày ' . $data['detail']['strDay'] . ', ' . $dayType . '.';
            if ($goodHourText !== '') {
                $metaDescription .= ' Giờ hoàng đạo: ' . $goodHourText . '.';
            }

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => $titleSeo,
                'meta_des' => $metaDescription,
                'meta_key' => '',
                'canonical' => $request->url(),
                'robots' => $isIndexable ? 'index, follow' : 'noindex, follow',
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;

            // return view('pages::copes.lunar')->with('data', $data);
            $html = view('pages::copes.lunar')->with('data', $data)->render();
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

    public function copeYear($year, Request $request)
    {
        try {
            if (empty($year) || $year < 1900 || $year > 2050) return response()->view('errors.404', [], 404);

//            $cacheKey = 'cope_year_html_' . md5($slug . '-' . $year);
//            $ttl = now()->addMinutes(15);
//            if (Cache::has($cacheKey)) {
//                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
//                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                    ->header('Cache-Control', \dataKey::CACHE);
//            }
//
//            $data['cache'] = 1;
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

            // view
            return view('pages::copes.year')->with('data', $data);
//            $html = view('pages::copes.year')->with('data', $data)->render();
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

//            $cacheKey = 'cope_month_html_' . md5($slug . '-' . $month['month'] . '-' . $month['year']);
//            $ttl = now()->addMinutes(15);
//            if (Cache::has($cacheKey)) {
//                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
//                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
//                    ->header('Cache-Control', \dataKey::CACHE);
//            }

//            $data['cache'] = 1;
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

            // view
            return view('pages::copes.month')->with('data', $data);
//            $html = view('pages::copes.month')->with('data', $data)->render();
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

    public function copeWeek($path, $request)
    {
        try {
            $week = [];
            if ($path == 'tot-trong-tuan') {
                $slug = 'xem-ngay-' . $path;
                $find = $slug;
                $week = Helpers::getWeekDates();

                return redirect()->route(
                    'page.cope.show.week',
                    ['week' => $week['week'] . '-nam-' . $week['year']],
                    302
                );
            }

            if (!count($week)) {
                $parseWeek = Helpers::parseWeekSlug($path);
                if (!empty($parseWeek['week'])) {
                    $slug = 'xem-ngay-tot-xau-tuan-' . $path;
                    $find = 'xem-ngay-tot-trong-tuan';
                    $week = Helpers::getWeekDates($parseWeek['year'], $parseWeek['week']);
                }
            }

            if (empty($week)) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_week_html_' . md5($slug . '-' . $week['dates'][0] . '-' . $week['dates'][6]);
            $ttl = now()->addMinutes(15);
            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($find);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
//            $data['categoryParent'] = Helpers::findByParentKey($data['category']['parent']);
            $data['wData'] = $week;

            $data['lists'] = RequestHelpers::request($request, \dataApiRoutes::COPE_WEEK, str_replace(':from', $week['dates'][0], str_replace(':to', $week['dates'][6], \dataApiRoutes::COPE_WEEK)), [], 'get');
            if (!count($data['lists'])) {
                return response()->view('errors.404', [], 404);
            }

            // range index google
            $weekStart = Carbon::createFromFormat('d-m-Y', $week['dates'][0], 'Asia/Ho_Chi_Minh')->startOfDay();
            $weekEnd = Carbon::createFromFormat('d-m-Y', $week['dates'][count($week['dates']) - 1], 'Asia/Ho_Chi_Minh')->endOfDay();
            if ($weekStart->month === $weekEnd->month && $weekStart->year === $weekEnd->year) {
                $dateRange = $weekStart->format('d') . '–' . $weekEnd->format('d/m/Y');
            } elseif ($weekStart->year === $weekEnd->year) {
                $dateRange = $weekStart->format('d/m') . '–' . $weekEnd->format('d/m/Y');
            } else {
                $dateRange = $weekStart->format('d/m/Y') . '–' . $weekEnd->format('d/m/Y');
            }
            $totalDays = count($data['lists']);
            $goodDayCount = count(array_filter($data['lists'], static fn(array $row): bool => !empty($row['isDay'])));
            $badDayCount = $totalDays - $goodDayCount;
            $now = now('Asia/Ho_Chi_Minh');
            $indexFrom = $now->copy()->subYear()->startOfYear();
            $indexTo = $now->copy()->addYears(2)->endOfYear();
            $isIndexable = $weekEnd->gte($indexFrom) && $weekStart->lte($indexTo);

            // seo
            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => $slug,
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Tuần ' . $week['week'] . ' (' . $dateRange . ') có ngày nào tốt?',
                'meta_des' => 'Tuần ' . $week['week'] . ', từ ' . $weekStart->format('d/m') . ' đến ' . $weekEnd->format('d/m/Y') . ' có ' . $goodDayCount . ' ngày Hoàng đạo' . ' và ' . $badDayCount . ' ngày Hắc đạo.' . ' Xem từng ngày để chọn giờ đẹp,' . ' biết việc nên làm và ngày cần tránh.',
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

            // view
            // return view('pages::copes.week')->with('data', $data);
            $html = view('pages::copes.week')->with('data', $data)->render();
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

    public function copeDay($path, $request)
    {
        try {
            $day = '';
            if ($path == 'tot-xau') {
                $find = 'xem-ngay-' . $path;
                $day = now('Asia/Ho_Chi_Minh')->format('d-m-Y');

                return redirect()->route(
                    'page.cope.show.day',
                    ['day' => $day],
                    302
                );
            }

            if (Helpers::isDateFormatDMY($path)) {
                $find = 'xem-ngay-tot-xau';
                $day = $path;
            }

            $slug = 'xem-ngay-tot-xau';
            if (empty($day)) return response()->view('errors.404', [], 404);

            $cacheKey = 'cope_day_html_' . md5($path . '-' . $day);
            $ttl = now()->addMinutes(15);

            if (Cache::has($cacheKey)) {
                return response(Helpers::genCsrfToken(Cache::get($cacheKey), ''), 200)
                    ->header('Content-Type', \dataKey::CACHE_CONTENT_TYPE)
                    ->header('Cache-Control', \dataKey::CACHE);
            }

            $data['cache'] = 1;
            $data['category'] = Helpers::findBySlug($find);
            if (empty($data['category']['title'])) return response()->view('errors.404', [], 404);
//            $data['categoryParent'] = Helpers::findByParentKey(!empty($data['category']['parent']) ? $data['category']['parent'] : $data['category']['type']);

            $data['detail'] = RequestHelpers::request($request, \dataApiRoutes::COPE_DETAIL, str_replace(':day', $day, \dataApiRoutes::COPE_DETAIL), [], 'get');
            if (empty($data['detail']['id'])) {
                return response()->view('errors.404', [], 404);
            }

            // range index google
            $pageDate = Carbon::createFromFormat('d-m-Y', $day, 'Asia/Ho_Chi_Minh')->startOfDay();
            $displayDate = $pageDate->format('d/m/Y');
            $now = now('Asia/Ho_Chi_Minh');
            $indexFrom = $now->subYear()->startOfYear();
            $indexTo = $now->addYears(2)->endOfYear();
            $isIndexable = $pageDate->betweenIncluded($indexFrom, $indexTo);
            $lunarTimestamp = strtotime($data['detail']['lunarDay']);
            $lunarDate = date('d/m/Y', $lunarTimestamp);
            $dayType = !empty($data['detail']['isDay']) ? 'Hoàng đạo' : 'Hắc đạo';

            $config = $request->get('configData');
            $siteName = env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'slug' => !empty($data['detail']['slug']) ? $data['detail']['slug'] : '',
                'logo' => !empty($config['setting']['thumbnail']) ? Helpers::renderThumb($config['setting']['thumbnail']) : '',
                'logo_share' => !empty($config['setting']['thumbnailShare']) ? Helpers::renderThumb($config['setting']['thumbnailShare']) : '',
                'fav' => asset('static/web/images/favicon/favicon.ico'),
                'title_seo' => 'Ngày ' . $displayDate . ' (' . $data['detail']['strDay'] . ') tốt hay xấu?',
                'meta_des' => 'Ngày ' . $displayDate . ' là ngày ' . $data['detail']['strDay'] . ', ' . $lunarDate . ' âm lịch, thuộc ' . $dayType . '. Xem ngày này hợp làm việc gì, ' . 'nên tránh gì và giờ nào đẹp.',
                'meta_key' => '',
                'canonical' => $request->url(),
                'robots' => $isIndexable ? 'index, follow' : 'noindex, follow'
            ];

            $data['seo'] = $SEO;
            $data['common'] = $SEO;
            $data['shareMXH'] = Helpers::renderShareMXH('pro_show', $SEO);
            $data['show'] = 1;

            // return view('pages::copes.day')->with('data', $data);
            $html = view('pages::copes.day')->with('data', $data)->render();

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
}
