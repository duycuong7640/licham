@extends('pages::layouts.master')

@section('content')
    @php
        $year = $data['year'];
        $preYear = $year - 1 < 1900 ? '#' : $year - 1;
        $nextYear = $year + 1 > 2050 ? '#' : $year + 1;

        $strYear = '';
        foreach($data['calendar'] as $monthNum => $monthData) {
            foreach($monthData['days'] as $kd=>$day) {
                if($day['type'] != 'empty' && $day['lunar_year'] == $year) {
                    $strYear = $day['strYear'];
                    break;
                }
            }
            if($strYear) break;
        }

        // data
        $isLeapYear = ($year % 400 === 0) || ($year % 4 === 0 && $year % 100 !== 0);
        $totalDaysInYear = $isLeapYear ? 366 : 365;

        $totalGoodDays = 0;
        $totalBadDays = 0;
        $monthStats = [];
        $tetSolarDate = null;

        $isLeapYear =
        ($year % 400 === 0)
        || ($year % 4 === 0 && $year % 100 !== 0);

        $totalDaysInYear = $isLeapYear ? 366 : 365;

        $tetDay = null;
        $firstDayOfYear = null;
        $lastDayOfYear = null;
        foreach ($data['calendar'] as $monthNum => $monthData) {

            foreach ($monthData['days'] as $day) {

                if (empty($day) || $day['type'] === 'empty' || (int) $day['solar_year'] !== (int) $year) {
                    continue;
                }

                /*
                 * 01/01 dương lịch
                 */
                if (
                    (int) $day['solar_day'] === 1
                    && (int) $day['solar_month'] === 1
                ) {
                    $firstDayOfYear = $day;
                }

                /*
                 * 31/12 dương lịch
                 */
                if (
                    (int) $day['solar_day'] === 31
                    && (int) $day['solar_month'] === 12
                ) {
                    $lastDayOfYear = $day;
                }

                /*
                 * Mùng 1 Tết của đúng năm âm lịch
                 */
                if (
                    empty($tetDay)
                    && (int) $day['lunar_day'] === 1
                    && (int) $day['lunar_month'] === 1
                    && (int) $day['lunar_year'] === (int) $year
                ) {
                    $tetDay = $day;
                }
            }
        }


        /*
         * Thứ của ngày Tết
         */
        $tetWeekday = null;

        if (!empty($tetDay)) {

            $weekdays = [
                0 => 'Chủ nhật',
                1 => 'Thứ Hai',
                2 => 'Thứ Ba',
                3 => 'Thứ Tư',
                4 => 'Thứ Năm',
                5 => 'Thứ Sáu',
                6 => 'Thứ Bảy',
            ];

            $tetCarbon = \Carbon\Carbon::create(
                (int) $tetDay['solar_year'],
                (int) $tetDay['solar_month'],
                (int) $tetDay['solar_day']
            );

            $tetWeekday = $weekdays[$tetCarbon->dayOfWeek] ?? null;
        }


        /*
         * Tìm tháng có nhiều ngày hoàng đạo nhất
         */
        $maxGoodDays = 0;
        $bestGoodMonths = [];

        foreach ($monthStats as $monthNum => $stats) {

            if ($stats['good'] > $maxGoodDays) {
                $maxGoodDays = $stats['good'];
                $bestGoodMonths = [$monthNum];

            } elseif (
                $stats['good'] === $maxGoodDays
                && $maxGoodDays > 0
            ) {
                $bestGoodMonths[] = $monthNum;
            }
        }

        $bestGoodMonthsText = collect($bestGoodMonths)
            ->map(fn($month) => 'tháng ' . $month)
            ->implode(', ');
    @endphp
    <section class="card year-page-head">
        <div>
            <span class="section-label">LỊCH VẠN NIÊN</span>
            <h1>Lịch âm năm {{ $year }}</h1>
            <p>Năm {{ $strYear }} · {{ $year }}</p>
        </div>
        <div class="year-controls" data-url="{{ route('page.cope.show.year', ['year' => '__YEAR__']) }}">
            @if($preYear != '#')
                <a class="btn-loading" href="{{ route('page.cope.show.year', ['year' => $preYear]) }}" aria-label="Năm trước">‹</a>
            @endif
            <label for="yearSelect">
                Chọn năm
                <select id="yearSelect">
                    @for($y = 1900; $y <= 2050; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </label>
            @if($nextYear != '#')
                <a class="btn-loading" href="{{ route('page.cope.show.year', ['year' => $nextYear]) }}" aria-label="Năm sau">›</a>
            @endif
        </div>
    </section>
    <div class="year-summary">
        <div>
            <strong>{{ $strYear }}</strong>
            <span>Can Chi năm</span>
        </div>

        <div>
            <strong>{{ $totalDaysInYear }}</strong>
            <span>Ngày dương lịch</span>
        </div>

        <div>
            <strong>{{ $isLeapYear ? 'Có' : 'Không' }}</strong>
            <span>Năm nhuận dương lịch</span>
        </div>

        @if(!empty($tetSolarDate))
            <div>
                <strong>{{ $tetSolarDate }}</strong>
                <span>Tết Nguyên đán</span>
            </div>
        @endif
    </div>
    <section class="year-calendar-section" aria-labelledby="pre_year-calendar-title">
        <div class="year-section-head">
            <div>
                <span class="section-label">TỔNG QUAN 12 THÁNG</span>
                <h2 id="pre_year-calendar-title">Lịch âm năm {{ $year }} theo 12 tháng</h2>
            </div>
            <div class="legend">
                <span><i></i>Ngày hoàng đạo</span>
                <span><i class="bad-dot"></i>Ngày hắc đạo</span>
            </div>
        </div>
        <div class="year-overview">
            @foreach($data['calendar'] as $monthNum => $monthData)
                <section class="mini-month">
                    <h3>
                        <a href="{{ route('page.cope.show.month', ['month' => \App\Helpers\Helpers::checkNumber($monthData['month']), 'year' => $monthData['year']]) }}"
                           title="Tháng {{ $monthData['month'] }}">
                            Lịch âm tháng {{ $monthData['month'] }}/{{ $monthData['year'] }}
                        </a>
                    </h3>
                    <div class="mini-week">
                        <span>T2</span>
                        <span>T3</span>
                        <span>T4</span>
                        <span>T5</span>
                        <span>T6</span>
                        <span>T7</span>
                        <span>CN</span>
                    </div>
                    <div class="mini-days">
                        @foreach($monthData['days'] as $kd=>$day)
                            @if($day['type'] == 'empty')
                                <span class="mini-muted" aria-hidden="true">
                                    <strong></strong>
                                    <small></small>
                                </span>
                            @else
                                @php
                                    $isDay = $day['is_good'] ? 'mini-good' : 'mini-bad';
                                    $today = $day['date'] == date('d-m-Y') ? "today" : "";
                                    $dayOffices = !empty($day['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption2($day['options']['DAY_OFFICER'], 'DAY_OFFICER') : '';
                                    $montViecnenlam = $dayOffices ? explode('. ', \App\Helpers\Helpers::getContentInBrackets($dayOffices)) : [];
                                @endphp
                                <a href="{{ route('page.cope.show.day', ['day' => $day['solar_day'], 'month' => $day['solar_month'], 'year' => $day['solar_year']]) }}" class="{{ $isDay }} {{ $today }}"
                                   aria-label="Ngày {{ $day['solar_day'] }} tháng {{ $day['solar_month'] }}, âm lịch {{ $day['lunar_day'] }} tháng {{ $day['lunar_month'] }}">
                                    <strong>{{ $day['solar_day'] }}</strong>
                                    <small>{{ $day['lunar_day'] }}</small>
                                    @if(!empty($today))
                                        <em>Hôm nay</em>
                                    @endif
                                    <span class="day-tooltip" role="tooltip">
                                        <b>{{ $day['solar_day'] }}/{{ $day['solar_month'] }}/{{ $day['solar_year'] }} · Âm {{ $day['lunar_day'] }}/{{ $day['lunar_month'] }}</b>
                                        <span><strong>Can Chi:</strong> {{ $day['strDay'] }}</span>
                                        <span><strong>Đánh giá:</strong> Ngày {{ $day['is_good'] ? 'Hoàng đạo' : 'Hắc đạo' }}</span>
                                        <span>
                                            <strong>Giờ tốt:</strong>
                                            @if(!empty($day['options']['AUSPICIOUS_HOUR']))
                                                @foreach($day['options']['AUSPICIOUS_HOUR'] as $k=>$value)
                                                    @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                                                    @if($k), @endif
                                                    {{ !empty($time['title']) ? $time['title'] : '' }}
                                                    ({{ !empty($time['hour']) ? $time['hour'] : '' }})
                                                @endforeach
                                            @endif
                                        </span>
                                        <span><strong>Phù hợp:</strong> {{ !empty($montViecnenlam[0]) ? $montViecnenlam[0] : '' }}</span>
                                    </span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>
    <section class="section-block year-information" id="tong-quan" aria-labelledby="year-overview-title">
        <article class="card seo-analysis">

            <div class="cms-content">

            <span class="section-label">
                THÔNG TIN NĂM {{ $year }}
            </span>


                <h2 id="year-overview-title">
                    Lịch âm năm {{ $year }}: {{ $strYear }}, Tết và các mốc âm dương
                </h2>


                <p>
                    <strong>Năm {{ $year }}</strong> là năm
                    <strong>{{ $strYear }}</strong> theo Can Chi.
                    Đây là
                    @if($isLeapYear)
                        <strong>năm nhuận dương lịch</strong> với 366 ngày,
                    @else
                        năm dương lịch có 365 ngày,
                    @endif
                    bắt đầu từ ngày 01/01/{{ $year }} và kết thúc
                    vào ngày 31/12/{{ $year }}.
                    Lịch âm trong năm không trùng hoàn toàn với ranh giới
                    của năm dương lịch, vì năm âm lịch được tính theo
                    chu kỳ tháng âm và bắt đầu từ Tết Nguyên đán.
                </p>


                @if(!empty($tetDay))

                    <h3>
                        Tết Nguyên đán {{ $year }} vào ngày nào?
                    </h3>

                    <p>
                        Mùng 1 tháng Giêng năm
                        <strong>{{ $strYear }}</strong>
                        rơi vào

                        @if(!empty($tetWeekday))
                            <strong>{{ $tetWeekday }}</strong>,
                        @endif

                        ngày

                        <a href="{{ route('page.cope.show.day', [
                            'day'   => $tetDay['solar_day'],
                            'month' => $tetDay['solar_month'],
                            'year'  => $tetDay['solar_year']
                        ]) }}"
                           title="Lịch ngày {{ $tetDay['solar_day'] }}/{{ $tetDay['solar_month'] }}/{{ $tetDay['solar_year'] }}">

                            <strong>
                                {{ \App\Helpers\Helpers::checkNumber($tetDay['solar_day']) }}/{{ \App\Helpers\Helpers::checkNumber($tetDay['solar_month']) }}/{{ $tetDay['solar_year'] }}
                            </strong>

                        </a>

                        dương lịch. Đây là thời điểm bắt đầu
                        tháng Giêng và năm âm lịch {{ $strYear }}.
                    </p>

                @endif


                @if(!empty($firstDayOfYear) && !empty($lastDayOfYear))
                    <h3>
                        Đầu và cuối năm {{ $year }} là ngày bao nhiêu âm lịch?
                    </h3>
                    <p>
                        Ngày
                        <a href="{{ route('page.cope.show.day', ['day'   => $firstDayOfYear['solar_day'], 'month' => $firstDayOfYear['solar_month'], 'year'  => $firstDayOfYear['solar_year']]) }}" title="Lịch ngày {{ $firstDayOfYear['solar_day'] }}/{{ $firstDayOfYear['solar_month'] }}/{{ $firstDayOfYear['solar_year'] }}">
                            <strong>{{ $firstDayOfYear['solar_day'] }}/{{ $firstDayOfYear['solar_month'] }}/{{ $firstDayOfYear['solar_year'] }}</strong>
                        </a>
                        dương lịch tương ứng ngày
                        <strong>
                            {{ \App\Helpers\Helpers::checkNumber($firstDayOfYear['lunar_day']) }}/{{ \App\Helpers\Helpers::checkNumber($firstDayOfYear['lunar_month']) }}
                        </strong>
                        âm lịch.
                        Trong khi đó, ngày
                        <a href="{{ route('page.cope.show.day', ['day'   => $lastDayOfYear['solar_day'], 'month' => $lastDayOfYear['solar_month'], 'year'  => $lastDayOfYear['solar_year']]) }}" title="Lịch ngày {{ $lastDayOfYear['solar_day'] }}/{{ $lastDayOfYear['solar_month'] }}/{{ $lastDayOfYear['solar_year'] }}">
                            <strong>{{ $lastDayOfYear['solar_day'] }}/{{ $lastDayOfYear['solar_month'] }}/{{ $lastDayOfYear['solar_year'] }}</strong>
                        </a>
                        tương ứng ngày
                        <strong>
                            {{ \App\Helpers\Helpers::checkNumber($lastDayOfYear['lunar_day']) }}/{{ \App\Helpers\Helpers::checkNumber($lastDayOfYear['lunar_month']) }}
                        </strong>
                        âm lịch.
                        Hai mốc này giúp thấy rõ sự chênh lệch giữa
                        năm dương lịch và chu kỳ của lịch âm.
                    </p>
                @endif
                <h3>
                    Năm {{ $year }} có phải năm nhuận không?
                </h3>
                <p>
                    @if($isLeapYear)
                        Có. <strong>{{ $year }} là năm nhuận dương lịch</strong>,
                        có 366 ngày; riêng tháng 2 có 29 ngày.
                    @else
                        Không. <strong>{{ $year }} không phải năm nhuận dương lịch</strong>,
                        có 365 ngày; tháng 2 có 28 ngày.
                    @endif
                    Khái niệm năm nhuận dương lịch này khác với
                    <strong>tháng nhuận trong âm lịch</strong>.
                    Tháng nhuận âm lịch được xác định theo quy tắc
                    của lịch âm dương và không phụ thuộc trực tiếp
                    vào việc năm dương lịch có 365 hay 366 ngày.
                </p>
            </div>
        </article>
    </section>
    <section class="section-block">
        <article class="card month-day-list good-list" id="lich-am-nam-khac">
            <span class="section-label">LỊCH ÂM NĂM KHÁC</span>
            <h2>Xem lịch âm các năm khác</h2>
            <div id="pre_goodDays" class="date-link-grid">
                @php
                    $fpYear = $year - 9 < 1900 ? 1900 : $year - 9;
                    $fnYear = $year + 10 > 2050 ? 2050 : $year + 10;
                @endphp
                @for($i = $fpYear; $i <= $fnYear; $i ++)
                    <a href="{{ route('page.cope.show.year', ['year' => $i]) }}" title="Lịch âm năm {{ $i }}" class="text-center text-bold">Lịch âm năm {{ $i }}</a>
                @endfor
            </div>
        </article>
    </section>
    <section class="section-block holiday-grid" aria-label="Ngày lễ nổi bật" id="ngay-le-ky-niem">
        <article class="card holiday-card">
            <span class="section-label">DƯƠNG LỊCH</span>
            <h2>Ngày lễ và kỷ niệm</h2>
            @if(!empty($data['yearData']['gregorianData']))
                @php $gregorianData = @json_decode($data['yearData']['gregorianData']); @endphp
                <ul class="cal-year-ext-holiday-list">
                    @foreach($gregorianData as $r)
                        @php $value = \App\Helpers\Helpers::parseEvent($r); @endphp
                        @if(!empty($value['day']) && !empty($value['month']) && !empty($value['value']))
                            <li class="cal-year-ext-holiday-item">
                                <time>{{ $value['day'] }}/{{ $value['month'] }}</time>
                                <span>{{ $value['value'] }}</span></li>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </article>
        <article class="card holiday-card">
            <span class="section-label">ÂM LỊCH</span>
            <h2>Ngày lễ truyền thống</h2>
            @if(!empty($data['yearData']['lunarData']))
                @php $lunarData = @json_decode($data['yearData']['lunarData']); @endphp
                <ul class="cal-year-ext-holiday-list">
                    @foreach($lunarData as $r)
                        @php $value = \App\Helpers\Helpers::parseEvent($r); @endphp
                        @if(!empty($value['day']) && !empty($value['month']) && !empty($value['value']))
                            <li class="cal-year-ext-holiday-item">
                                <time>{{ $value['day'] }}/{{ $value['month'] }}</time>
                                <span>{{ $value['value'] }}</span></li>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </article>
    </section>
    <article class="card seo-analysis"
             aria-labelledby="year-related-title">

        <div class="cms-content">

        <span class="section-label">
            TRA CỨU LIÊN QUAN
        </span>

            <h2 id="year-related-title">
                Tra cứu thêm từ lịch âm năm {{ $year }}
            </h2>

            <p>
                Từ lịch âm năm {{ $year }}, bạn có thể chọn từng tháng
                trong bảng lịch phía trên để xem chi tiết ngày âm dương,
                Can Chi, ngày hoàng đạo – hắc đạo và thông tin của từng ngày.
                Ngoài ra, có thể chuyển sang năm liền trước, năm liền sau
                hoặc sử dụng các công cụ tra cứu lịch khác.
            </p>

            <p class="cms-links">
                <b>Tra cứu tiếp: </b>
                <a href="{{ route('page.cope.show.day', ['day' => (int) date('d'), 'month' => (int) date('m'), 'year' => date('Y')]) }}" title="Âm lịch hôm nay">Âm lịch hôm nay</a> ·
                <a href="{{ route('page.cope.show.month', ['month' => (int) date('m'), 'year' => date('Y')]) }}" title="Lịch âm tháng {{ (int) date('m') }}">Lịch âm tháng {{ (int) date('m') }}</a> ·
                @if(($year + 1) <= 2050)
                    <a href="{{ route('page.cope.show.year', ['year' => (int) date('m'), 'year' => date('Y')]) }}" title="Lịch âm năm {{ $year + 1 }}">Lịch âm năm {{ $year + 1 }}</a> ·
                @endif
                <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">Đổi ngày âm dương</a>
            </p>

            <p class="content-note">
                Thông tin lịch pháp trên trang được trình bày nhằm phục vụ
                nhu cầu tra cứu và tham khảo. Khi cần xem một ngày cụ thể,
                nên mở trang chi tiết của ngày đó để đối chiếu đầy đủ
                các thông tin liên quan.
            </p>

        </div>

    </article>
@endsection

@section('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const toolbar = document.querySelector('.year-controls');
            const yearSelect = document.getElementById('yearSelect');

            if (!toolbar || !yearSelect) {
                return;
            }

            function changeMonth() {
                const year = yearSelect.value;
                const url = toolbar.dataset.url.replace('__YEAR__', year);
                window.location.href = url;
            }

            yearSelect.addEventListener('change', changeMonth);
        });
    </script>
@endsection
