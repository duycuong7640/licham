<?php

namespace Modules\Pages\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;

class AjaxController extends Controller
{
    public function calendarMonth(Request $request)
    {
        $params = $request->validate([
            'month' => [
                'required',
                'integer',
                'between:1,12',
            ],
            'year' => [
                'required',
                'integer',
                'between:1900,2050',
            ],
        ]);

        $month = (int) $params['month'];
        $year = (int) $params['year'];
        $calendarMonth = RequestHelpers::request($request, \dataApiRoutes::COPE_CALENDAR_MONTH, str_replace(':month', $month, str_replace(':year', $year, \dataApiRoutes::COPE_CALENDAR_MONTH)), [], 'get');

        return response()->json([
            'code' => 200,
            'month' => $month,
            'year' => $year,
            'data' => $calendarMonth,
        ]);
    }
}
