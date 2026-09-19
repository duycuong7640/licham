<div class="live-box">
    <h2 class="live-title">
        {{ $cate['type'] }} hôm nay - Xem trực tiếp kết quả xổ số {{ $cate['title'] }}
    </h2>

    <!-- Tabs -->
    <div class="live-tabs">
        @foreach(\dataMenu::menus() as $k=>$row)
            @if($row['level'] == 2 && $row['slug'] == 'truc-tiep')
                @php
                    $cateLive = \App\Helpers\Helpers::getSlugByFieldType($row['type']);
                @endphp
                <div class="live-tab @if($row['type'] == $cate['type']) active-live @endif">
                    <a href="{{ route('page.cate.day', ['slug' => $cateLive['slug'], 'day' => $row['slug']]) }}"
                       title="Trực tiếp {{ $row['type'] }}">
                        Trực tiếp {{ $row['type'] }}
                    </a>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Countdown -->
    <div class="live-countdown"
         data-time="{{ dataMenu::LIVE_DESCRIPTION[$data['category']['type']]['time'] }}"
         data-duration="30"> <!-- phút -->

        <span class="live-label">Còn</span>

        <div class="time-box-live time-hour"></div>
        <div class="time-box-live time-minute"></div>
        <div class="time-box-live time-second"></div>

        <div class="live-status-text"></div>
    </div>

    <!-- Description -->
    <div class="live-desc">
        {!! dataMenu::LIVE_DESCRIPTION[$data['category']['type']]['des'] !!}
    </div>

    <!-- Sub Tabs -->
    @php $submenu = \App\Helpers\Helpers::getSubMenuByType($cate['type']); @endphp
    <div class="live-sub-tabs">
        <div class="live-sub active-live1">
            <a href="{{ route('page.cate.index', ['slug' => $cate['slug']]) }}" title="{{ $cate['type'] }}">{{ $cate['title'] }}</a>
        </div>
        @foreach($submenu as $k=>$row)
            @if($row['slug'] !== 'truc-tiep')
                <div class="live-sub active-live1">
                    <a href="{{ route('page.cate.index', ['slug' => $cate['slug'].'/'.$row['slug']]) }}"
                       title="{{ $cate['type'] }} {{ $row['title'] }}">{{ $row['title'] }}</a>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Status -->
    <div class="live-status">
        <span class="live-loading"></span>
        <span class="live-text">Đang chờ kết quả</span>
        <span class="live-dropdown">▼</span>
    </div>
</div>
