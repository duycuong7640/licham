@extends('pages::layouts.master')

@section('content')
    @php
        $row = $data['day'];
        [$year, $month, $day] = explode('-', $row['day']);
        [$lunarYear, $lunarMonth, $lunarDay] = explode('-', $row['lunarDay']);
        $thu = \App\Helpers\Helpers::formatVietnameseDateNumber($row['day']);
        $dayKhongMinh = !empty($row['options']['KONG_MING_FORTUNE_DAY'][0]) ? \App\Helpers\Helpers::getKongMingFortune($row['options']['KONG_MING_FORTUNE_DAY'][0]['value']) : [];
        $nguhanh = !empty($row['options']['FIVE_ELEMENTS']) ? \App\Helpers\Helpers::execTagPContent(\App\Helpers\Helpers::getCopeValueByOption($row['options']['FIVE_ELEMENTS'], 'FIVE_ELEMENTS')) : '';
        $textNguhanh = $nguhanh ? \App\Helpers\Helpers::getValueByLabel($nguhanh, 'Ngũ hành niên mệnh') : '';
        $tietkhi = !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'SOLAR_SEASONS') : '';
        $tuoixung = !empty($row['options']['INCOMPATIBLE_AGES']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['INCOMPATIBLE_AGES'], 'INCOMPATIBLE_AGES', false) : '';
        $arrTuoixung = $tuoixung ? \App\Helpers\Helpers::getXungInfo($tuoixung) : [];
        $dayOffices = !empty($row['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption2($row['options']['DAY_OFFICER'], 'DAY_OFFICER') : '';
        $viecnenlam = explode('. ', \App\Helpers\Helpers::getContentInBrackets($dayOffices));
    @endphp
    <section
        id="pre_daily-calendar"
        class="reading-summary"
        aria-label="Tóm tắt lịch âm hôm nay"
    >
        <div class="summary-kicker" id="lich-am-hom-nay">
            <b>Lịch âm hôm nay</b>
            <span>Thông tin quan trọng trong ngày</span>
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
    <nav class="related-topics" aria-label="Chủ đề lịch âm liên quan">
        <span class="related-topics-label">
          <span class="related-topics-icon" aria-hidden="true"></span>
          Chủ đề liên quan
        </span>
        <div class="related-topics-links">
            <small>Xem lịch âm</small>
            <small>Ngày âm lịch</small>
            <small>Xem ngày tốt xấu</small>
            <small>Lịch âm 2026 hôm nay</small>
            <small>Âm lịch hôm nay là bao nhiêu</small>
        </div>
    </nav>
    <div class="page-grid">
        <section class="card today-card">
            <div class="today-heading">
                <div>
                    <span class="section-label">LỊCH ÂM HÔM NAY</span>
                    <h1 id="pre_pageDate">{{ $thu }}, {{ $day }} tháng {{ $month }}, {{ $year }}</h1>
                </div>
                <div class="arrow-group">
                    <button id="pre_prevDay" class="icon-btn">‹</button>
                    <button id="pre_nextDay" class="icon-btn">›</button>
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
                <h2>Thông tin chi tiết hôm nay</h2>
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
    <section class="section-block seo-grid" id="pre_knowledge">
        <article class="card article-card" id="kien-thuc">
            <span class="section-label">KIẾN THỨC LỊCH VIỆT</span>
            <h2>Lịch âm hôm nay có ý nghĩa gì?</h2>
            <p>
                Lịch âm Việt Nam kết hợp chu kỳ Mặt Trăng với các tiết khí của năm
                Mặt Trời. Bên cạnh ngày âm, lịch vạn niên còn cung cấp Can Chi và
                những dữ liệu văn hóa truyền thống thường được tham khảo trong đời
                sống.
            </p>
            <p class="knowledge-note">
                Khi tra cứu, nên đối chiếu ngày dương, ngày âm, giờ hoàng đạo và mục
                đích công việc để có góc nhìn đầy đủ thay vì chỉ dựa vào một thông
                tin riêng lẻ.
            </p>
            <div class="faq">
                <details>
                    <summary>Vì sao âm lịch có tháng nhuận?</summary>
                    <p>
                        Tháng nhuận giúp năm âm lịch duy trì sự tương ứng với chu kỳ
                        mùa.
                    </p>
                </details>
                <details>
                    <summary>Giờ hoàng đạo được dùng để làm gì?</summary>
                    <p>
                        Đây là các khung giờ tốt theo lịch pháp dân gian, thường được
                        tham khảo khi xuất hành hoặc bắt đầu công việc.
                    </p>
                </details>
                <details>
                    <summary>Can Chi của ngày cho biết điều gì?</summary>
                    <p>
                        Can Chi là hệ thống kết hợp Thiên Can và Địa Chi, được dùng để
                        gọi tên ngày, tháng, năm trong lịch pháp truyền thống.
                    </p>
                </details>
            </div>
        </article>
        <aside class="card link-card">
            <span class="section-label">TRA CỨU NHANH</span>
            <h3>Tra cứu theo nhu cầu</h3>
            <p class="lookup-intro">
                Chọn nội dung anh cần xem để đi thẳng đến công cụ phù hợp.
            </p>
            <div class="lookup-links">
                <a href="{{ route('page.home') }}" title="Lịch âm tháng {{ date('m') }}/{{ date('Y') }}">
                    <strong>Lịch âm tháng {{ date('m') }}/{{ date('Y') }}</strong>
                    <small>Xem ngày âm, ngày tốt xấu trong tháng</small>
                </a>
                <a href="{{ route('page.home') }}">
                    <strong>Chọn ngày cưới hỏi</strong>
                    <small>Tra cứu ngày phù hợp cho việc hỷ</small>
                </a>
                <a href="{{ route('page.home') }}">
                    <strong>Chọn ngày khai trương</strong>
                    <small>Tham khảo ngày mở hàng, kinh doanh</small>
                </a>
                <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">
                    <strong>Đổi ngày âm dương</strong>
                    <small>Chuyển đổi nhanh giữa hai loại lịch</small>
                </a>
            </div>
        </aside>
    </section>
    <article class="card seo-analysis" aria-labelledby="convert-seo-title">
        <div class="cms-content">
            <span class="section-label">KIẾN THỨC</span>

            <p class="cms-links">
                <b>Tra cứu tiếp: </b>
                <a href="{{ route('page.cope.show.month', ['month' => date('m'), 'year' => date('Y')]) }}" title="Lịch âm tháng {{ date('m') }}">Lịch âm tháng {{ date('m') }}</a> ·
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
