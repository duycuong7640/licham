@extends('pages::layouts.master')

@section('content')
    <header class="card convert-heading">
        <span class="section-label">CÔNG CỤ LỊCH VIỆT</span>
        <h1>Đổi Ngày Dương Sang Âm, Âm Sang Dương</h1>
        <p>
            Chuyển đổi ngày dương sang âm hoặc ngày âm sang dương,
            đồng thời tra cứu Can Chi, tiết khí, giờ hoàng đạo
            và các thông tin lịch pháp của ngày đã chọn.
        </p>
    </header>

    @php
        $row = $data['day'];
        $fD = explode('-', $row['day']);
        $d = (int) $fD[2];
        $m = (int) $fD[1];
        $y = (int) $fD[0];
        $flD = explode('-', $row['lunarDay']);
        $lunarDay = (int) $flD[2];
        $lunarMonth = (int) $flD[1];
        $lunarYear = (int) $flD[0];
        $thu = \App\Helpers\Helpers::formatVietnameseDateNumber($row['day']);
        $tietkhi = !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'SOLAR_SEASONS') : '';
        $dayKhongMinh = !empty($row['options']['KONG_MING_FORTUNE_DAY'][0]) ? \App\Helpers\Helpers::getKongMingFortune($row['options']['KONG_MING_FORTUNE_DAY'][0]['value']) : [];
        $schemaName = "Đổi ngày âm dương";
    @endphp
    @push('schema')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'CollectionPage',
                        '@id' => $data['seo']['canonical'] . '#webpage',
                        'url' => $data['seo']['canonical'],
                        'name' => $schemaName,
                        'description' => $data['seo']['meta_des'],
                        'inLanguage' => 'vi-VN',
                        'breadcrumb' => [
                            '@id' => $data['seo']['canonical'] . '#breadcrumb',
                        ],
                        'about' => [
                            '@type' => 'Thing',
                            'name' => $data['seo']['name'],
                        ],
                    ],
                    [
                        '@type' => 'BreadcrumbList',
                        '@id' => $data['seo']['canonical'] . '#breadcrumb',
                        'itemListElement' => [
                            [
                                '@type' => 'ListItem',
                                'position' => 1,
                                'name' => 'Trang chủ',
                                'item' => route('page.home'),
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 2,
                                'name' => "Đổi ngày âm dương",
                                'item' => route('page.cate.index', ['slug' => 'doi-ngay-am-duong']),
                            ]
                        ],
                    ],
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
    <section class="convert-workspace">
        <div class="card convert-form-card">
            <div class="convert-form-head">
                <span>BƯỚC 1</span>
                <div>
                    <h2>Chọn loại lịch và ngày</h2>
                    <p>Nhập ngày bạn muốn tra cứu.</p>
                </div>
            </div>
            <form method="post" action="" id="pre_convertForm">
                @csrf()
                <fieldset class="field convert-type-field">
                    <label>Hình thức chuyển đổi</label>
                    <div class="convert-type-options">
                        <label class="convert-type-option">
                            <input
                                type="radio"
                                name="convertType"
                                value="solar"
                                @if(!empty($data['params']['convertType']) && $data['params']['convertType'] == 'solar')
                                    checked
                                @endif
                                @if(empty($data['params']['convertType']))
                                    checked
                                @endif
                            />
                            <span>Dương sang Âm</span>
                        </label>
                        <label class="convert-type-option">
                            <input
                                type="radio"
                                name="convertType"
                                value="lunar"
                                @if(!empty($data['params']['convertType']) && $data['params']['convertType'] == 'lunar')
                                    checked
                                @endif
                            />
                            <span>Âm sang Dương</span>
                        </label>
                    </div>
                </fieldset>
                <div id="pre_solarFields" class="convert-fields">
                    <div class="field convert-field-wide">
                        <label for="pre_convertDate">Chọn ngày dương lịch</label>
                        <input type="hidden" name="convertedDate" id="pre_convertedDate">
                        <input
                            id="pre_convertDate"
                            class="vietnamese-date-input"
                            type="text"
                            name="date"
                            value="{{ !empty($data['params']['date']) ? $data['params']['date'] : $d.'/'.$m.'/'.$y }}"
                            placeholder="dd/mm/YYYY"
                            inputmode="numeric"
                            maxlength="10"
                            pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"
                            title="Nhập ngày theo định dạng dd/mm/YYYY"
                            autocomplete="off"
                            data-min-year="1900"
                            data-max-year="2050"
                        />
                    </div>
                </div>
                <div id="pre_lunarFields" class="convert-fields lunar-fields" hidden>
                    <div class="field">
                        <label for="pre_lunarInputDay">Ngày âm</label>
                        <select id="pre_lunarInputDay"></select>
                    </div>
                    <div class="field">
                        <label for="pre_lunarInputMonth">Tháng âm</label>
                        <select id="pre_lunarInputMonth"></select>
                    </div>
                    <div class="field">
                        <label for="pre_lunarInputYear">Năm</label>
                        <select id="pre_lunarInputYear"></select>
                    </div>
                </div>
                <button class="btn convert-submit" type="submit">
                    Chuyển đổi ngày
                </button>
            </form>
            <p class="convert-hint">
                Hỗ trợ chuyển đổi lịch âm dương Việt Nam trong khoảng từ năm 1900 đến 2050.
            </p>
        </div>

        <section
            id="pre_convertResult"
            class="card convert-result"
            aria-live="polite"
        >
            <div class="convert-result-head">
                <span>KẾT QUẢ</span><strong>{{ $thu }}, {{ $row['d'] }} tháng {{ $row['m'] }} năm {{ $y }}</strong>
            </div>
            <div class="conversion-pair">
                <div>
                    <small>DƯƠNG LỊCH</small>
                    <strong id="pre_resultSolarDay">{{ $d }}</strong>
                    <span id="pre_resultSolarMeta">Tháng {{ $m }} năm {{ $y }}</span>
                </div>
                <span class="conversion-arrow" aria-hidden="true">⇄</span>
                <div>
                    <small>ÂM LỊCH</small>
                    <strong id="pre_resultLunarDay">{{ $lunarDay }}</strong>
                    <span id="pre_resultLunarMeta">Tháng {{ $lunarMonth }} năm {{ $row['strYear'] }}</span>
                </div>
            </div>
            <dl class="conversion-facts">
                <div>
                    <dt>Can Chi</dt>
                    <dd id="pre_resultCanChi">
                        Ngày {{ $row['strDay'] }} · Tháng {{ $row['strMonth'] }} · Năm {{ $row['strYear'] }}
                    </dd>
                </div>
                <div>
                    <dt>Tiết khí</dt>
                    <dd>{{ $tietkhi }}</dd>
                </div>
                <div class="fact-travel">
                    <dt>Ngày xuất hành</dt>
                    <dd id="pre_resultTravel">
                        Ngày <strong>{{ !empty($dayKhongMinh[0]) ? $dayKhongMinh[0] : '' }}</strong>: {{ !empty($dayKhongMinh[1]) ? $dayKhongMinh[1] : '' }}
                    </dd>
                </div>
                <div class="fact-good-hours">
                    <dt>Giờ hoàng đạo</dt>
                    <dd id="pre_resultHours">
                        @if(!empty($row['options']['AUSPICIOUS_HOUR']))
                            @foreach($row['options']['AUSPICIOUS_HOUR'] as $k=>$value)
                                @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                                @if($k), @endif
                                {{ !empty($time['title']) ? $time['title'] : '' }}
                                ({{ !empty($time['hour']) ? $time['hour'] : '' }})
                            @endforeach
                        @endif
                    </dd>
                </div>
                <div class="fact-bad-hours">
                    <dt>Giờ hắc đạo</dt>
                    <dd id="pre_resultBadHours">
                        @if(!empty($row['options']['DARK_HOUR']))
                            @foreach($row['options']['DARK_HOUR'] as $k=>$value)
                                @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                                @if($k), @endif
                                {{ !empty($time['title']) ? $time['title'] : '' }}
                                ({{ !empty($time['hour']) ? $time['hour'] : '' }})
                            @endforeach
                        @endif
                    </dd>
                </div>
            </dl>
            <a class="result-detail-link"
               href="{{ route('page.cope.show.day', ['day' => $d, 'month' => $m, 'year' => $y]) }}"
               aria-label="Ngày {{ $d }} tháng {{ $m }}, âm lịch {{ $lunarDay }} tháng {{ $lunarMonth }}"
               title="Ngày {{ $d }} tháng {{ $m }}, âm lịch {{ $lunarDay }} tháng {{ $lunarMonth }}"
            >
                Xem luận giải chi tiết ngày này →
            </a>
        </section>
    </section>

    @php
        $months = \App\Helpers\Helpers::buildCalendar($data['months']);
        $rowDay = $data['day'];
        $todayDate = $data['day']['day'];
    @endphp
    <section class="section-block card calendar-card" id="lich-doi-chieu">
        <div class="section-head">
            <div>
                <span class="section-label">ĐỐI CHIẾU THEO THÁNG</span>
                <h2 id="pre_monthTitle">Tháng {{ $m }} năm {{ $y }}</h2>
            </div>
        </div>
        <div class="weekdays">
            <div>Thứ 2</div>
            <div>Thứ 3</div>
            <div>Thứ 4</div>
            <div>Thứ 5</div>
            <div>Thứ 6</div>
            <div>Thứ 7</div>
            <div>CN</div>
        </div>
        <div id="pre_calendar" class="calendar-grid">
            @foreach($months as $values)
                @foreach($values as $month)
                    @if(empty($month['id']))
                        <button class="calendar-day muted" disabled="" aria-disabled="true" aria-label="">
                            <strong></strong>
                            <small></small>
                        </button>
                    @else
                        @php
                            $isDay = $month['isDay'] ? 'good-day' : 'bad-day';
                            $isSaturday = $month['isSaturday'] ? 'weekend' : '';
                            $isSunday = $month['isSunday'] ? 'weekend' : '';
                            $isViewDay = $month['date'] == $todayDate ? 'viewDay' : '';
                            $today = $month['date'] == date('Y-m-d') ? 'selected today' : '';
                            $dayOffices = !empty($month['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption2($month['options']['DAY_OFFICER'], 'DAY_OFFICER') : '';
                            $montViecnenlam = $dayOffices ? explode('. ', \App\Helpers\Helpers::getContentInBrackets($dayOffices)) : [];
                            $month['day'] = \App\Helpers\Helpers::checkNumber($month['day']);
                            $month['month'] = \App\Helpers\Helpers::checkNumber($month['month']);
                            $month['lunarDay'] = \App\Helpers\Helpers::checkNumber($month['lunarDay']);
                            $month['lunarMonth'] = \App\Helpers\Helpers::checkNumber($month['lunarMonth']);
                        @endphp
                        <a href="{{ route('page.cope.show.day', ['day' => $month['day'], 'month' => $month['month'], 'year' => $month['year']]) }}"
                           class="calendar-day btn-loading {{ $isDay }} {{ $isSaturday }} {{ $isSunday }} {{ $today }} {{ $isViewDay }}"
                           data-date="{{ \Carbon\Carbon::parse($month['date'], 'Asia/Ho_Chi_Minh')->utc()->format('Y-m-d\TH:i:s.v\Z') }}"
                           aria-label="Ngày {{ $month['day'] }} tháng {{ $month['month'] }}, âm lịch {{ $month['lunarDay'] }} tháng {{ $month['lunarMonth'] }}"
                           title="Ngày {{ $month['day'] }} tháng {{ $month['month'] }}, âm lịch {{ $month['lunarDay'] }} tháng {{ $month['lunarMonth'] }}"
                        >
                            <strong>{{ $month['day'] }}</strong>
                            <small>{{ $month['lunarDay'] }}</small>
                            @if(!empty($today))
                                <em>Hôm nay</em>
                            @endif
                            <span class="day-tooltip" role="tooltip">
                                <b>Dương: {{ $month['day'] }}/{{ $month['month'] }}/{{ $month['year'] }} · Âm {{ $month['lunarDay'] }}/{{ $month['lunarMonth'] }}</b>
                                <span><strong>Can Chi:</strong> {{ $month['strDay'] }}</span>
                                <span><strong>Đánh giá:</strong> Ngày {{ $month['isDay'] ? 'Hoàng đạo' : 'Hắc đạo' }}</span>
                                <span>
                                    <strong>Giờ tốt:</strong>
                                    @if(!empty($month['options']['AUSPICIOUS_HOUR']))
                                        @foreach($month['options']['AUSPICIOUS_HOUR'] as $k=>$value)
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
            @endforeach
        </div>
        <div class="legend">
            <span><i class="today-dot"></i> Ngày đang chọn</span>
            <span><i></i> Ngày Hoàng đạo</span>
            <span><i class="bad-dot"></i> Ngày Hắc đạo</span>
            <span>Ngày âm ở góc phải</span>
            <span>Di chuột hoặc chạm để xem nhanh</span>
        </div>
    </section>

    <section class="section-block convert-guide-grid" id="huong-dan">
        <article class="card convert-guide">
            <span class="section-label">HƯỚNG DẪN TRA CỨU</span>
            <h2>Cách đổi ngày dương sang âm và âm sang dương</h2>
            <p>
                Chọn hình thức chuyển đổi, nhập ngày cần tìm rồi nhấn
                <b>Chuyển đổi ngày</b>. Kết quả sẽ cho biết ngày tương ứng theo loại
                lịch còn lại cùng các thông tin lịch pháp quan trọng.
            </p>
            <h3>Khi nào cần đổi ngày âm dương?</h3>
            <p>
                Công cụ thường được dùng để tra ngày giỗ, ngày lễ truyền thống, ngày
                sinh âm lịch, Tết Nguyên đán hoặc đối chiếu một ngày dương lịch với
                lịch vạn niên.
            </p>
            <h3>Kết quả chuyển đổi gồm những gì?</h3>
            <p>
                Ngoài ngày âm và ngày dương, trang còn bố trí vị trí cho thứ trong
                tuần, Can Chi ngày tháng năm, tiết khí, ngày xuất hành và các khung
                giờ hoàng đạo.
            </p>
            <div class="guide-note">
                Thông tin tốt xấu mang giá trị văn hóa và tham khảo. Những quyết
                định quan trọng vẫn nên dựa trên điều kiện thực tế.
            </div>
        </article>
        <aside class="card convert-faq">
            <span class="section-label">CÂU HỎI THƯỜNG GẶP</span>
            <h2>Hiểu đúng về đổi lịch</h2>
            <details>
                <summary>Âm lịch Việt Nam có phải chỉ dựa vào Mặt Trăng?</summary>
                <p>
                    Âm lịch Việt Nam thực chất là âm dương lịch: tháng dựa theo tuần
                    trăng và có tháng nhuận để phù hợp với chu kỳ năm Mặt Trời.
                </p>
            </details>
            <details>
                <summary>Vì sao có tháng âm lịch 29 hoặc 30 ngày?</summary>
                <p>
                    Độ dài tháng được xác định theo chu kỳ trăng non kế tiếp nên tháng
                    âm lịch có thể là tháng thiếu 29 ngày hoặc tháng đủ 30 ngày.
                </p>
            </details>
            <details>
                <summary>Đổi ngày âm có cần chọn tháng nhuận?</summary>
                <p>
                    Có. Nếu ngày cần đổi thuộc tháng nhuận, cần đánh dấu đúng để kết
                    quả dương lịch không bị sai lệch.
                </p>
            </details>
            <div class="convert-related">
                <strong>Tra cứu liên quan</strong>
                <a href="{{ route('page.cope.show.day', ['day' => $d, 'month' => $m, 'year' => $y]) }}" title="Âm lịch ngày {{ $d }} tháng {{ $m }}">Âm lịch ngày {{ $d }} tháng {{ $m }}</a>
                <a href="{{ route('page.cope.show.month', ['month' => $m, 'year' => $y]) }}" title="Lịch âm tháng {{ $m }}">Lịch âm tháng {{ $m }}</a>
                <a href="{{ route('page.cope.show.year', ['year' => $y]) }}" title="Lịch âm năm {{ $y }}">Lịch âm năm {{ $y }}</a>
            </div>
        </aside>
    </section>
    <article class="card seo-analysis" aria-labelledby="convert-seo-title">
        <div class="cms-content">
            <span class="section-label">KIẾN THỨC ÂM DƯƠNG LỊCH</span>

            <h2 id="convert-seo-title">
                Đổi ngày âm dương dùng để làm gì?
            </h2>

            <p>
                Đổi ngày âm dương giúp xác định một ngày dương lịch
                tương ứng với ngày nào trong âm lịch Việt Nam,
                hoặc tìm ngày dương từ một ngày âm đã biết.
                Việc đối chiếu này thường được sử dụng khi tra ngày sinh âm lịch,
                ngày giỗ, ngày lễ truyền thống, Tết Nguyên đán
                và các mốc thời gian được ghi theo âm lịch.
            </p>

            <p>
                Khi chuyển đổi từ ngày âm sang ngày dương,
                cần xác định đúng ngày, tháng, năm âm lịch
                và kiểm tra tháng đó có phải tháng nhuận hay không.
                Hai ngày có cùng ngày và tháng âm nhưng khác trạng thái
                tháng thường – tháng nhuận có thể tương ứng
                với những ngày dương lịch khác nhau.
            </p>

            <p>
                Sau khi chuyển đổi, bạn có thể tiếp tục xem
                thông tin chi tiết của ngày tương ứng,
                đối chiếu toàn bộ tháng hoặc tra cứu lịch của cả năm.
            </p>

            <p class="cms-links">
                <b>Tra cứu tiếp: </b>
                <a href="{{ route('page.home') }}" title="Âm lịch hôm nay">Âm lịch hôm nay</a> ·
                <a href="{{ route('page.cope.show.month', ['month' => (int) date('m'), 'year' => date('Y')]) }}" title="Lịch âm tháng {{ (int) date('m') }}">Lịch âm tháng {{ (int) date('m') }}</a> ·
                <a href="{{ route('page.cope.show.year', ['year' => date('Y')]) }}" title="Lịch âm năm {{ date('Y') }}">Lịch âm năm {{ date('Y') }}</a>
            </p>
        </div>
    </article>
@endsection

@section('scripts')
    <script type="text/javascript">
        const INT = Math.floor;

        function jdFromDate(dd, mm, yy) {
            const a = INT((14 - mm) / 12);
            const y = yy + 4800 - a;
            const m = mm + 12 * a - 3;

            return dd + INT((153 * m + 2) / 5)
                + 365 * y + INT(y / 4)
                - INT(y / 100) + INT(y / 400) - 32045;
        }

        function jdToDate(jd) {
            const a = jd + 32044;
            const b = INT((4 * a + 3) / 146097);
            const c = a - INT(b * 146097 / 4);
            const d = INT((4 * c + 3) / 1461);
            const e = c - INT(1461 * d / 4);
            const m = INT((5 * e + 2) / 153);

            return [
                e - INT((153 * m + 2) / 5) + 1,
                m + 3 - 12 * INT(m / 10),
                b * 100 + d - 4800 + INT(m / 10)
            ];
        }

        function getNewMoonDay(k, tz = 7) {
            const T = k / 1236.85;
            const T2 = T * T;
            const T3 = T2 * T;
            const dr = Math.PI / 180;

            let jd = 2415020.75933 + 29.53058868 * k
                + 0.0001178 * T2 - 0.000000155 * T3;

            jd += 0.00033 * Math.sin(
                (166.56 + 132.87 * T - 0.009173 * T2) * dr
            );

            const M = 359.2242 + 29.10535608 * k
                - 0.0000333 * T2 - 0.00000347 * T3;

            const Mp = 306.0253 + 385.81691806 * k
                + 0.0107306 * T2 + 0.00001236 * T3;

            const F = 21.2964 + 390.67050646 * k
                - 0.0016528 * T2 - 0.00000239 * T3;

            let C = (0.1734 - 0.000393 * T) * Math.sin(M * dr)
                + 0.0021 * Math.sin(2 * M * dr);

            C -= 0.4068 * Math.sin(Mp * dr);
            C += 0.0161 * Math.sin(2 * Mp * dr);
            C -= 0.0004 * Math.sin(3 * Mp * dr);
            C += 0.0104 * Math.sin(2 * F * dr);
            C -= 0.0051 * Math.sin((M + Mp) * dr);
            C -= 0.0074 * Math.sin((M - Mp) * dr);
            C += 0.0004 * Math.sin((2 * F + M) * dr);
            C -= 0.0004 * Math.sin((2 * F - M) * dr);
            C -= 0.0006 * Math.sin((2 * F + Mp) * dr);
            C += 0.0010 * Math.sin((2 * F - Mp) * dr);
            C += 0.0005 * Math.sin((2 * Mp + M) * dr);

            const delta = T < -11
                ? 0.001 + 0.000839 * T + 0.0002261 * T2
                - 0.00000845 * T3 - 0.000000081 * T * T3
                : -0.000278 + 0.000265 * T + 0.000262 * T2;

            return INT(jd + C - delta + 0.5 + tz / 24);
        }

        function getSunLongitude(jdn, tz = 7) {
            const T = (jdn - 2451545.5 - tz / 24) / 36525;
            const T2 = T * T;
            const dr = Math.PI / 180;

            const M = 357.52910 + 35999.05030 * T
                - 0.0001559 * T2 - 0.00000048 * T * T2;

            const L0 = 280.46645 + 36000.76983 * T
                + 0.0003032 * T2;

            const DL = (1.914600 - 0.004817 * T - 0.000014 * T2)
                * Math.sin(dr * M)
                + (0.019993 - 0.000101 * T) * Math.sin(2 * dr * M)
                + 0.000290 * Math.sin(3 * dr * M);

            const L = (L0 + DL) * dr;
            const normalized = L - 2 * Math.PI * INT(L / (2 * Math.PI));

            return INT(normalized / Math.PI * 6);
        }

        function getLunarMonth11(year, tz = 7) {
            const off = jdFromDate(31, 12, year) - 2415021;
            const k = INT(off / 29.530588853);

            let nm = getNewMoonDay(k, tz);

            if (getSunLongitude(nm, tz) >= 9) {
                nm = getNewMoonDay(k - 1, tz);
            }

            return nm;
        }

        function getLeapMonthOffset(a11, tz = 7) {
            const k = INT(
                0.5 + (a11 - 2415021.076998695) / 29.530588853
            );

            let i = 1;
            let last;
            let arc = getSunLongitude(getNewMoonDay(k + i, tz), tz);

            do {
                last = arc;
                i++;
                arc = getSunLongitude(getNewMoonDay(k + i, tz), tz);
            } while (arc !== last && i < 14);

            return i - 1;
        }

        function lunarToSolar(day, month, year, leap) {
            const tz = 7;

            if (
                !Number.isInteger(day) ||
                !Number.isInteger(month) ||
                !Number.isInteger(year) ||
                day < 1 || day > 30 ||
                month < 1 || month > 12 ||
                year < 1900 || year > 2050
            ) {
                return null;
            }

            const a11 = month < 11
                ? getLunarMonth11(year - 1, tz)
                : getLunarMonth11(year, tz);

            const b11 = month < 11
                ? getLunarMonth11(year, tz)
                : getLunarMonth11(year + 1, tz);

            let off = month - 11;
            if (off < 0) off += 12;

            if (b11 - a11 > 365) {
                const leapOff = getLeapMonthOffset(a11, tz);
                let leapMonth = leapOff - 2;

                if (leapMonth < 0) leapMonth += 12;

                if (leap && month !== leapMonth) return null;

                if (leap || off >= leapOff) off++;
            } else if (leap) {
                return null;
            }

            const k = INT(
                0.5 + (a11 - 2415021.076998695) / 29.530588853
            );

            const monthStart = getNewMoonDay(k + off, tz);
            const nextMonthStart = getNewMoonDay(k + off + 1, tz);

            if (day > nextMonthStart - monthStart) {
                return null;
            }

            return jdToDate(monthStart + day - 1);
        }


        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('pre_convertForm');
            if (!form) return;

            const dateInput = form.querySelector('input[name="date"]');
            const convertedDate = form.querySelector('input[name="convertedDate"]');

            function updateConvertedDate() {
                const type = form.querySelector(
                    'input[name="convertType"]:checked'
                )?.value;

                const date = dateInput.value.trim();

                if (!date) {
                    convertedDate.value = '';
                    return false;
                }

                if (type === 'solar') {
                    convertedDate.value = date;
                    return true;
                }

                const match = date.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);

                if (!match) {
                    convertedDate.value = '';
                    return false;
                }

                const day = Number(match[1]);
                const month = Number(match[2]);
                const year = Number(match[3]);

                const result = lunarToSolar(day, month, year, 0);

                if (
                    !Array.isArray(result) ||
                    result.length !== 3 ||
                    !result[0] ||
                    !result[1] ||
                    !result[2]
                ) {
                    convertedDate.value = '';
                    return false;
                }

                convertedDate.value = [
                    String(result[0]).padStart(2, '0'),
                    String(result[1]).padStart(2, '0'),
                    result[2]
                ].join('/');

                return true;
            }

            dateInput.addEventListener('input', updateConvertedDate);
            dateInput.addEventListener('change', updateConvertedDate);

            form.querySelectorAll('input[name="convertType"]').forEach(radio => {
                radio.addEventListener('change', updateConvertedDate);
            });

            form.addEventListener('submit', function (e) {
                if (!updateConvertedDate()) {
                    e.preventDefault();
                    alert('Ngày được chọn không hợp lệ!');
                }
            });

            updateConvertedDate();
        });
    </script>
@endsection
