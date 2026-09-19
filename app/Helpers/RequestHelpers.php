<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use JetBrains\PhpStorm\ArrayShape;

class RequestHelpers
{
    #[ArrayShape(['Content-Type' => "string", 'Request-From' => "string", 'Authorization' => "string"])]
    public static function setHeaders($request): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Request-From' => 'front-end',
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
//                case \dataApiRoutes::CONFIG:
//                    $cacheData = self::getRedis(\dataApiRoutes::CONFIG);
//                    break;
                case \dataApiRoutes::POSTS:
                    if ($data['isPage'] == 'cate') {
                        $cacheData = self::getRedis(\dataApiRoutes::POSTS . '_' . $data['isPage'] . '_' . $data['keySlug'] . '_' . md5(@json_encode($data)) . '_page_' . $data['page']);
                    } else if ($data['isPage'] == 'sitemap') {
                        $cacheData = self::getRedis(\dataApiRoutes::POSTS . '_' . $data['isPage'] . '_' . $data['keySlug'] . '_' . md5($data['type']) . '_page_' . $data['page']);
                    } else if ($data['isPage'] == 'lists') {
                        $cacheData = self::getRedis(\dataApiRoutes::POSTS . '_' . $data['isPage'] . '_' . $data['keySlug'] . '_limit_' . $data['limit'] . '_key_' . md5(@json_encode($data)));
                    } else if ($data['isPage'] == 'tag') {
                        $cacheData = self::getRedis(\dataApiRoutes::POSTS . '_' . $data['isPage'] . '_page_' . $data['page'] . '_type_' . md5($data['type']));
                    } else {
                        $cacheData = self::getRedis(\dataApiRoutes::POSTS . '_' . $data['isPage'] . '_' . md5(@json_encode($data)) . '_page_' . $data['page']);
                    }
                    break;
                case \dataApiRoutes::POST_BY_TAGS:
                    if ($data['isPage'] == 'cate') {
                        $cacheData = self::getRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $data['isPage'] . '_' . $data['keySlug'] . '_' . md5(@json_encode($data)) . '_page_' . $data['page']);
                    } else if ($data['isPage'] == 'sitemap') {
                        $cacheData = self::getRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $data['isPage'] . '_' . $data['keySlug'] . '_' . md5(@json_encode($data)) . '_page_' . $data['page']);
                    } else if ($data['isPage'] == 'lists') {
                        $cacheData = self::getRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $data['isPage'] . '_' . $data['keySlug'] . '_limit_' . $data['limit'] . '_key_' . $data['key']);
                    } else if ($data['isPage'] == 'tag') {
                        $cacheData = self::getRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $data['isPage'] . '_page_' . $data['page'] . '_type_' . md5($data['type']));
                    } else {
                        $cacheData = self::getRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $data['isPage'] . '_page_' . $data['page']);
                    }
                    break;
                case \dataApiRoutes::HASHTAGS:
                    if ($data['isPage'] == 'lists') {
                        $cacheData = self::getRedis(\dataApiRoutes::POSTS . '_' . $data['isPage'] . '_' . $data['keySlug'] . '_' . md5(@json_encode($data)));
                    }
                    break;
                case \dataApiRoutes::POSTS_SEARCH:
                    $cacheData = self::getRedis(\dataApiRoutes::POSTS_SEARCH . '_' . md5($data['title']));
                    break;
                case \dataApiRoutes::POST_DETAIL:
                    $cacheData = self::getRedis($URL);
                    break;
                case \dataApiRoutes::COPE_DETAIL:
                    $cacheData = self::getRedis($URL);
                    break;
                case \dataApiRoutes::COPE_WEEK:
                    $cacheData = self::getRedis($URL);
                    break;
                case \dataApiRoutes::COPE_MONTH:
                    $cacheData = self::getRedis($URL);
                    break;
                case \dataApiRoutes::COPE_YEAR:
                    $cacheData = self::getRedis($URL);
                    break;
                case \dataApiRoutes::COPE_CALENDAR_MONTH:
                    $cacheData = self::getRedis($URL);
                    break;
                case \dataApiRoutes::COPE_HISTORICAL_EVENT:
                    $cacheData = self::getRedis($URL);
                    break;
                case \dataApiRoutes::CONFIG:
                    $cacheData = self::getRedis(\dataApiRoutes::CONFIG . '_domain_' . Helpers::renderSlug($data['domain']));
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
            $response = Http::withHeaders(self::setImgHeaders($request))->attach($data['field'], file_get_contents($data[$data['field']]), $data[$data['field']]->getClientOriginalName())->post(env('API_URL') . $URL);
        } else {
            $response = match (strtolower($method)) {
                'get' => Http::withHeaders(self::setHeaders($request))->get(env('API_URL') . $URL, $data),
                'post' => Http::withHeaders(self::setHeaders($request))->post(env('API_URL') . $URL, $data),
                'patch' => Http::withHeaders(self::setHeaders($request))->patch(env('API_URL') . $URL, $data),
                'delete' => Http::withHeaders(self::setHeaders($request))->delete(env('API_URL') . $URL, $data),
                default => Http::withHeaders(self::setHeaders($request))->put(env('API_URL') . $URL, $data),
            };
        }

//        if($url_root == 'copes/historical-events'){
//            echo $response->status();
//            dd([
//                'url' => env('API_URL') . $URL,
//                'headers' => self::setHeaders($request),
//                'data' => $data,
//                'status' => $response->status(),
//                'body' => $response->body(),
//                'json' => $response->json(),
//            ]);
//        }

