<?php

namespace Modules\Pages\Http\Controllers\Auth;

use App\Helpers\Helpers;
use App\Helpers\RequestHelpers;
use App\Helpers\ResponseHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Modules\Pages\Http\Requests\Auth\AvatarUploadImageRequest;
use Modules\Pages\Http\Requests\Auth\LoginRequest;
use Modules\Pages\Http\Requests\Auth\RegisterRequest;
use Modules\Pages\Http\Requests\Auth\UpdateProfileRequest;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(): object
    {
        try {
            $data['common'] = Helpers::metaHead(["title_seo" => \dataInfoPage::LOGIN]);
            $data['login'] = true;
            return view('pages::auth.login')->with(['data' => $data]);
        } catch (\Exception $e) {
            return !empty($e->getMessage()) ? abort(500) : abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param LoginRequest $request
     * @return object
     */
    public function postLogin(LoginRequest $request): object
    {
        try {
            $data = RequestHelpers::request($request, \dataApiRoutes::LOGIN, \dataApiRoutes::LOGIN, $request->validated(), 'post');
            if (!empty($data) && empty($data['error'])) {
                return redirect(route('page.home'))->withCookie(cookie(\dataKey::SESSION_LOGIN_TOKEN, $data['access_token'], $data['expires_in']));
            } else {
                $errorMessages['error'] = $data['error'];
                $errors = new MessageBag($errorMessages);
                return back()->withInput($request->validated())->withErrors($errors)->withCookie(cookie()->forget(\dataKey::SESSION_LOGIN_TOKEN));;
            }
        } catch (\Exception $e) {
            return !empty($e->getMessage()) ? abort(500) : abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(): object
    {
        try {
            $data['common'] = Helpers::metaHead(["title_seo" => \dataInfoPage::REGISTER]);
            return view('pages::auth.register')->with(['data' => $data]);
        } catch (\Exception $e) {
            return !empty($e->getMessage()) ? abort(500) : abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param RegisterRequest $request
     * @return object
     */
    public function postRegister(RegisterRequest $request): object
    {
        try {
            $data = RequestHelpers::request($request, \dataApiRoutes::REGISTER, \dataApiRoutes::REGISTER, $request->validated(), 'post');
            if (!empty($data['id'])) {
                return redirect(route('page.login'));
            } else {
                $errorMessages['error'] = $data['error'];
                $errors = new MessageBag($errorMessages);
                return back()->withInput($request->validated())->withErrors($errors);
            }
        } catch (\Exception $e) {
            return !empty($e->getMessage()) ? abort(500) : abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function profile(): object
    {
        try {
            $data['common'] = Helpers::metaHead(["title_seo" => \dataInfoPage::PROFILE]);
            return view('pages::auth.profile')->with(['data' => $data]);
        } catch (\Exception $e) {
            return !empty($e->getMessage()) ? abort(500) : abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
//    public function updateAvatar(AvatarUploadImageRequest $request): object
//    {
//        try {
//            $response = RequestHelpers::request(new Request(), \dataApiRoutes::UPLOAD_IMAGE, \dataApiRoutes::UPLOAD_IMAGE, ['images' => $request->file('images'), 'field' => 'images'], 'attach');
//            if ($response['status'] == \dataResponse::STATUS_200) {
//                $update_avatar = RequestHelpers::request(new Request(), \dataApiRoutes::USER_UPDATE, \dataApiRoutes::USER_UPDATE, ['thumbnail' => $response['response']['images']], 'patch');
//                if ($update_avatar['status'] == \dataResponse::STATUS_200) {
//                    return ResponseHelpers::responseSuccess(['thumbnail' => $update_avatar['response']['thumbnail']]);
//                }
//            }
//            return ResponseHelpers::responseServerError();
//        } catch (\Exception $e) {
//            return ResponseHelpers::responseServerError();
//        }
//    }

    /**
     * Show the form for creating a new resource.
     * @param UpdateProfileRequest $request
     * @return object
     */
    public function updateProfile(UpdateProfileRequest $request): object
    {
        try {
            $data = $request->validated();
            if (!empty($data['birthday'])) $data['birthday'] = date('Y-m-d', strtotime($data['birthday']));
            if (!isset($data['is_push_notify'])) $data['is_push_notify'] = 0;
            if (!isset($data['is_send_mail'])) $data['is_send_mail'] = 0;
            $update_avatar = RequestHelpers::request($request, \dataApiRoutes::USER_UPDATE, \dataApiRoutes::USER_UPDATE, $data, 'patch');
            if ($update_avatar) {
                return redirect(route('page.profile'));
            }
            return abort(500);
        } catch (\Exception $e) {
            return abort(500);
        }
    }

    public function logout(): object
    {
        // Session::forget(\dataKey::SESSION_LOGIN_TOKEN);
        return redirect(route('page.home'))->withCookie(cookie()->forget(\dataKey::SESSION_LOGIN_TOKEN));
    }

}
