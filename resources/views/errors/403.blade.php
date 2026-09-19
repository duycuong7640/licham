@extends('pages::layouts.error')

@section('content')
    <div class="error-page text-center">
        <img src="{{ asset('static/web/images/overload.png') }}" title="overload" alt="overload">
        <div class="mt-4">
            <a href="{{ route('page.home') }}" class="btn btn-submit" style="color: #fff; background: #9d111d; font-size: 15px; padding: 8px 15px; border-radius: 5px;">Trang chủ</a>
        </div>
    </div>
@endsection
