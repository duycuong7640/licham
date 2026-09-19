@extends('pages::layouts.auth')

@section('content')
    <div class="wrap-auth">
        <div class="auth-container bg-opacity radius">
            <form method="post" action="" class="forms-sample form-auth" id="form-register">
                @csrf()
                <p class="title-p1">Sign up!</p>
                <p class="title-p2">Please register to create your account.</p>
                <div class="wrap-form">
                    <label>FullName</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Type your FullName">
                </div>
                <div class="wrap-form">
                    <label>E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Type your e-mail">
                </div>
                <div class="wrap-form">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Type your password">
                </div>
                <div class="wrap-form">
                    <label>Re-Password</label>
                    <input type="password" name="password_confirm" class="form-control" id="password_confirm" placeholder="Type your password">
                </div>
                <div class="wrap-form-action">
                    <div class="mb-3 button-group">
                        <button class="btn btn-submit" type="submit">Register</button>
                    </div>
                    <div class="sign-up">
                        <p>
                            Do you already have an account? <a href="{{ route('page.login') }}" title="Sign In">Sign In</a>
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
    {!! JsValidator::formRequest('Modules\Pages\Http\Requests\Auth\RegisterRequest','#form-register'); !!}
@endsection
