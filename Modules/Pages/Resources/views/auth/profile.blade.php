@extends('pages::layouts.auth')

@section('content')
    <div class="wrap-auth">
        <div class="auth-container bg-opacity radius">
            <form method="post" action="" class="forms-sample form-auth" id="form-update">
                @csrf()
                <p class="title-p1">Profile: {{ $userData->email }}</p>
                <div class="wrap-form">
                    <label>FullName</label>
                    <input type="text" name="last_name" value="{{ $userData->last_name }}" class="form-control"
                           placeholder="Type your FullName">
                </div>
                <div class="wrap-form">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" id="password"
                           placeholder="Type your password">
                </div>
                <div class="wrap-form">
                    <label>Re-Password</label>
                    <input type="password" name="password_confirm" class="form-control" id="password_confirm"
                           placeholder="Type your password">
                </div>
                <div class="wrap-form-action">
                    <div class="mb-3 button-group">
                        <button class="btn btn-submit" type="submit">Change</button>
                    </div>
                    @if(!empty($userData->id))
                        <div class="mb-3 button-group">
                            <a href="{{ route('page.logout') }}" title="Logout">
                                <button class="btn btn-submit secondary" type="button">Logout</button>
                            </a>
                        </div>
                    @endif
                    @if($errors->has('error'))
                        <p class="alert alert-danger">{{$errors->first('error')}}</p>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@section('validate')
    {!! JsValidator::formRequest('Modules\Pages\Http\Requests\Auth\UpdateProfileRequest','#form-update'); !!}
@endsection
