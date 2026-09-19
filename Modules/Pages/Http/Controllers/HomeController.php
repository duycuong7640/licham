<?php

namespace Modules\Pages\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\RequestApiHelpers;
use App\Helpers\RequestHelpers;
use App\Helpers\ZodiacHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return object
     */
    public function index(Request $request): object
    {
        try {
            $now = Carbon::now('Asia/Ho_Chi_Minh');
            $day = $now->format('d-m-Y');
            $month = (int)$now->format('m');
            $year = (int)$now->format('Y');
            $config = $request->get('configData', []);
            $setting = $config['setting'] ?? [];
            $siteName = !empty($setting['title']) ? $setting['title'] : env('SITE_NAME');
            $SEO = [
                'name' => $siteName,
                'logo' => !empty($setting['thumbnail']) ? Helpers::renderThumb($setting['thumbnail']) : '',
                'logo_share' => !empty($setting['thumbnailShare']) ? Helpers::renderThumb($setting['thumbnailShare']) : '',
                'fav' => asset('static/favicon/favicon.ico'),
                'title_seo' => !empty($setting['titleSeo']) ? $setting['titleSeo'] : '',
                'meta_des' => !empty($setting['metaDes']) ? $setting['metaDes'] : '',
                'meta_key' => !empty($setting['metaKey']) ? $setting['metaKey'] : '',
                'canonical' => route('page.home'),
            ];

            $data['seo'] = $SEO;
            $data['common'] = Helpers::metaHead($SEO);
            $data['shareMXH'] = Helpers::renderShareMXH('home', $SEO, $config);
            $data['page'] = 'home';
            $data['month'] = $month;
            $data['year'] = $year;
            $data['mData'] = Helpers::getMonthDates($year, $month);
            $data['months'] = RequestHelpers::request($request, \dataApiRoutes::COPE_MONTH, str_replace(':month', $month, str_replace(':year', $year, \dataApiRoutes::COPE_MONTH)), [], 'get');
            $data['day'] = RequestHelpers::request($request, \dataApiRoutes::COPE_DETAIL, str_replace(':day', $day, \dataApiRoutes::COPE_DETAIL), [], 'get');

            return view('pages::index')->with('data', $data);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }
}
