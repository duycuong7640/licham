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
            <strong>12</strong>
            <span>Tháng trong năm</span>
        </div>
        <div>
            <strong>365</strong><span>Ngày dương lịch</span>
        </div>
        <div>
            <strong>{{ $strYear }}</strong>
            <span>Can Chi năm</span>
        </div>
    </div>
    <section class="year-calendar-section" aria-labelledby="year-calendar-title">
        <div class="year-section-head">
            <div>
                <span class="section-label">TỔNG QUAN 12 THÁNG</span>
                <h2 id="year-calendar-title">Lịch cả năm</h2>
            </div>
            <div class="legend">
                <span><i></i>Ngày tốt</span>
                <span><i class="bad-dot"></i>Ngày xấu</span>
            </div>
        </div>
        <div class="year-overview">
            @foreach($data['calendar'] as $monthNum => $monthData)
                <section class="mini-month">
                    <h2>
                        <a href="{{ route('page.cope.show.month', ['month' => \App\Helpers\Helpers::checkNumber($monthData['month']), 'year' => $monthData['year']]) }}"
                           title="Tháng {{ $monthData['month'] }}">
                            Tháng {{ $monthData['month'] }}
                        </a>
                    </h2>
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
                                <a href="" class="{{ $isDay }} {{ $today }}"
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
    <section class="section-block">
        <article class="card month-day-list good-list">
            <span class="section-label">LỊCH ÂM NĂM KHÁC</span>
            <h2>Xem lịch âm các năm khác</h2>
            <div id="goodDays" class="date-link-grid">
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
    <section class="section-block holiday-grid" aria-label="Ngày lễ nổi bật">
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
    <section
        class="card year-events section-block"
        aria-labelledby="year-events-title"
    >
        <div class="year-section-head">
            <div>
                <span class="section-label">TRA CỨU THEO THÁNG</span>
                <h2 id="year-events-title">Danh sách sự kiện theo tháng</h2>
            </div>
            <p>Phân biệt ngày âm và ngày dương để dễ đối chiếu.</p>
        </div>
        <div id="yearEvents" class="year-event-grid"></div>
    </section>
    <article class="card year-seo section-block">
        <span class="section-label">TÌM HIỂU LỊCH NĂM</span>
        <h2 id="yearSeoTitle">Lịch âm năm 2026 và cách tra cứu</h2>
        <p>
            Trang lịch năm giúp người dùng quan sát nhanh toàn bộ 12 tháng, so
            sánh vị trí các ngày âm và đi sâu vào từng tháng khi cần. Mỗi ngày có
            liên kết tới trang thông tin chi tiết để xem Can Chi, giờ hoàng đạo và
            dữ liệu xuất hành.
        </p>
        <p>
            Khi chọn một năm khác, toàn bộ 12 bảng tháng sẽ được cập nhật đồng
            thời. Dữ liệu ngày lễ và Can Chi trong bản demo sẽ được thay thế bằng
            nội dung do API và CMS cung cấp.
        </p>
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
