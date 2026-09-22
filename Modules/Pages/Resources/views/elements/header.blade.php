{!! !empty($configData) ? \App\Helpers\Helpers::renderCode($configData, settingKey::CODE_BODY) : '' !!}
<header class="site-header">
    <div class="header-main">
        <a class="logo" href="{{ route('page.home') }}" title="Logo">
          <span class="logo-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
              <rect x="3" y="4.5" width="18" height="16" rx="2.5"></rect>
              <path d="M7 2.5v4M17 2.5v4M3 9h18"></path>
              <path
                  class="logo-date"
                  d="M8 12h3v3H8zM14 12h3v3h-3zM8 17h3v2H8z"
              ></path>
            </svg>
          </span>
            <span class="logo-copy">
            <b>Lịch Âm Tốt</b>
            <small>Lịch Việt mỗi ngày</small>
          </span>
        </a>
        @include('pages::elements.menu')
        <div class="header-tools">
            <div class="live-clock" aria-label="Giờ hiện tại tại Việt Nam">
                <span class="live-clock-dot" aria-hidden="true"></span>
                <span class="live-clock-label">Giờ Việt Nam</span>
                <time id="clock" class="clock" datetime="">00:00:00</time>
            </div>
            <button class="menu-btn" type="button" aria-label="Mở menu">
                ☰
            </button>
        </div>
    </div>
    <div class="subnav">
        <div class="subnav-inner">
            <span>Âm lịch Việt Nam · GMT+7</span>
            @if(!empty($data['hyperlinks']))
                <div class="subnav-links">
                    @foreach($data['hyperlinks'] as $row)
                        <a href="#{{ \App\Helpers\Helpers::renderSlug($row) }}">{{ $row }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</header>
