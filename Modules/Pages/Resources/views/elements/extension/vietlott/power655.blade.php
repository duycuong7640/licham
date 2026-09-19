@php
    $tickets = @json_decode($row['label_ticket'], true);
    $awards = @json_decode($row['awards'], true);
    $cate = \App\Helpers\Helpers::getSlugByType('VIETLOTT');
    $cate2 = \App\Helpers\Helpers::getSlugByFieldChildType($row['type']);

    if(isset($row['spin']) && $row['spin']) {
        $row['ky'] = str_replace('#', '', $row['ky']) + 1;
        $row['ky'] = strlen($row['ky']) < 5 ? '0'.$row['ky'] : $row['ky'];
    }
@endphp
@if(isset($firstItem) && $firstItem)
    <input type="hidden" value="{{ $row['day'] }}" id="ip_{{dataKey::TYPE_VL_655}}"/>
@endif
<div class="power-live-box mb-15">
    <div class="res-h">
        @if(isset($row['spin']) && $row['spin'])
            <h2>Quay thử xổ số {{ $cate2['title'] }} ngày {{ date('d-m-Y') }}</h2>
        @else
            @if(Route::currentRouteName() == 'page.home')
                @if(isset($h1) && $h1)
                    <h1>{{ $cate['title'] }} - Xổ số Power 6/55 {{ $row['thu'] }}, KQ {{ $cate2['title'] }} hôm nay</h1>
                @else
                    <h2>Kết quả xổ số Power 6/55 {{ $row['thu'] }} ngày {{ \App\Helpers\Helpers::formatDate($row['day']) }}</h2>
                @endif
            @else
                @if(isset($h1) && $h1)
                    <h1>{{ $cate['title'] }} - Xổ số Power 6/55 {{ $row['thu'] }}, KQ {{ $cate2['title'] }} hôm nay</h1>
                @else
                    <h2>Kết quả xổ số Power 6/55 {{ $row['thu'] }} ngày {{ \App\Helpers\Helpers::formatDate($row['day']) }}</h2>
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
                <button class="btn-vietlott-spin btn-spin-style" data-type="power655">
                    <span class="icon-spin">🔄</span>
                    <span class="btn-text">QUAY THỬ KẾT QUẢ</span>
                </button>
            </div>
        </div>
    @endif

    <div class="power-live-body">
        <div class="power-live-jackpot">
            @foreach($awards as $k => $award)
                @if($award[0] == 'Jackpot 1')
                    <div class="power-live-label">Jackpot 1 Power 6/55</div>
                    <div class="power-live-value lv-value">{{ $award[3] }} đồng</div>
                @endif
                @if($award[0] == 'Jackpot 2')
                    <div class="power-live-label">Jackpot 2 Power 6/55</div>
                    <div class="power-live-value lv-value">{{ $award[3] }} đồng</div>
                @endif
            @endforeach

            <div class="power-live-label">Kết quả trúng thưởng Power 6/55</div>
        </div>

        <div class="power-live-round">
            Kỳ quay thưởng: <b>#{{ $row['ky'] }}</b>
        </div>

        @if(isset($row['spin']) && $row['spin'])
            <div class="mega-live-numbers" id="area-power655">
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball">--</span>
                <span class="power-live-ball power-live-special">--</span>
            </div>
        @else
            <div class="power-live-numbers">
                @foreach($tickets as $k => $ticket)
                    <span
                        class="power-live-ball @if($k == (count($tickets) - 1)) power-live-special @endif">{{ $ticket }}</span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="power-live-table">
        <div class="power-live-row power-live-head">
            <div>Giải thưởng</div>
            <div>Trúng khớp</div>
            <div>Số lượng giải</div>
            <div>Giá trị giải (đồng)</div>
        </div>

        @foreach($awards as $k => $award)
            <div class="power-live-row">
                <div>{{ $award[0] }}</div>
                <div>
                    @if($award[0] == 'Jackpot 2')
                        O O O O O | <span class="red">O</span>
                    @else
                        {{ $award[1] }}
                    @endif
                </div>
                <div>{{ \App\Helpers\Helpers::numberFormat((int)$award[2]) }}</div>
                <div>{{ $award[3] }}</div>
            </div>
        @endforeach
    </div>
</div>
