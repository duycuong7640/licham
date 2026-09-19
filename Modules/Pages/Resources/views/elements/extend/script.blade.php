<script type="text/javascript" src="{{ asset('/static/web/js/app.js') }}" defer></script>
@yield('scripts')
@yield('validate')
{!! !empty($configData) ? \App\Helpers\Helpers::renderCode($configData, settingKey::CODE_FOOTER) : '' !!}
