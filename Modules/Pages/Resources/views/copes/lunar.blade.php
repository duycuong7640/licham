@extends('pages::layouts.app')

@section('content')
    @php
        $row = $data['detail'];
        $type = $data['category']['type'];
        $formatDay = strtotime($row['day']);
        $day = date('d', $formatDay);
        $month = date('m', $formatDay);
        $year = date('Y', $formatDay);

        $lDay = strtotime($row['lunarDay']);
        $lunarDay = date('d', $lDay);
        $lunarMonth = date('m', $lDay);
        $lunarYear = date('Y', $lDay);

        $date = \Carbon\Carbon::parse($row['day']);
        $prevUrl = $date->year <= 1900 ? '' : route('page.cope.show.lunar', ['slug' => 'duong-ngay-'.$date->copy()->subDay()->format('d-m-Y')]);
        $nowUrl = $date->year <= 1900 ? '' : route('page.cope.show.lunar', ['slug' => 'duong-ngay-'.$date->format('d-m-Y')]);
        $nextUrl = $date->year >= 2500 ? '' : route('page.cope.show.lunar', ['slug' => 'duong-ngay-'.$date->copy()->addDay()->format('d-m-Y')]);
    @endphp
    @push('schema')
        @include('pages::elements.extend.page-schema', [
            'schemaType' => 'WebPage',
            'sectionName' => 'Lịch âm dương',
            'sectionUrl' => url('/lich-am-duong'),
            'currentName' => 'Lịch âm ngày ' . (int) $day
                . ' tháng ' . (int) $month
                . ' năm ' . $year,
            'aboutName' => 'Ngày ' . $day . '/' . $month . '/' . $year
                . ' dương lịch, ngày '
                . $lunarDay . '/' . $lunarMonth
                . ' âm lịch',
        ])
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Tra cứu lịch âm dương</p>
            <h1>
                Lịch âm ngày {{ (int) $day }} tháng {{ (int) $month }} năm {{ $year }}
            </h1>
            <p>
                Ngày {{ $day }}/{{ $month }}/{{ $year }} dương lịch nhằm ngày {{ $lunarDay }}/{{ $lunarMonth }} năm {{ $row['strYear'] }}. Đây là ngày {{ $row['strDay'] }}, {{ $row['isDay'] ? 'Hoàng đạo' : 'Hắc đạo' }}.
            </p>
        </div>

        <div class="wrap-info-year">
            <ul>
                <li>
                    <span>Giờ hiện tại:</span>
                    {{ \App\Helpers\Helpers::getCurrentCanChiHour() }}
                </li>
                <li>
                    <span>Dương lịch:</span>
                    {{ !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'GREGORIAN_CALENDAR') : '' }}
                </li>
                <li>
                    <span>Âm lịch hôm nay:</span>
                    {{ !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'LUNAR_CALENDAR') : '' }}
                </li>
                <li>
                    <span>Ngày:</span>
                    {{ $row['isDay'] ? 'Hoàng đạo' : 'Hắc đạo' }}
                </li>
                <li>
                    <span>Trực:</span>
                    {{ !empty($row['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['DAY_OFFICER'], 'DAY_OFFICER', false) : '' }}
                </li>
                <li>
                    <span>Tiết khí:</span>
                    {{ !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'SOLAR_SEASONS', false) : '' }}
                </li>
                <li>
                    <span>Giờ tốt cho mọi việc:</span>
                    @if(!empty($row['options']['AUSPICIOUS_HOUR']))
                        @php
                            $arrTime = [];
                        @endphp
                        @foreach($row['options']['AUSPICIOUS_HOUR'] as $kv=>$value)
                            @php
                                $time = \App\Helpers\Helpers::matchHour($value['value']);
                                $arrTime[] = (!empty($time['title']) ? $time['title'] : '').' '.(!empty($time['hour']) ? '('.$time['hour'].')' : '');
                            @endphp
                        @endforeach
                        {{ implode(', ', $arrTime) }}
                    @endif
                </li>
            </ul>
        </div>

        @include('pages::copes.elements.tab')

        <form class="date-lookup" action="" method="get" aria-label="Chọn ngày cần xem">
            <label>
                <span>Ngày</span>
                <select aria-label="Ngày" id="daySelect">
                    <option selected>{{ $day }}</option>
                </select>
            </label>
            <label>
                <span>Tháng</span>
                <select aria-label="Tháng" id="monthSelect">
                    <option selected>{{ $month }}</option>
                </select>
            </label>
            <label>
                <span>Năm</span>
                <select aria-label="Năm" id="yearSelect" data-current-week="{{ $year }}">
                    @for($i = 1900; $i <= 2050; $i ++)
                        <option value="{{ $i }}" @if($year == $i) selected @endif>{{ $i }}</option>
                    @endfor
                </select>
            </label>
            <button type="button" id="btnViewDate">Xem ngày</button>
        </form>

        <div class="lunar-calendar-container">
            <div class="lunar-calendar-header">
                <div class="lunar-calendar-title">ÂM LỊCH HÔM NAY NGÀY {{ $lunarDay }}/{{ $lunarMonth }}/{{ $lunarYear }}</div>
            </div>

            <div class="lunar-calendar-body">
                <a href="{{ $prevUrl }}" class="lunar-calendar-nav lunar-calendar-prev" id="lunar-btn-prev">
                    <span class="lunar-calendar-icon-arrow">&#10094;</span>
                    <span class="lunar-calendar-spinner"></span>
                </a>
                <a href="{{ $nextUrl }}" class="lunar-calendar-nav lunar-calendar-next" id="lunar-btn-next">
                    <span class="lunar-calendar-icon-arrow">&#10095;</span>
                    <span class="lunar-calendar-spinner"></span>
                </a>

                <div class="lunar-calendar-column lunar-calendar-border-right">
                    <div class="lunar-calendar-sub-header">
                        <span>☀️</span> Dương lịch
                    </div>
                    <div class="lunar-calendar-main-content">
                        <div class="lunar-calendar-month-year">Tháng {{ $month }} năm {{ $year }}</div>
                        <div class="lunar-calendar-big-day">{{ $day }}</div>
                    </div>
                    <div class="lunar-calendar-footer">
                        <strong>Thứ Năm</strong>
                    </div>
                </div>

                <div class="lunar-calendar-column">
                    <div class="lunar-calendar-sub-header">
                        <span>🌙</span> Âm lịch
                    </div>
                    <div class="lunar-calendar-main-content">
                        <div class="lunar-calendar-month-year">Tháng {{ $lunarMonth }} năm {{ $row['strYear'] }}</div>
                        <div class="lunar-calendar-big-day">{{ $lunarDay }}</div>
                    </div>
                    <div class="lunar-calendar-footer">
                        Ngày: <b>{{ $row['strDay'] }}</b>, Tháng: <b>{{ $row['strMonth'] }}</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="responsive-table">
            <table>
                <tbody>
                <tr>
                    <th class="lunar-tb-content lunar-tb-content-bold">Ngày</th>
                    <th class="lunar-tb-content lunar-tb-content-bold">Tháng</th>
                    <th class="lunar-tb-content lunar-tb-content-bold">Năm</th>
                </tr>
                <tr>
                    <td class="lunar-tb-content">{{ $row['strDay'] }}</td>
                    <td class="lunar-tb-content">{{ $row['strMonth'] }}</td>
                    <td class="lunar-tb-content">{{ $row['strYear'] }}</td>
                </tr>
                <tr>
                    <td class="lunar-tb-content">{{ \App\Helpers\Helpers::getNapAm($row['strDay']) }}</td>
                    <td class="lunar-tb-content">{{ \App\Helpers\Helpers::getNapAm($row['strMonth']) }}</td>
                    <td class="lunar-tb-content">{{ \App\Helpers\Helpers::getNapAm($row['strYear']) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <section class="day-table-section mb-5" aria-labelledby="tableTitle">
            <div class="section-title-row day-table-heading">
                <div>
                    <p class="eyebrow">Bảng tra cứu</p>
                    <h2 id="tableTitle">Thông tin tốt xấu ngày {{ $day }}/{{ $month }}/{{ $year }}</h2>
                </div>
            </div>

            <div class="info-table-grid">
                @if(!empty($row['options']['LUNAR']))
                    <article class="info-table-card wide">
                        <h3>Lịch âm dương</h3>
                        <div class="responsive-table @if(!empty($row['options']['LUNAR'])) mb-4 @endif">
                            <table>
                                <tbody>
                                @foreach($row['options']['LUNAR'] as $value)
                                    <tr>
                                        <th><strong>{{ dataKey::COPE_KEYS[$value['key']] }}</strong></th>
                                        <td>{{ $value['value'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(!empty($row['options']['SUMMER']))
                            <div class="responsive-table">
                                @php
                                    $summers = [];
                                    $current = null;

                                    foreach ($row['options']['SUMMER'] ?? [] as $item) {
                                        if ($item['key'] === 'SUMMER_REGION') {
                                            if (!empty($current)) {
                                                $summers[] = $current;
                                            }

                                            $current = [
                                                'SUMMER_REGION' => $item['value'],
                                                'items' => [],
                                            ];

                                            continue;
                                        }

                                        if (!empty($current)) {
                                            $current['items'][] = $item;
                                        }
                                    }

                                    if (!empty($current)) {
                                        $summers[] = $current;
                                    }
                                @endphp
                                <table>
                                    <tbody>
                                    <tr>
                                        <th><strong>Mặt trời</strong></th>
                                        <th><strong>Giờ mọc</strong></th>
                                        <th><strong>Giờ lặn</strong></th>
                                    </tr>
                                    @foreach($summers as $values)
                                        <tr>
                                            <td>{{ $values['SUMMER_REGION'] }}</td>
                                            @foreach($values['items'] as $value)
                                                <td>{{ $value['value'] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </article>
                @endif

                @if(!empty($row['options']['BASIC_STEP']))
                    <article class="info-table-card wide">
                        <h3>Các bước xem ngày lành theo Lịch Vạn Niên {{ date('Y') }}</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['BASIC_STEP'] as $value)
                                    @php
                                        $step = explode('(//)', $value['value']);
                                    @endphp
                                    @if(!empty($step[0]))
                                        <tr>
                                            <th><strong>{{ $step[0] }}</strong></th>
                                            <td>{{ $step[1] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['AUSPICIOUS_HOUR']))
                    <article class="info-table-card wide">
                        <h3>Giờ Hoàng đạo (Giờ lành)</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach(array_chunk($row['options']['AUSPICIOUS_HOUR'], 2) as $hours)
                                    <tr>
                                        @foreach($hours as $value)
                                            <td>{{ $value['value'] }}</td>
                                        @endforeach

                                        @if(count($hours) < 2)
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['DARK_HOUR']))
                    <article class="info-table-card wide">
                        <h3>Giờ Hắc đạo (Giờ xấu)</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach(array_chunk($row['options']['DARK_HOUR'], 2) as $hours)
                                    <tr>
                                        @foreach($hours as $value)
                                            <td>{{ $value['value'] }}</td>
                                        @endforeach

                                        @if(count($hours) < 2)
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['FIVE_ELEMENTS']))
                    <article class="info-table-card wide">
                        <h3>Ngũ hành</h3>
                        <div class="responsive-table">
                            <div class="wrap-content">
                                {!! !empty($row['options']['FIVE_ELEMENTS']) ? \App\Helpers\Helpers::execTagPContent(\App\Helpers\Helpers::getCopeValueByOption($row['options']['FIVE_ELEMENTS'], 'FIVE_ELEMENTS')) : '' !!}
                            </div>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['FIVE_ELEMENTS']))
                    <article class="info-table-card wide">
                        <h3>Xem ngày lành, xấu theo trực</h3>
                        <div class="responsive-table">
                            <div class="wrap-content">
                                {!! !empty($row['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['DAY_OFFICER'], 'DAY_OFFICER', false) : '' !!}
                            </div>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['INCOMPATIBLE_AGES']))
                    <article class="info-table-card wide">
                        <h3>Tuổi xung khắc</h3>
                        <div class="responsive-table">
                            <div class="wrap-content">
                                {!! !empty($row['options']['INCOMPATIBLE_AGES']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['INCOMPATIBLE_AGES'], 'INCOMPATIBLE_AGES', false) : '' !!}
                            </div>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['GOOD_STAR']))
                    <article class="info-table-card wide">
                        <h3>Sao tốt (Theo Ngọc hạp thông thư)</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['GOOD_STAR'] as $value)
                                    <tr>
                                        <td>{{ strip_tags($value['value']) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['BAD_STAR']))
                    <article class="info-table-card wide">
                        <h3>Sao xấu (Theo Ngọc hạp thông thư)</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['BAD_STAR'] as $value)
                                    <tr>
                                        <td>{{ strip_tags($value['value']) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['TABOO_DAY']))
                    <article class="info-table-card wide">
                        <h3>Ngày kỵ</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['TABOO_DAY'] as $value)
                                    <tr>
                                        <td class="wrap-content">{{ strip_tags($value['value']) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['DIRECTION_OF_TRAVEL']))
                    <article class="info-table-card wide">
                        <h3>Hướng xuất hành</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['DIRECTION_OF_TRAVEL'] as $value)
                                    <tr>
                                        <td class="wrap-content">{!! $value['value'] !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['LUNAR_MANSION']))
                    <article class="info-table-card wide">
                        <h3>Ngày tốt theo Nhị thập bát tú</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['LUNAR_MANSION'] as $value)
                                    <tr>
                                        <td class="wrap-content">{!! $value['value'] !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['HUMAN_DEITIES']))
                    <article class="info-table-card wide">
                        <h3>Nhân thần</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['HUMAN_DEITIES'] as $value)
                                    <tr>
                                        <td class="wrap-content">{!! $value['value'] !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['FETAL_GOD']))
                    <article class="info-table-card wide">
                        <h3>Thai thần</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['FETAL_GOD'] as $value)
                                    @php $fetalGod = \App\Helpers\Helpers::splitThaiThanContent($value['value']); @endphp
                                    <tr>
                                        <th>{!! $fetalGod['title'] !!}</th>
                                        <td class="wrap-content">{!! $fetalGod['content'] !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['LY_THUAN_PHONG_DEPARTURE_HOURS']))
                    <article class="info-table-card wide">
                        <h3>Giờ xuất hành theo Lý Thuần Phong</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['LY_THUAN_PHONG_DEPARTURE_HOURS'] as $value)
                                    @php
                                        $step = explode('(//)', $value['value']);
                                    @endphp
                                    @if(!empty($step[0]))
                                        <tr>
                                            <th><strong>{!! $step[0] !!}</strong></th>
                                            <td>{!! $step[1] !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['KONG_MING_FORTUNE_DAY']))
                    <article class="info-table-card wide">
                        <h3>Ngày xuất hành theo Khổng Minh</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['KONG_MING_FORTUNE_DAY'] as $value)
                                    <tr>
                                        <td class="wrap-content">{!! $value['value'] !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif
            </div>
        </section>

        <section class="category-card reveal">
            <div class="category-head"><h2>Xem lịch theo ngày</h2></div>
            @php
                $currentDate = \Carbon\Carbon::createFromFormat('d/m/Y', $day.'/'.$month.'/'.$year);
            @endphp
            <div class="simple-links">
                @for ($i = -3; $i <= 4; $i++)
                    @php
                        $date = $currentDate->copy()->addDays($i);
                        $daySlug = $date->format('d-m-Y');
                    @endphp

                    <a href="{{ route('page.cope.show.day', ['day' => $daySlug]) }}"
                       title="Xem lịch âm dương ngày {{ $date->format('d/m/Y') }}">
                        Xem ngày tốt {{ $date->format('d-m-Y') }} <span>›</span>
                    </a>
                @endfor
            </div>
        </section>

        <section class="category-card reveal">
            <div class="category-head"><h2>Xem Lịch theo năm</h2></div>
            <div class="simple-links">
                @for ($y = $year - 3; $y <= $year + 4; $y++)
                    <a href="{{ route('page.cope.show.year', ['year' => $y]) }}" title="Xem lịch âm dương năm {{ $y }}" {{ ($data['year'] ?? date('Y')) == $y ? 'cal-year-ext-btn-active' : '' }}">
                        Lịch âm dương năm {{ $y }} <span>›</span>
                    </a>
                @endfor
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const btnPrev = document.getElementById("lunar-btn-prev");
            const btnNext = document.getElementById("lunar-btn-next");

            function handleCalendarNavigation(event, element) {
                event.preventDefault();

                const targetUrl = element.getAttribute("href");

                if (!targetUrl || targetUrl === "$prevUrl" || targetUrl === "$nextUrl") {
                    console.error("Lỗi: Bạn chưa thay đổi biến $prevUrl hoặc $nextUrl thành URL thật!");
                    alert("Tính năng chuyển trang đang chạy thử nghiệm (Chưa gắn link thật).");
                    return;
                }

                btnPrev.classList.add("lunar-disabled");
                btnNext.classList.add("lunar-disabled");

                element.classList.add("lunar-loading");

                setTimeout(function() {
                    window.location.href = targetUrl;
                }, 400);

                setTimeout(function() {
                    btnPrev.classList.remove("lunar-disabled", "lunar-loading");
                    btnNext.classList.remove("lunar-disabled", "lunar-loading");
                    console.log("Hết thời gian chờ, hệ thống tự động mở khóa lại các nút điều hướng.");
                }, 5000);
            }

            if (btnPrev) {
                btnPrev.addEventListener("click", function (e) {
                    handleCalendarNavigation(e, this);
                });
            }

            if (btnNext) {
                btnNext.addEventListener("click", function (e) {
                    handleCalendarNavigation(e, this);
                });
            }
        });


        document.addEventListener('DOMContentLoaded', function () {
            const daySelect = document.getElementById('daySelect');
            const monthSelect = document.getElementById('monthSelect');
            const yearSelect = document.getElementById('yearSelect');

            function renderDays() {
                const selectedDay = parseInt(daySelect.value || 1);
                const month = parseInt(monthSelect.value || 1);
                const year = parseInt(yearSelect.value || new Date().getFullYear());

                const daysInMonth = new Date(year, month, 0).getDate();

                daySelect.innerHTML = '';

                for (let day = 1; day <= daysInMonth; day++) {
                    const option = document.createElement('option');

                    option.value = day;
                    option.textContent = day;

                    if (day === Math.min(selectedDay, daysInMonth)) {
                        option.selected = true;
                    }

                    daySelect.appendChild(option);
                }
            }

            function renderMonths() {
                const selectedMonth = parseInt(monthSelect.value || 1);

                monthSelect.innerHTML = '';

                for (let month = 1; month <= 12; month++) {
                    const option = document.createElement('option');

                    option.value = month;
                    option.textContent = month;

                    if (month === selectedMonth) {
                        option.selected = true;
                    }

                    monthSelect.appendChild(option);
                }
            }

            renderMonths();
            renderDays();

            monthSelect.addEventListener('change', renderDays);
            yearSelect.addEventListener('input', renderDays);
        });

        document.getElementById('btnViewDate').addEventListener('click', function () {
            const day = document.getElementById('daySelect').value.padStart(2, '0');
            const month = document.getElementById('monthSelect').value.padStart(2, '0');
            const year = document.getElementById('yearSelect').value;

            const date = `${day}-${month}-${year}`;

            let url = @json(route('page.cope.show.lunar', ['slug' => 'duong-ngay-__DATE__']));
            url = url.replace('__DATE__', date);

            window.location.href = url;
        });
    </script>
@endsection
