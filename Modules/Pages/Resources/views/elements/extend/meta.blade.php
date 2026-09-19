<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
@php
    $googleIndex = !empty($configData)
        ? \App\Helpers\Helpers::renderCode(
            $configData,
            settingKey::GOOGLE_INDEX
        )
        : 0;

    $robots = $data['common']['robots']
        ?? ($googleIndex == 1 ? 'index, follow' : 'noindex, nofollow');
@endphp
<meta name="robots" content="{{ $robots }}">
@if(!empty($data['cache']))
    TOKEN_ADD
@else
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endif
<link rel="apple-touch-icon" sizes="180x180"
      href="{{ asset('static/favicon/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32"
      href="{{ asset('static/favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16"
      href="{{ asset('static/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('static/favicon/site.webmanifest') }}">
@if(!empty($data['common']['title_seo']))
    <title>{{ $data['common']['title_seo'] }}</title>
@endif
@if(!empty($data['common']['meta_des']))
    <meta name="description" content="{{ $data['common']['meta_des'] }}"/>
@endif
@if(!empty($data['common']['meta_key']))
    <meta name="keywords" content="{{ $data['common']['meta_key'] }}"/>
@endif
<meta name="format-detection" content="telephone=no">
@stack('schema')
<link rel="alternate" type="application/rss+xml" title="RSS Feed - {{ env('APP_NAME') }}"
      href="{{ route('rss.feed') }}">
{!! !empty($data['shareMXH']) ? $data['shareMXH'] : '' !!}
@php
    $canonical = !empty($data['common']['canonical']) ? $data['common']['canonical'] : request()->url();
@endphp
<link rel="canonical" href="{{ $canonical }}">
<link rel="alternate" href="{{ $canonical }}" hreflang="x-default"/>
<link rel="alternate" href="{{ $canonical }}" hreflang="vi-VN"/>
@stack('paginate')
@stack('meta')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">


