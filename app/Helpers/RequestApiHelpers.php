<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use JetBrains\PhpStorm\ArrayShape;

class RequestApiHelpers
{
    #[ArrayShape(['Content-Type' => "string", 'Request-From' => "string", 'Authorization' => "string"])]
    public static function setHeaders($request): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Request-From' => 'front-end',
            'api_key' => env('API_KEY2')
        ];

//        if (Session::has(\dataKey::SESSION_LOGIN_TOKEN)) {
//            $headers['Authorization'] = 'Bearer ' . Session::get(\dataKey::SESSION_LOGIN_TOKEN);
//        }

        if (Helpers::checkCookie($request, \dataKey::SESSION_LOGIN_TOKEN)) {
            $headers['Authorization'] = 'Bearer ' . Helpers::getCookie($request, \dataKey::SESSION_LOGIN_TOKEN);
        }

        return $headers;
    }

    public static function setImgHeaders($request): array
    {
        $headers = [
//            'Content-Type' => 'multipart/form-data',
//            'Request-From' => 'front-end',
        ];

//        if (Session::has(\dataKey::SESSION_LOGIN_TOKEN)) {
//            $headers['Authorization'] = 'Bearer ' . Session::get(\dataKey::SESSION_LOGIN_TOKEN);
//        }

        if (Helpers::checkCookie($request, \dataKey::SESSION_LOGIN_TOKEN)) {
            $headers['Authorization'] = 'Bearer ' . Helpers::getCookie($request, \dataKey::SESSION_LOGIN_TOKEN);
        }

        return $headers;
    }

    #[ArrayShape(['status' => "", 'response' => ""])]
    public static function response($status, $response): array
    {
        return [
            'status' => $status,
            'response' => $response
        ];
    }

    #[ArrayShape(['status' => "", 'response' => ""])]
    public static function request($request, $url_root, $URL = '', $data = [], $method = '', $refresh_cache = false): array
    {
        if (\dataApiRoutes::CONFIG != $url_root) {
            if ($request->has('reset') && $request->get('reset') == '1') {
                $refresh_cache = true;
            }
        }

        if (!$refresh_cache) {
            $cacheData = [];
            switch ($url_root) {
                case \dataApiRoutes::FORTUNE_TINH_DUYEN:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_NAM_LAY_CHONG:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_NAM_LAY_VO:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_SINH_CON_HOP_TUOI:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_SINH_CON_THEO_Y_MUON:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_XEM_TUOI_XAY_NHA:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_CAN_XUONG_TINH_SO:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_TINH_TRUNG_TANG:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_BOI_NGAY_SINH:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_THAN_SO_HOC:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_LAS_SO_TU_VI:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_12_CHD_HANG_NGAY:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_XONG_DAT:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_TUOI_VO_CHONG:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_TUOI_SINH_CON:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_TUOI_KET_HON:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_TUOI_HOP_NHAU:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_TUOI_LAM_AN:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_TUOI_LAM_NHA:
                    $cacheData = self::getRedis($URL . '-' . md5(@json_encode($data)));
                    break;
                case \dataApiRoutes::FORTUNE_NGAY_TOT_XAU:
                    $cacheData = self::getRedis($URL . '-' . date('d-m-Y'));
                    break;
                default:
                    break;
            }

            if (!empty($cacheData)) {
                if ($cacheData != 'null') {
                    return @json_decode($cacheData, true);
                } else {
                    return [];
                }
            }
        }
        return self::requestApiData($request, $url_root, $URL, $data, $method);
    }

    #[ArrayShape(['status' => "", 'response' => ""])]
    public static function requestApiData($request, $url_root, $URL = '', $data = [], $method = ''): array
    {
        if ($method == 'attach') {
            $response = Http::withHeaders(self::setImgHeaders($request))->attach($data['field'], file_get_contents($data[$data['field']]), $data[$data['field']]->getClientOriginalName())->post(env('API_URL2') . $URL);
        } else {
            $response = match (strtolower($method)) {
                'get' => Http::withHeaders(self::setHeaders($request))->get(env('API_URL2') . $URL, $data),
                'post' => Http::withHeaders(self::setHeaders($request))->post(env('API_URL2') . $URL, $data),
                'patch' => Http::withHeaders(self::setHeaders($request))->patch(env('API_URL2') . $URL, $data),
                'delete' => Http::withHeaders(self::setHeaders($request))->delete(env('API_URL2') . $URL, $data),
                default => Http::withHeaders(self::setHeaders($request))->put(env('API_URL2') . $URL, $data),
            };
        }

//        dd([
//            'url' => env('API_URL2') . $URL,
//            'headers' => self::setHeaders($request),
//            'data' => $data,
//            'status' => $response->status(),
//            'body' => $response->body(),
//            'json' => $response->json(),
//        ]);

//        if ($response->status() == \dataResponse::STATUS_401) {
//            header("Location: " . route('page.login'));
//            exit();
//        }

//        if ($response->status() == \dataResponse::STATUS_429) {
//            return abort(429);
//        }

        $response = self::response($response->status(), $response->json());
        if ($response['status'] == \dataResponse::STATUS_200 && !empty($response['response'])) {
            self::setDataRedis($request, $url_root, $URL, $response['response'], $data);
            return $response['response'];
        } else {
            if (in_array($url_root, [\dataApiRoutes::LOGIN, \dataApiRoutes::REGISTER])) {
                return $response['response'];
            }
            return [];
        }

    }

    public static function setDataRedis($request, $url, $url_params, $data, $params)
    {
        $REDIS_EXPIRE_30D = env('REDIS_EXPIRE_30D');
        $REDIS_EXPIRE_15D = env('REDIS_EXPIRE_15D');
        $REDIS_EXPIRE_1D = env('REDIS_EXPIRE_1D');
        $REDIS_EXPIRE_3H = env('REDIS_EXPIRE_3H');
        $REDIS_EXPIRE_2H = env('REDIS_EXPIRE_2H');
        $REDIS_EXPIRE_1H = env('REDIS_EXPIRE_1H');
        $REDIS_EXPIRE_30M = env('REDIS_EXPIRE_30M');
        $REDIS_EXPIRE_20M = env('REDIS_EXPIRE_20M');
        $REDIS_EXPIRE_10M = env('REDIS_EXPIRE_10M');
        $REDIS_EXPIRE_5M = env('REDIS_EXPIRE_5M');
        $REDIS_EXPIRE_1M = env('REDIS_EXPIRE_1M');
        $REDIS_EXPIRE_10S = env('REDIS_EXPIRE_10S');
        $REDIS_EXPIRE_50S = env('REDIS_EXPIRE_50S');
        switch ($url) {
            case \dataApiRoutes::FORTUNE_TINH_DUYEN:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_NAM_LAY_CHONG:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_NAM_LAY_VO:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_SINH_CON_HOP_TUOI:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_SINH_CON_THEO_Y_MUON:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_XEM_TUOI_XAY_NHA:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_CAN_XUONG_TINH_SO:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_TINH_TRUNG_TANG:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_BOI_NGAY_SINH:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_THAN_SO_HOC:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_LAS_SO_TU_VI:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_1D);
                break;
            case \dataApiRoutes::FORTUNE_12_CHD_HANG_NGAY:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_15D);
                break;
            case \dataApiRoutes::FORTUNE_XONG_DAT:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_TUOI_VO_CHONG:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_TUOI_SINH_CON:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_TUOI_KET_HON:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_TUOI_HOP_NHAU:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_TUOI_LAM_AN:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_TUOI_LAM_NHA:
                self::setRedis($url_params. '-' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::FORTUNE_NGAY_TOT_XAU:
                self::setRedis($url_params. '-' . date('d-m-Y'), $data, $REDIS_EXPIRE_1D);
                break;
            default:
                break;
        }
    }

    public static function setRedis($key, $data, $expire)
    {
        try {
            $key = self::renderKey($key);
            Redis::set($key, @json_encode($data), 'EX', $expire);
        } catch (\Exception $ex) {
            Helpers::logError(!json_encode(['key' => $key, "message" => $ex->getMessage()]));
            return [];
        }
    }

    public static function getRedis($key)
    {
        try {
            $key = self::renderKey($key);
            return Redis::get($key);
        } catch (\Exception $ex) {
            Helpers::logError(!json_encode(['key' => $key, "message" => $ex->getMessage()]));
            return [];
        }
    }

    public static function renderKey($key)
    {
        $key = str_replace('/', '_', str_replace('-', '_', $key));
        return $key;
    }

}
