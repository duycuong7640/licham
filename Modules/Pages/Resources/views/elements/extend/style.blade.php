<link href="{{ asset('/static/web/css/style.css') }}?v=1.5" rel="stylesheet" media="all" />
<link href="{{ asset('/static/web/css/icons.css') }}?v=1.1" rel="stylesheet" media="all" />
@yield('style')
{!! !empty($configData) ? \App\Helpers\Helpers::renderCode($configData, settingKey::CODE_HEADER) : '' !!}
