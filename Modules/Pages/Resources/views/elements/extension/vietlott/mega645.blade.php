@php
    $tickets = @json_decode($row['label_ticket'], true);
    $awards = @json_decode($row['awards'], true);
    $cate = \App\Helpers\Helpers::getSlugByType('VIETLOTT');
    $cate2 = \App\Helpers\Helpers::getSlugByFieldChildType($row['type']);

    if(isset($row['spin']) && $row['spin']){
        $row['ky'] = str_replace('#', '', $row['ky']) + 1;
        $row['ky'] = strlen($row['ky']) < 5 ? '0'.$row['ky'] : $row['ky'];
    }
@endphp

@if(isset($firstItem) && $firstItem)
    <input type="hidden" value="{{ $row['day'] }}" id="ip_{{dataKey::TYPE_VL_645}}"/>
@endif

<div class="mega-live-box mb-15">
    <div class="res-h">
        @if(isset($row['spin']) && $row['spin'])
            <h2>Quay thử xổ số {{ $cate2['title'] }} ngày {{ date('d-m-Y') }}</h2>
        @else
            @if(Route::currentRouteName() == 'page.home')
                @if(isset($h1) && $h1)
                    <h1>{{ $cate['title'] }} - Xổ số Mega 6/45 {{ $row['thu'] }}
                        , KQ {{ $cate2['title'] }} hôm nay</h1>
                @else
                    <h2>Kết quả xổ số Mega 6/45 {{ $row['thu'] }} ngày {{ \App\Helpers\Helpers::formatDate($row['day']) }}</h2>
                @endif
            @else
                @if(isset($h1) && $h1)
                    <h1>{{ $cate['title'] }} - Xổ số Mega 6/45 {{ $row['thu'] }}
                        , KQ {{ $cate2['title'] }} hôm nay</h1>
                @else
                    <h2>Kết quả xổ số Mega 6/45 {{ $row['thu'] }} ngày {{ \App\Helpers\Helpers::formatDate($row['day']) }}</h2>
                @endif
            @endif
        @endif
        <p>{{ \App\Helpers\Helpers::formatVietnameseDate($row['day']) }}</p>
        <div class="res-site-link">
            <a title="{{ $cate2['title'] }}"
               href="{{ route('page.cate.index', ['slug' => $cate['slug'].'/'.$cate2['slug']]) }}">{{ $cate2['title'] }}</a>
            <a title="{{ strtoupper($cate2['title']) }} {{ $row['thu'] }}"
               href="{{ route('page.cate.vietlott.thu', ['slug' => $cate['slug'], 'slug2' => $cate2['slug'], 'thu' => \App\Helpers\Helpers::renderSlug($row['thu'])]) }}">{{ strtoupper($cate2['title']) }} {{ $row['thu'] }}</a>
            <a title="{{ strtoupper($cate2['title']) }} {{ \App\Helpers\Helpers::formatDate($row['day']) }}"
               href="{{ route('page.cate.vietlott.ngay', ['slug' => $cate['slug'], 'slug2' => $cate2['slug'], 'ngay' => str_replace('/', '-', \App\Helpers\Helpers::formatDate($row['day']))]) }}">
                {{ strtoupper($cate2['title']) }} {{ \App\Helpers\Helpers::formatDate($row['day']) }}
            </a>
        </div>
    </div>

    @if(isset($row['spin']) && $row['spin'])
        <div class="tryspin-guide-wrapper">
            <div class="guide-text">
                <i class="fas fa-info-circle"></i>
                <span>Hệ thống giả lập quay số điện tử chính xác theo thuật toán lồng cầu. Bấm nút dưới đây để bắt đầu thử vận may!</span>
            </div>

            <div class="wrap-btn-spin">
                <button class="btn-vietlott-spin btn-spin-style" data-type="mega645">
                    <span class="icon-spin">🔄</span>
                    <span class="btn-text">QUAY THỬ KẾT QUẢ</span>
                </button>
            </div>
        </div>
    @endif

    <div class="mega-live-body">
        <div class="mega-live-jackpot mb-15">
            @foreach($awards as $k => $award)
                @if($award[0] == 'Jackpot')
                    <div class="power-live-label">Jackpot Mega 6/45</div>
                    <div class="power-live-value lv-value">{{ $award[3] }} đồng</div>
                @endif
            @endforeach
        </div>

        <div class="mega-live-round">
            Kỳ quay thưởng: <b>#{{ $row['ky'] }}</b>
        </div>

        @if(isset($row['spin']) && $row['spin'])
            <div class="mega-live-numbers" id="area-mega645">
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
            </div>
        @else
            <div class="mega-live-numbers">
                @foreach($tickets as $k => $ticket)
                    <span class="power-live-ball">{{ $ticket }}</span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mega-live-table">
        <div class="mega-live-row mega-live-head">
            <div>Giải thưởng</div>
            <div>Trúng khớp</div>
            <div>Số lượng giải</div>
            <div>Giá trị giải (đồng)</div>
        </div>

        @foreach($awards as $k => $award)
            <div class="power-live-row">
                <div>{{ $award[0] }}</div>
                <div>{{ $award[1] }}</div>
                <div>{{ \App\Helpers\Helpers::numberFormat((int)$award[2]) }}</div>
                <div>{{ $award[3] }}</div>
            </div>
        @endforeach
    </div>
</div>
