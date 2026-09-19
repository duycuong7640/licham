@if(Request::routeIs('page.home'))
    @php
        $row = $data['dayInfo'];
        $formatDay = strtotime($row['day']);
        $day = date('d', $formatDay);
        $month = date('m', $formatDay);
        $year = date('Y', $formatDay);

        $rateDay = $data['day']['display'];
        $fmDay = \Carbon\Carbon::parse($row['day']);
        $previousDay = $fmDay->copy()->subDay()->format('d-m-Y');
        $nextDay = $fmDay->copy()->addDay()->format('d-m-Y');

        $mn = \App\Helpers\Helpers::findByType('COPE_DAY');
    @endphp
    <div class="calendar-card reveal">
        <div class="calendar-top">
            <div class="calendar-top__nav">
                <a href="{{ route('page.cate.index', ['slug' => $mn['slug'].'-'.$previousDay]) }}"
                   title="{{ $mn['title'] }} {{ $previousDay }}">
                    <button type="button" aria-label="Tháng trước" class="icon-action"><span>‹</span></button>
                </a>
                <span>Tháng {{ $month }} năm {{ $year }}</span>
                <a href="{{ route('page.cate.index', ['slug' => $mn['slug'].'-'.$nextDay]) }}"
                   title="{{ $mn['title'] }} {{ $nextDay }}">
                    <button type="button" aria-label="Tháng sau" class="icon-action"><span>›</span></button>
                </a>
            </div>
            <div class="weekday">{{ \App\Helpers\Helpers::formatVietnameseDateText($row['day']) }}</div>
            <p class="day">{{ $day }}</p>
            <strong>Trực: {{ !empty($row['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['DAY_OFFICER'], 'DAY_OFFICER') : '' }}</strong>
            <p><em>{!! $row['description'] !!}</em></p>
            <span class="lucky-badge">✣ {{ $rateDay['badge'] }}</span>
        </div>
        <div class="calendar-body">
            <div class="tag-list">
                <span class="tag">Năm {{ $row['strYear'] }}</span>
                <span class="tag">Tháng {{ $row['strMonth'] }}</span>
                <span class="tag">Ngày {{ $row['strDay'] }}</span>
            </div>
            <div class="auspice">
                <div class="auspice-head">
                    <span>☼ Tiết khí: {{ !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'SOLAR_SEASONS') : '' }}</span>
                    @php
                        $currentTime = now('Asia/Ho_Chi_Minh');
                    @endphp
                    <time
                        id="currentTime"
                        datetime="{{ $currentTime->format('c') }}"
                    >
                        {{ $currentTime->format('H:i:s') }}
                    </time>
                </div>
                <strong>Giờ Hoàng đạo</strong>
                <div class="auspice-grid">
                    @if(!empty($row['options']['AUSPICIOUS_HOUR']))
                        @foreach($row['options']['AUSPICIOUS_HOUR'] as $value)
                            @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                            <span>{{ !empty($time['title']) ? $time['title'] : '' }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="date-switch">
                <a href="{{ route('page.cate.index', ['slug' => $mn['slug'].'-'.$previousDay]) }}"
                   title="{{ $mn['title'] }} {{ $previousDay }}">
                    <button>Hôm qua</button>
                </a>
                <a href="{{ route('page.cate.index', ['slug' => $mn['slug']]) }}" title="{{ $mn['title'] }}">
                    <button class="active">Hôm nay</button>
                </a>
                <a href="{{ route('page.cate.index', ['slug' => $mn['slug'].'-'.$nextDay]) }}"
                   title="{{ $mn['title'] }} {{ $nextDay }}">
                    <button>Ngày mai</button>
                </a>
            </div>
        </div>
    </div>
@else
    @php
        $mnBD = \App\Helpers\Helpers::findByType('FORTUNE_BIRTHDAY');
        $mnChild = \App\Helpers\Helpers::findByType('FORTUNE_CHILD');
        $mnRF = \App\Helpers\Helpers::findByType('PHYSIOGNOMY_READING_FACE');
    @endphp
    <div class="side-feature-links reveal">
        <h2>Tiện ích</h2>
        <a class="side-feature-link" href="{{ route('page.cate.index', ['slug' => $mnBD['slug']]) }}"
           title="{{ $mnBD['title'] }}">
            <i>✦</i>
            <span>
                <strong>{{ $mnBD['title'] }}</strong>
                <small>Khám phá tính cách, vận mệnh và tương lai</small>
            </span>
            <span>›</span>
        </a>

        <a class="side-feature-link" href="{{ route('page.cate.index', ['slug' => $mnChild['slug']]) }}"
           title="{{ $mnChild['title'] }}">
            <i>♡</i>
            <span>
                <strong>{{ $mnChild['title'] }} theo ngày sinh</strong>
                <small>Chọn năm sinh con thuận hòa, may mắn</small>
            </span>
            <span>›</span>
        </a>

        <a class="side-feature-link" href="{{ route('page.cate.index', ['slug' => $mnRF['slug']]) }}"
           title="{{ $mnRF['title'] }}">
            <i>◎</i>
            <span>
                <strong>{{ $mnRF['title'] }} đoán vận mệnh</strong>
                <small>Luận tính cách, sự nghiệp và hậu vận</small>
            </span>
            <span>›</span>
        </a>
    </div>
@endif

@php
    $mn = \App\Helpers\Helpers::findByType('FORTUNE_LOVE');
@endphp
<form action="{{ route('page.cate.index', ['slug' => $mn['slug']]) }}" method="get" class="reminder reveal"
      id="scopeLove">
    <h2>✣ Đừng bỏ lỡ!</h2>
    <p>Bói tình yêu theo tuổi?</p>
    <label for="maleSolarDate">Ngày sinh Nam (Dương lịch)</label>
    <input id="maleSolarDate" type="text" name="maleSolarDate" maxlength="10" inputmode="numeric"
           value="{{ request('maleSolarDate', '04-05-1996') }}"
           title="Nhập ngày theo định dạng dd-mm-yyyy, năm 1900-2050">
    <label for="femaleSolarDate">Ngày sinh Nữ (Dương lịch)</label>
    <input id="femaleSolarDate" type="text" name="femaleSolarDate" maxlength="10" inputmode="numeric"
           value="{{ request('femaleSolarDate', '04-05-1999') }}"
           title="Nhập ngày theo định dạng dd-mm-yyyy, năm 1900-2050">
    <button type="submit" class="btn-sm-form">Xem ngay</button>
</form>

@php
    $mnTuvi = \App\Helpers\Helpers::findByType('HOROSCOPE_TV_NAM');
    $mnLaso = \App\Helpers\Helpers::findByType('HOROSCOPE_LAS_SO');
    $mnThanSoHoc = \App\Helpers\Helpers::findByType('NUMEROLOGY_SEARCH');
@endphp
<div class="tool-panel reveal">
    <a href="{{ route('page.cate.index', ['slug' => $mnTuvi['slug']]) }}" title="{{ $mnTuvi['title'] }}">
        <span class="tool-icon">📅</span>
        <span><strong>Tử vi hằng ngày</strong><small>Cập nhật mỗi sáng</small></span>
        <span>›</span>
    </a>
    <a href="{{ route('page.cate.index', ['slug' => $mnLaso['slug']]) }}" title="{{ $mnLaso['title'] }}">
        <span class="tool-icon">🔮</span>
        <span><strong>Lấy Lá số tử vi</strong><small>Phân tích chi tiết</small></span>
        <span>›</span>
    </a>
    <a href="{{ route('page.cate.index', ['slug' => $mnThanSoHoc['slug']]) }}" title="{{ $mnThanSoHoc['title'] }}">
        <span class="tool-icon">🔢</span>
        <span><strong>Xem Thần số học miễn phí</strong><small>Khám phá vận mệnh</small></span>
        <span>›</span>
    </a>
</div>

@php
    $calendarRows = collect($calendarMonth ?? [])->values();

    $firstCalendarDay = $calendarRows->first();

    $initialCalendarYear = (int) data_get(
        $firstCalendarDay,
        'y',
        now('Asia/Ho_Chi_Minh')->year
    );

    $initialCalendarMonth = (int) data_get(
        $firstCalendarDay,
        'm',
        now('Asia/Ho_Chi_Minh')->month
    );

    $today = now('Asia/Ho_Chi_Minh');

    $initialActiveDay = (
        $today->year === $initialCalendarYear
        && $today->month === $initialCalendarMonth
    )
        ? $today->day
        : 1;
@endphp

<div
    class="mini-calendar reveal"
    id="miniCalendar"
    data-calendar-api="{{ route('page.ajax.calendar.month') }}"
    data-day-url-template="{{ route('page.cate.index', ['slug' => 'xem-ngay-tot-xau-__DATE__']) }}"
    data-initial-year="{{ $initialCalendarYear }}"
    data-initial-month="{{ $initialCalendarMonth }}"
    data-initial-day="{{ $initialActiveDay }}"
>
    <div class="mini-calendar__head">
        <button
            id="miniCalendarPrev"
            type="button"
            class="icon-action"
            aria-label="Tháng trước"
        >
            <span>‹</span>
        </button>

        <span id="miniCalendarTitle">
            Lịch âm dương<br>
            Tháng {{ $initialCalendarMonth }} — {{ $initialCalendarYear }}
        </span>

        <button
            id="miniCalendarNext"
            class="icon-action"
            type="button"
            aria-label="Tháng sau"
        >
            <span>›</span>
        </button>
    </div>

    <div
        class="month-grid"
        id="miniCalendarGrid"
        aria-live="polite"
        aria-busy="false"
    ></div>

    <div
        class="mini-calendar-loading"
        id="miniCalendarLoading"
        aria-hidden="true"
    >
        <span class="mini-calendar-loading__spinner"></span>
        <span>Đang tải lịch...</span>
    </div>

    <div
        class="calendar-legend"
        aria-label="Chú giải lịch âm dương"
    >
        <span class="good">
            <i></i>
            Hoàng đạo
        </span>

        <span>
            <i></i>
            Hắc đạo
        </span>
    </div>
</div>

<details
    class="calendar-seo-links"
    id="miniCalendarSeoLinks"
>
    <summary>
        <span class="calendar-seo-links__summary-content">
            <strong>
                Xem ngày tốt xấu tháng
                {{ $initialCalendarMonth }}/{{ $initialCalendarYear }}
            </strong>

            <small>
                Danh sách {{ $calendarRows->count() }} ngày
            </small>
        </span>

        <span
            class="calendar-seo-links__arrow"
            aria-hidden="true"
        >
            <img src="{{ asset('static/web/images/arrow.png') }}" width="12" height="12" title="arrow" alt="arrow" aria-hidden="true" decoding="async"/>
        </span>
    </summary>

    <div class="calendar-seo-links__content">
        <p class="calendar-seo-links__description">
            Tra cứu lịch âm dương, ngày Hoàng đạo, Hắc đạo và
            thông tin Can Chi của từng ngày trong tháng.
        </p>

        <ul>
            @foreach($calendarRows as $calendarDay)
                @php
                    $day = (int) data_get($calendarDay, 'd');
                    $month = (int) data_get($calendarDay, 'm');
                    $year = (int) data_get($calendarDay, 'y');

                    $dateUrl = url(
                        '/xem-ngay-tot-xau-' .
                        str_pad($day, 2, '0', STR_PAD_LEFT) . '-' .
                        str_pad($month, 2, '0', STR_PAD_LEFT) . '-' .
                        $year
                    );

                    $isGoodDay =
                        (int) data_get($calendarDay, 'isDay') === 1;

                    $dayType = $isGoodDay
                        ? 'Hoàng đạo'
                        : 'Hắc đạo';
                @endphp

                <li>
                    <a
                        href="{{ $dateUrl }}"
                        class="{{ $isGoodDay ? 'is-good' : 'is-bad' }}"
                    >
                        <span class="calendar-seo-links__date">
                            {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}/{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <span class="calendar-seo-links__meta">
                            @if(data_get($calendarDay, 'strDay'))
                                {{ data_get($calendarDay, 'strDay') }}
                            @endif

                            <small>{{ $dayType }}</small>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</details>

<script>
    window.MINI_CALENDAR_INITIAL_DATA = @json(
        $calendarMonth ?? [],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
</script>

@if(!empty($topRankPost))
    <div class="widget reveal">
        <h2><span class="popular-mark">#</span> Đọc nhiều</h2>
        <div class="popular">
            @foreach($topRankPost as $row)
                <a href="{{ route('page.post.show', ['slug' => $row['slug']]) }}" title="{{ $row['title'] }}" aria-label="{{ $row['title'] }}">{{ $row['title'] }}</a>
            @endforeach
        </div>
    </div>
@endif

@php
    $tsh = \App\Helpers\Helpers::findByType('NUMEROLOGY_SEARCH');
    $xntx = \App\Helpers\Helpers::findByType('COPE_DAY');
    $ptlove = \App\Helpers\Helpers::findByType('FENG_SHUI_LOVE_MARRIAGE');
    $btd = \App\Helpers\Helpers::findByType('FORTUNE_LOVE');
    $nth = \App\Helpers\Helpers::findByParentKey('PHYSIOGNOMY');
    $tv12chded = \App\Helpers\Helpers::findByType('ZODIAC_12_CUNG_EVERY_DAY');
    $xtxd = \App\Helpers\Helpers::findByType('AGE_VISIT_LAND');
@endphp
<div class="quick-links reveal">
    <div class="widget widget-reset">
        <h2>Khám phá thêm</h2>
    </div>
    <a href="{{ route('page.cate.index', ['slug' => $xntx['slug']]) }}" title="{{ $xntx['title'] }}">{{ $xntx['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $ptlove['slug']]) }}" title="{{ $ptlove['title'] }}">{{ $ptlove['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $btd['slug']]) }}" title="{{ $btd['title'] }}">{{ $btd['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $nth['slug']]) }}" title="{{ $nth['title'] }}">{{ $nth['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $tsh['slug']]) }}" title="{{ $tsh['title'] }}">{{ $tsh['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $tv12chded['slug']]) }}" title="{{ $tv12chded['title'] }}">{{ $tv12chded['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $xtxd['slug']]) }}" title="{{ $xtxd['title'] }}">{{ $xtxd['title'] }}<span>›</span></a>
</div>

@php
    $lch = \App\Helpers\Helpers::findByParentKey('HUMAN_WISH');
    $bvk = \App\Helpers\Helpers::findByParentKey('HUMAN_PRAYER');
    $csh = \App\Helpers\Helpers::findByParentKey('LIFE_CAPTION_HAY');
    $dncnh = \App\Helpers\Helpers::findByType('LIFE_FAMUOS_QUOTES');
    $tdt = \App\Helpers\Helpers::findByParentKey('LIFE_EVERY_DAY');
@endphp
<div class="quick-links reveal">
    <div class="widget widget-reset">
        <h2>Tiện ích nổi bật</h2>
    </div>
    <a href="{{ route('page.cate.index', ['slug' => $lch['slug']]) }}" title="{{ $lch['title'] }}">{{ $lch['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $bvk['slug']]) }}" title="{{ $bvk['title'] }}">{{ $bvk['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $csh['slug']]) }}" title="{{ $csh['title'] }}">{{ $csh['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $dncnh['slug']]) }}" title="{{ $dncnh['title'] }}">{{ $dncnh['title'] }}<span>›</span></a>
    <a href="{{ route('page.cate.index', ['slug' => $tdt['slug']]) }}" title="{{ $tdt['title'] }}">{{ $tdt['title'] }}<span>›</span></a>
</div>