//        if ($response->status() == \dataResponse::STATUS_401) {
//            header("Location: " . route('page.login'));
//            exit();
//        }

//        if ($response->status() == \dataResponse::STATUS_429) {
//            return abort(429);
//        }

        $response = self::response($response->status(), $response->json());
//        if ($response['status'] == \dataResponse::STATUS_200 && !empty($response['response'])) {
//            self::setDataRedis($request, $url_root, $URL, $response['response'], $data);
//            return $response['response'];
//        }
        if ($response['status'] == \dataResponse::STATUS_200) {
            $responseData = $response['response'] ?? [];

            self::setDataRedis(
                $request,
                $url_root,
                $URL,
                $responseData,
                $data
            );

            return $responseData;
        } else {
            if (in_array($url_root, [\dataApiRoutes::LOGIN, \dataApiRoutes::REGISTER])) {
                return $response['response'];
            }
            return [];
        }

    }

    public static function setDataRedis($request, $url, $url_params, $data, $params)
    {
        $REDIS_EXPIRE_60D = env('REDIS_EXPIRE_60D');
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
            case \dataApiRoutes::POSTS:
                if ($params['isPage'] == 'cate') {
                    self::setRedis(\dataApiRoutes::POSTS . '_' . $params['isPage'] . '_' . $params['keySlug'] . '_' . md5(@json_encode($params)) . '_page_' . $params['page'], $data, $REDIS_EXPIRE_5M);
                } else if ($params['isPage'] == 'sitemap') {
                    self::setRedis(\dataApiRoutes::POSTS . '_' . $params['isPage'] . '_' . $params['keySlug'] . '_' . md5($params['type']) . '_page_' . $params['page'], $data, $REDIS_EXPIRE_50S);
                } else if ($params['isPage'] == 'lists') {
                    self::setRedis(\dataApiRoutes::POSTS . '_' . $params['isPage'] . '_' . $params['keySlug'] . '_limit_' . $params['limit'] . '_key_' . md5(@json_encode($params)), $data, !empty($params['cache_time']) ? $params['cache_time'] : $REDIS_EXPIRE_5M);
                } else if ($params['isPage'] == 'tag') {
                    self::setRedis(\dataApiRoutes::POSTS . '_' . $params['isPage'] . '_page_' . $params['page'] . '_type_' . md5($params['type']), $data, $REDIS_EXPIRE_5M);
                } else {
                    self::setRedis(\dataApiRoutes::POSTS . '_' . $params['isPage'] . '_' . md5(@json_encode($params)) . '_page_' . $params['page'], $data, $REDIS_EXPIRE_5M);
                }
                break;
            case \dataApiRoutes::POST_BY_TAGS:
                if ($params['isPage'] == 'cate') {
                    self::setRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $params['isPage'] . '_' . $params['keySlug'] . '_' . md5(@json_encode($params)) . '_page_' . $params['page'], $data, $REDIS_EXPIRE_5M);
                } else if ($params['isPage'] == 'sitemap') {
                    self::setRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $params['isPage'] . '_' . $params['keySlug'] . '_' . md5(@json_encode($params)) . '_page_' . $params['page'], $data, $REDIS_EXPIRE_50S);
                } else if ($params['isPage'] == 'lists') {
                    self::setRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $params['isPage'] . '_' . $params['keySlug'] . '_limit_' . $params['limit'] . '_key_' . $params['key'], $data, $REDIS_EXPIRE_5M);
                } else if ($params['isPage'] == 'tag') {
                    self::setRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $params['isPage'] . '_page_' . $params['page'] . '_type_' . md5($params['type']), $data, $REDIS_EXPIRE_5M);
                } else {
                    self::setRedis(\dataApiRoutes::POST_BY_TAGS . '_' . $params['isPage'] . '_page_' . $params['page'], $data, $REDIS_EXPIRE_5M);
                }
                break;
            case \dataApiRoutes::HASHTAGS:
                if ($params['isPage'] == 'lists') {
                    self::setRedis(\dataApiRoutes::POSTS . '_' . $params['isPage'] . '_' . $params['keySlug'] . '_' . md5(@json_encode($params)), $data, $REDIS_EXPIRE_10M);
                }
                break;
            case \dataApiRoutes::POSTS_SEARCH:
                self::setRedis(\dataApiRoutes::POSTS_SEARCH . '_' . md5($params['title']), $data, $REDIS_EXPIRE_1D);
                break;
            case \dataApiRoutes::POST_DETAIL:
                self::setRedis($url_params, $data, $REDIS_EXPIRE_1M);
                break;
            case \dataApiRoutes::COPE_DETAIL:
                self::setRedis($url_params, $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::COPE_WEEK:
                self::setRedis($url_params, $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::COPE_MONTH:
                self::setRedis($url_params, $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::COPE_YEAR:
                self::setRedis($url_params, $data, $REDIS_EXPIRE_30D);
                break;
            case \dataApiRoutes::COPE_CALENDAR_MONTH:
                self::setRedis($url_params, $data, $REDIS_EXPIRE_60D);
                break;
            case \dataApiRoutes::COPE_HISTORICAL_EVENT:
                self::setRedis($url_params, $data, $REDIS_EXPIRE_60D);
                break;
            case \dataApiRoutes::CONFIG:
                self::setRedis(\dataApiRoutes::CONFIG . '_domain_' . Helpers::renderSlug($params['domain']), $data, $REDIS_EXPIRE_5M);
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
