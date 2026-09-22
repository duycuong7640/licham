<link href="{{ asset('/static/web/css/style.css') }}" rel="stylesheet" media="all" />
<link href="{{ asset('/static/web/css/icons.css') }}" rel="stylesheet" media="all" />
@yield('style')
{!! !empty($configData) ? \App\Helpers\Helpers::renderCode($configData, settingKey::CODE_HEADER) : '' !!}
