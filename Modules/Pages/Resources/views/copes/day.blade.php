@extends('pages::layouts.master')

@section('content')
    @php
        $row = $data['day'];
        $dE = explode('-', $row['day']);
        $day = (int) $dE[2];
        $month = (int) $dE[1];
        $year = (int) $dE[0];
        $ldE = explode('-', $row['lunarDay']);
        $lunarDay = (int) $ldE[2];
        $lunarMonth = (int) $ldE[1];
        $lunarYear = (int) $ldE[0];
        $currentDate = \Carbon\Carbon::createFromFormat('Y-m-d', $row['day'], 'Asia/Ho_Chi_Minh');
        $dateText = $day.'/'.$month.'/'.$year;
        $lunarDateText = $lunarDay.'/'.$lunarMonth.'/'.$lunarYear;
        $todayDate = \Carbon\Carbon::today('Asia/Ho_Chi_Minh');
        $isToday = $currentDate->isSameDay($todayDate);
        $thu = \App\Helpers\Helpers::formatVietnameseDateNumber($row['day']);
        $dayStatus = !empty($row['isDay']) ? 'Hoàng đạo' : 'Hắc đạo';

        $previousDate = $currentDate->copy()->subDay();
        $nextDate = $currentDate->copy()->addDay();
        $previousDayRoute = [
            'day'   => (int) $previousDate->format('d'),
            'month' => (int) $previousDate->format('m'),
            'year'  => (int) $previousDate->format('Y'),
        ];
        $nextDayRoute = [
            'day'   => (int) $nextDate->format('d'),
            'month' => (int) $nextDate->format('m'),
            'year'  => (int) $nextDate->format('Y'),
        ];
        $previousDayText = $previousDate->format('d/m/Y');
        $nextDayText = $nextDate->format('d/m/Y');

        $dayKhongMinh = !empty($row['options']['KONG_MING_FORTUNE_DAY'][0]) ? \App\Helpers\Helpers::getKongMingFortune($row['options']['KONG_MING_FORTUNE_DAY'][0]['value']) : [];
        $nguhanh = !empty($row['options']['FIVE_ELEMENTS']) ? \App\Helpers\Helpers::execTagPContent(\App\Helpers\Helpers::getCopeValueByOption($row['options']['FIVE_ELEMENTS'], 'FIVE_ELEMENTS')) : '';
        $textNguhanh = $nguhanh ? \App\Helpers\Helpers::getValueByLabel($nguhanh, 'Ngũ hành niên mệnh') : '';
        $tietkhi = !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'SOLAR_SEASONS') : '';
        $tuoixung = !empty($row['options']['INCOMPATIBLE_AGES']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['INCOMPATIBLE_AGES'], 'INCOMPATIBLE_AGES', false) : '';
        $arrTuoixung = $tuoixung ? \App\Helpers\Helpers::getXungInfo($tuoixung) : [];
        $dayOffices = !empty($row['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption2($row['options']['DAY_OFFICER'], 'DAY_OFFICER') : '';
        $viecnenlam = explode('. ', \App\Helpers\Helpers::getContentInBrackets($dayOffices));

        $dayOfficerTitle = '';
        if (!empty($dayOffices)) {
            $dayOfficerTitle = trim(preg_replace('/\s*\(.*$/u', '', strip_tags($dayOffices)));
        }
        $dayOfficerContent = !empty($dayOffices)? trim(\App\Helpers\Helpers::getContentInBrackets($dayOffices)) : '';
        $goodStarCount = !empty($row['options']['GOOD_STAR']) ? count($row['options']['GOOD_STAR']) : 0;

        $goodStarNames = [];
        if (!empty($row['options']['GOOD_STAR'])) {
            foreach ($row['options']['GOOD_STAR'] as $value) {
                $text = trim(
                    strip_tags($value['value'] ?? '')
                );
                if (empty($text)) {
                    continue;
                }
                $parts = explode(':', $text, 2);
                if (!empty($parts[0])) {
                    $goodStarNames[] = trim($parts[0]);
                }
            }
        }

        $badStarCount = !empty($row['options']['BAD_STAR']) ? count($row['options']['BAD_STAR']) : 0;
        $badStarNames = [];
        if (!empty($row['options']['BAD_STAR'])) {
            foreach ($row['options']['BAD_STAR'] as $value) {
                $text = trim(
                    strip_tags($value['value'] ?? '')
                );
                if (empty($text)) {
                    continue;
                }
                $parts = explode(':', $text, 2);
                if (!empty($parts[0])) {
                    $badStarNames[] = trim($parts[0]);
                }
            }
        }

        $xungNgay = !empty($arrTuoixung['xungngay'])
            ? trim($arrTuoixung['xungngay'])
            : '';

        $hasTabooDay = !empty($tabooText);

    @endphp
    <section
        id="pre_daily-calendar"
        class="reading-summary"
        aria-label="Tóm tắt lịch âm hôm nay"
    >
        <div class="summary-kicker" id="lich-am-hom-nay">
            <h1>Lịch âm ngày {{ $day . '/' . $month . '/' .$year }}</h1>
            <span>
                {{ $isToday ? 'Lịch âm hôm nay' : 'Lịch âm theo ngày' }}</span>
        </div>
        <div class="summary-grid">
            <p class="summary-line">
                Ngày <b>Dương Lịch</b>: <strong id="pre_summarySolar">{{ $day }}-{{ $month }}-{{ $year }}</strong>
            </p>
            <p class="summary-line">
                Ngày <b>Âm Lịch</b>: <strong id="pre_summaryLunar">{{ $lunarDay }}-{{ $lunarMonth }}
                    -{{ $lunarYear }}</strong>
            </p>
            <p class="summary-line">
                Ngày trong tuần: <strong id="pre_summaryWeekday">{{ $thu }}</strong>
            </p>
            <p class="summary-line">
                Ngày <strong id="pre_summaryCanChi">{{ $row['strDay'] }}</strong>
                tháng <strong>{{ $row['strMonth'] }}</strong>
                năm <strong>{{ $row['strYear'] }}</strong>
            </p>
            <p class="summary-line summary-wide">
                Ngày <strong>{{ !empty($dayKhongMinh[0]) ? $dayKhongMinh[0] : '' }}</strong>:
                <span id="pre_summaryTravel">{{ !empty($dayKhongMinh[1]) ? $dayKhongMinh[1] : '' }}</span>
            </p>
            <p class="summary-line summary-wide">
                Giờ <b>Hoàng Đạo</b>:
                <strong id="pre_summaryHours">
                    @if(!empty($row['options']['AUSPICIOUS_HOUR']))
                        @foreach($row['options']['AUSPICIOUS_HOUR'] as $k=>$value)
                            @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                            @if($k), @endif
                            {{ !empty($time['title']) ? $time['title'] : '' }}
                            ({{ !empty($time['hour']) ? $time['hour'] : '' }})
                        @endforeach
                    @endif
                </strong>
            </p>
        </div>
    </section>
    <div class="page-grid">
        <section class="card today-card">
            <div class="today-heading">
                <div>
                    <span class="section-label">LỊCH ÂM HÔM NAY</span>
                    <p id="pre_pageDate">{{ $thu }}, {{ $day }} tháng {{ $month }}, {{ $year }}</p>
                </div>
                <div class="arrow-group">
                    <a href="{{ route('page.cope.show.day', $previousDayRoute) }}" class="icon-btn btn-loading" title="Lịch ngày {{ $previousDayText }}" aria-label="Xem lịch ngày {{ $previousDayText }}">
                        ‹
                    </a>
                    <a href="{{ route('page.cope.show.day', $nextDayRoute) }}" class="icon-btn btn-loading" title="Lịch ngày {{ $nextDayText }}" aria-label="Xem lịch ngày {{ $nextDayText }}">
                        ›
                    </a>
                </div>
            </div>
            <div class="date-panels">
                <div class="date-panel solar">
                    <small>DƯƠNG LỊCH</small>
                    <strong id="pre_solarDay">{{ $day }}</strong>
                    <b id="pre_solarMeta">Tháng {{ $month }} năm {{ $year }}</b>
                    <span id="pre_solarWeekday">{{ $thu }}</span>
                </div>
                <div class="date-panel lunar">
                    <small>ÂM LỊCH</small>
                    <strong id="pre_lunarDay">{{ $lunarDay }}</strong>
                    <b id="pre_lunarMeta">Tháng {{ $lunarMonth }} năm {{ $lunarYear }}</b>
                    <span>Ngày {{ $row['strDay'] }}</span>
                </div>
            </div>
            <div class="status-line">
                <span class="status-pill">SAO TỐT</span>
                <span>
                    @foreach($row['options']['GOOD_STAR'] as $k=>$value)
                        @php $arrStar = explode(':', strip_tags($value['value'])); @endphp
                        @if($k) · @endif
                        {{ !empty($arrStar[0]) ? $arrStar[0] : '' }}
                    @endforeach
                </span>
            </div>
        </section>
        <aside class="card facts-card">
            <span class="section-label">THÔNG TIN TRONG NGÀY</span>
            <h2>Tổng quan nhanh</h2>
            <dl class="fact-list">
                <div>
                    <dt>Can chi ngày</dt>
                    <dd>{{ $row['strDay'] }}</dd>
                </div>
                <div>
                    <dt>Trực ngày</dt>
                    <dd>{{ !empty($row['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['DAY_OFFICER'], 'DAY_OFFICER') : '' }}</dd>
                </div>
                <div>
                    <dt>Ngũ hành</dt>
                    <dd>{{ $textNguhanh }}</dd>
                </div>
                <div>
                    <dt>Tiết khí</dt>
                    <dd>{{ $tietkhi }}</dd>
                </div>
                <div>
                    <dt>Xung ngày</dt>
                    <dd>{{ !empty($arrTuoixung['xungngay']) ? $arrTuoixung['xungngay'] : '' }}</dd>
                </div>
                <div>
                    <dt>Xung tháng</dt>
                    <dd>{{ !empty($arrTuoixung['xungthang']) ? $arrTuoixung['xungthang'] : '' }}</dd>
                </div>
            </dl>
        </aside>
        <div class="priority-strip">
            <div class="priority-item">
                <span class="priority-icon">✓</span>
                <div>
                    <small>GIỜ HOÀNG ĐẠO</small>
                    <b>
                        @if(!empty($row['options']['AUSPICIOUS_HOUR']))
                            @foreach($row['options']['AUSPICIOUS_HOUR'] as $k=>$value)
                                @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                                @if($k) · @endif
                                {{ !empty($time['title']) ? $time['title'] : '' }}
                            @endforeach
                        @endif
                    </b>
                </div>
            </div>
            <div class="priority-item">
                <span class="priority-icon">↗</span>
                <div>
                    <small>HƯỚNG XUẤT HÀNH</small>
                    <b>
                        @php $str = ''; @endphp
                        @if(!empty($row['options']['DIRECTION_OF_TRAVEL']))
                            @foreach($row['options']['DIRECTION_OF_TRAVEL'] as $value)
                                @php $str .= $value['value']; @endphp
                            @endforeach
                        @endif
                        @php
                            $arrHuongXH = $str ? \App\Helpers\Helpers::getThanHuongInfo($str) : [];
                        @endphp
                        @foreach ($arrHuongXH as $k=>$value)
                            @if($value['title'] != 'Hắc thần')
                                @if($k) · @endif
                                {!! $value['title'].'<span style="">: '.$value['content'].'</span>' !!}
                            @endif
                        @endforeach
                    </b>
                </div>
            </div>
            <div class="priority-item">
                <span class="priority-icon">◎</span>
                <div>
                    <small>VIỆC NÊN LÀM</small>
                    <b>{{ !empty($viecnenlam[0]) ? $viecnenlam[0] : '' }}</b>
                </div>
            </div>
        </div>
    </div>
    @php
        $months = \App\Helpers\Helpers::buildCalendar($data['months']);
        $rowDay = $data['day'];
        $todayDate = $data['day']['day'];
        $monthButton = $data['mData'];
    @endphp
    <section class="section-block card calendar-card">
        <div class="section-head">
            <div>
                <span class="section-label">LỊCH ÂM DƯƠNG</span>
                <h2 id="pre_monthTitle">Tháng {{ $data['month'] }} năm {{ $data['year'] }}</h2>
            </div>
            <div class="month-controls">
                <a href="{{ route('page.cope.show.month', ['month' => $monthButton['prev']['month'], 'year' => $monthButton['prev']['year']]) }}"
                   title="Xem lịch tháng {{ $monthButton['prev']['month'] }} năm {{ $monthButton['prev']['year'] }}"
                   aria-label="Xem lịch tháng {{ $monthButton['prev']['month'] }} năm {{ $monthButton['prev']['year'] }}">‹</a>
                <a href="{{ route('page.cope.show.month', ['month' => $data['month'], 'year' => $data['year']]) }}"
                   title="Xem lịch tháng {{ $data['month'] }} năm {{ $data['year'] }}"
                   aria-label="Xem lịch tháng {{ $data['month'] }} năm {{ $data['year'] }}">Tháng này</a>
                <a href="{{ route('page.cope.show.month', ['month' => $monthButton['next']['month'], 'year' => $monthButton['next']['year']]) }}"
                   title="Xem lịch tháng {{ $monthButton['next']['month'] }} năm {{ $monthButton['next']['year'] }}"
                   aria-label="Xem lịch tháng {{ $monthButton['next']['month'] }} năm {{ $monthButton['next']['year'] }}">›</a>
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
                           class="calendar-day btn-loading {{ $isDay }} {{ $isSaturday }} {{ $isSunday }} {{ $today }} {{ $today }} {{ $isViewDay }}"
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
            <span><i></i> Ngày tốt</span>
            <span><i class="bad-dot"></i> Ngày xấu</span>
            <span>Ngày âm ở góc phải</span>
            <span>Di chuột hoặc chạm để xem nhanh</span>
        </div>
    </section>
    <section class="section-block" id="pre_detail">
        <div class="section-head">
            <div>
                <span class="section-label">XEM NGÀY TỐT XẤU</span>
                <h2>Thông tin chi tiết ngày {{ $dateText }}</h2>
            </div>
        </div>
        <div class="detail-table">
            @foreach($row['options']['LUNAR'] as $value)
                <div class="detail-row">
                    <h3>{{ dataKey::COPE_KEYS[$value['key']] }}</h3>
                    <div class="detail-content">
                        {{ $value['value'] }}

                        @if($value['key'] == 'SOLAR_SEASONS')
                            @if(!empty($row['options']['SUMMER']))
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
                                <div class="table-responsive mt-5">
                                    <table class="data-table">
                                        <thead>
                                        <tr>
                                            <th><strong>Mặt trời</strong></th>
                                            <th><strong>Giờ mọc</strong></th>
                                            <th><strong>Giờ lặn</strong></th>
                                        </tr>
                                        </thead>
                                        <tbody>
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
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="detail-row" id="gio-hoang-dao">
                <h3>Giờ hoàng đạo</h3>
                <div class="detail-content">
                    @if(!empty($rowDay['options']['AUSPICIOUS_HOUR']))
                        @foreach($rowDay['options']['AUSPICIOUS_HOUR'] as $k=>$value)
                            @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                            <span
                                class="tag">{{ !empty($time['title']) ? $time['title'] : '' }} {{ !empty($time['hour']) ? $time['hour'] : '' }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="detail-row">
                <h3>Giờ hắc đạo</h3>
                <div class="detail-content">
                    @if(!empty($rowDay['options']['DARK_HOUR']))
                        @foreach($rowDay['options']['DARK_HOUR'] as $k=>$value)
                            @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                            <span
                                class="tag bad">{{ !empty($time['title']) ? $time['title'] : '' }} {{ !empty($time['hour']) ? $time['hour'] : '' }}</span>
                        @endforeach
                    @endif
                </div>
            </div>

            @if(!empty($row['options']['FIVE_ELEMENTS']))
                <div class="detail-row">
                    <h3>Ngũ hành</h3>
                    <div class="detail-content">
                        {!! !empty($row['options']['FIVE_ELEMENTS']) ? \App\Helpers\Helpers::execTagPContent(\App\Helpers\Helpers::getCopeValueByOption($row['options']['FIVE_ELEMENTS'], 'FIVE_ELEMENTS')) : '' !!}
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['DAY_OFFICER']))
                <div class="detail-row">
                    <h3>Xem ngày lành, xấu theo trực</h3>
                    <div class="detail-content">
                        {!! !empty($row['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['DAY_OFFICER'], 'DAY_OFFICER', false) : '' !!}
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['INCOMPATIBLE_AGES']))
                <div class="detail-row">
                    <h3>Tuổi xung khắc</h3>
                    <div class="detail-content">
                        {!! !empty($row['options']['INCOMPATIBLE_AGES']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['INCOMPATIBLE_AGES'], 'INCOMPATIBLE_AGES', false) : '' !!}
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['GOOD_STAR']))
                <div class="detail-row">
                    <h3>Sao tốt (Theo Ngọc hạp thông thư)</h3>
                    <div class="detail-content">
                        @foreach($row['options']['GOOD_STAR'] as $value)
                            <p>{{ strip_tags($value['value']) }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['BAD_STAR']))
                <div class="detail-row">
                    <h3>Sao xấu (Theo Ngọc hạp thông thư)</h3>
                    <div class="detail-content">
                        @foreach($row['options']['BAD_STAR'] as $value)
                            <p>{{ strip_tags($value['value']) }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['TABOO_DAY']))
                <div class="detail-row">
                    <h3>Ngày kỵ</h3>
                    <div class="detail-content">
                        @foreach($row['options']['TABOO_DAY'] as $value)
                            <p>{{ strip_tags($value['value']) }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['DIRECTION_OF_TRAVEL']))
                <div class="detail-row">
                    <h3>Hướng xuất hành</h3>
                    <div class="detail-content">
                        @foreach($row['options']['DIRECTION_OF_TRAVEL'] as $value)
                            {!! $value['value'] !!}
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['LUNAR_MANSION']))
                <div class="detail-row">
                    <h3>Ngày tốt theo Nhị thập bát tú</h3>
                    <div class="detail-content cleanContent">
                        @foreach($row['options']['LUNAR_MANSION'] as $value)
                            {!! $value['value'] !!}
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['HUMAN_DEITIES']))
                <div class="detail-row">
                    <h3>Nhân thần</h3>
                    <div class="detail-content">
                        @foreach($row['options']['HUMAN_DEITIES'] as $value)
                            {!! $value['value'] !!}
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['FETAL_GOD']))
                <div class="detail-row">
                    <h3>Thai thần</h3>
                    <div class="detail-content">
                        @foreach($row['options']['FETAL_GOD'] as $value)
                            @php $fetalGod = \App\Helpers\Helpers::splitThaiThanContent($value['value']); @endphp
                            <div class="detail-row">
                                <h4 style="color: #000;">{!! $fetalGod['title'] !!}</h4>
                                <div class="detail-content">
                                    {!! $fetalGod['content'] !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['LY_THUAN_PHONG_DEPARTURE_HOURS']))
                <div class="detail-row">
                    <h3>Giờ xuất hành theo Lý Thuần Phong</h3>
                    <div class="detail-content">
                        @foreach($row['options']['LY_THUAN_PHONG_DEPARTURE_HOURS'] as $value)
                            @php
                                $step = explode('(//)', $value['value']);
                            @endphp
                            <div class="detail-row">
                                @if(!empty($step[0]))
                                    <h4 style="color: #000;">{!! $step[0] !!}</h4>
                                    <div class="detail-content">
                                        {!! $step[1] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['KONG_MING_FORTUNE_DAY']))
                <div class="detail-row" id="xuat-hanh">
                    <h3>Ngày xuất hành theo Khổng Minh</h3>
                    <div class="detail-content">
                        @foreach($row['options']['KONG_MING_FORTUNE_DAY'] as $value)
                            {!! $value['value'] !!}
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['PENG_ZU_TABOOS']))
                <div class="detail-row">
                    <h3>Bành tổ bách kỵ</h3>
                    <div class="detail-content">
                        @foreach($row['options']['PENG_ZU_TABOOS'] as $value)
                            @php
                                $step = explode('(//)', $value['value']);
                            @endphp
                            <div class="detail-row">
                                @if(!empty($step[0]))
                                    <h4 style="color: #000;">{!! $step[0] !!}</h4>
                                    <div class="detail-content">
                                        {!! $step[1] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['HISTORICAL_IN_EVENTS']))
                <div class="detail-row">
                    <h3>Ngày này năm xưa</h3>
                    <div class="detail-content">
                        @foreach($row['options']['HISTORICAL_IN_EVENTS'] as $value)
                            @php
                                $step = explode('(//)', $value['value']);
                            @endphp
                            <div class="detail-row">
                                @if(!empty($step[0]))
                                    <h4 style="color: #000;">{!! $step[0] !!}</h4>
                                    <div class="detail-content">
                                        {!! $step[1] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($row['options']['HISTORICAL_OUT_EVENTS']))
                <div class="detail-row">
                    <h3>Sự kiện quốc tế</h3>
                    <div class="detail-content">
                        @foreach($row['options']['HISTORICAL_OUT_EVENTS'] as $value)
                            @php
                                $step = explode('(//)', $value['value']);
                            @endphp
                            <div class="detail-row">
                                @if(!empty($step[0]))
                                    <h4 style="color: #000;">{!! $step[0] !!}</h4>
                                    <div class="detail-content">
                                        {!! $step[1] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
    <article class="card seo-analysis" aria-labelledby="day-analysis-title">
        <div class="cms-content">
            <span class="section-label">
                NHẬN ĐỊNH TRONG NGÀY
            </span>
            <h2 id="day-analysis-title">
                Nhận định lịch ngày {{ $dateText }}
            </h2>
            <p>
                Ngày <strong>{{ $dateText }}</strong>
                tương ứng <strong>{{ $lunarDateText }}</strong> âm lịch,
                là ngày <strong>{{ $row['strDay'] }}</strong>,
                tháng <strong>{{ $row['strMonth'] }}</strong>,
                năm <strong>{{ $row['strYear'] }}</strong>.
                @if(!empty($dayStatus))
                    Theo phân loại Hoàng đạo – Hắc đạo,
                    đây là <strong>ngày {{ $dayStatus }}</strong>.
                @endif
            </p>
            @if(!empty($dayOfficerTitle) || !empty($textNguhanh) || !empty($tietkhi))
                <p>
                    @if(!empty($dayOfficerTitle))
                        Ngày có
                        <strong>Trực {{ $dayOfficerTitle }}</strong>.
                        @if(!empty($dayOfficerContent))
                            {{ rtrim($dayOfficerContent, '.') }}.
                        @endif
                    @endif
                    @if(!empty($textNguhanh))
                        Ngũ hành của ngày là
                        <strong>{{ $textNguhanh }}</strong>.
                    @endif
                    @if(!empty($tietkhi))
                        Thời điểm này thuộc tiết khí
                        <strong>{{ $tietkhi }}</strong>.
                    @endif
                </p>
            @endif
            @if(
                $goodStarCount > 0
                || $badStarCount > 0
            )
                <p>
                    @if($goodStarCount > 0)
                        Trong ngày có
                        {{ $goodStarCount }}
                        sao tốt đáng chú ý
                        <strong>
                            {{ implode(', ', $goodStarNames) }}
                        </strong>.
                    @endif
                    @if($badStarCount > 0)
                        @if($goodStarCount > 0)
                            Bên cạnh đó,
                        @endif
                        có
                        {{ $badStarCount }}
                        sao xấu cần lưu ý
                        <strong>
                            {{ implode(', ', $badStarNames) }}
                        </strong>.
                    @endif
                </p>
            @endif
            @if(!empty($xungNgay) || $hasTabooDay)
                <p>
                    @if(!empty($xungNgay))
                        Các tuổi xung với ngày gồm
                        <strong>{{ rtrim($xungNgay, '.') }}</strong>.
                    @endif
                    @if($hasTabooDay)
                        Ngày này cũng có yếu tố cần lưu ý:
                        {{ rtrim($tabooText, '.') }}.
                    @endif
                </p>
            @endif
            @if(!empty($dayKhongMinh[0]) || !empty($joyDirection) || !empty($wealthDirection))
                <p>
                    @if(!empty($dayKhongMinh[0]) && !empty($dayKhongMinh[1]))
                        Theo Khổng Minh, ngày này thuộc
                        <strong>{{ $dayKhongMinh[0] }}</strong>:
                        {{ rtrim($dayKhongMinh[1], '.') }}.
                    @endif
                    @if(!empty($joyDirection))
                        Hỷ thần ở
                        <strong>{{ $joyDirection }}</strong>.
                    @endif
                    @if(!empty($wealthDirection))
                        Tài thần ở
                        <strong>{{ $wealthDirection }}</strong>.
                    @endif
                </p>
            @endif
            <p class="knowledge-note">
                @if($goodStarCount > 0 && $badStarCount > 0)
                    Ngày {{ $dateText }} có cả yếu tố thuận lợi
                    và những điểm cần lưu ý.
                    Khi chọn ngày cho một công việc cụ thể,
                    nên đối chiếu Trực ngày, sao tốt – sao xấu,
                    tuổi xung và giờ thực hiện.
                @elseif($goodStarCount > 0 && $badStarCount === 0)
                    Ngày {{ $dateText }} có nhiều yếu tố thuận
                    theo dữ liệu lịch pháp trên trang.
                    Tuy vậy, vẫn nên đối chiếu với mục đích
                    công việc và tuổi của người thực hiện.
                @elseif($badStarCount > 0)
                    Ngày {{ $dateText }} có một số yếu tố
                    cần thận trọng theo lịch pháp truyền thống.
                    Nên xem kỹ từng công việc cụ thể
                    trước khi lựa chọn thời điểm thực hiện.
                @else
                    Khi xem ngày {{ $dateText }},
                    nên đối chiếu nhiều yếu tố lịch pháp
                    thay vì dựa vào một thông tin riêng lẻ.
                @endif
            </p>
            <p class="cms-links">
                <b>Tra cứu tiếp: </b>
                <a href="{{ route('page.cope.show.month', ['month' => (int) date('m'), 'year' => date('Y')]) }}" title="Lịch âm tháng {{ (int) date('m') }}">Lịch âm tháng {{ (int) date('m') }}</a> ·
                <a href="{{ route('page.cope.show.year', ['year' => date('Y')]) }}" title="Lịch âm năm {{ date('Y') }}">Lịch âm năm {{ date('Y') }}</a> ·
                <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">Đổi ngày âm dương</a>
            </p>
        </div>
    </article>
@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection
