@extends('pages::layouts.auth')

@section('content')
    <div class="wrap-auth">
        <div class="auth-container bg-opacity radius">
            <form method="post" action="" class="forms-sample form-auth" id="form-login">
            @csrf()
                <p class="title-p1">Welcome back!</p>
                <p class="title-p2">Please login to access your account.</p>
                <div class="wrap-form">
                    <label>E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @if(!empty($errors->messages()['valid_error']['email'][0])) is-invalid @endif" placeholder="Type your e-mail">
                    @if(!empty($errors->messages()['valid_error']['email'][0]))
                        <span id="email-error"
                              class="invalid-feedback">{{ $errors->messages()['valid_error']['email'][0] }}</span>
                    @endif
                </div>
                <div class="wrap-form">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control @if(!empty($errors->messages()['valid_error']['password'][0])) is-invalid @endif" placeholder="Type your password">
                    @if(!empty($errors->messages()['valid_error']['password'][0]))
                        <span id="email-error"
                              class="invalid-feedback">{{ $errors->messages()['valid_error']['password'][0] }}</span>
                    @endif
{{--                    <div class="wrap-forgot">--}}
{{--                        <a href="" title="Forgot Password">--}}
{{--                            Forgot Password?--}}
{{--                        </a>--}}
{{--                    </div>--}}
                </div>
                <div class="wrap-form-action">
                    <div class="mb-3 button-group">
                        <button class="btn btn-submit" type="submit">Log In</button>
                    </div>
                    <div class="sign-up">
                        <p>
                            Don't have an account? <a href="{{ route('page.register') }}" title="Register">Sign Up</a>
                        </p>
                    </div>
                    @if($errors->has('error'))
                        <p class="alert alert-danger">{{$errors->first('error')}}</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@section('validate')
    {!! JsValidator::formRequest('Modules\Pages\Http\Requests\Auth\LoginRequest','#form-login'); !!}
@endsection
