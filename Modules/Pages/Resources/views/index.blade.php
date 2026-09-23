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
        $preNextData = \App\Helpers\Helpers::getPrevNextDay($day, $month, $year);
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
            <h1>{{ !empty($configData) ? \App\Helpers\Helpers::renderCode($configData, settingKey::H1_HOME) : 'Lịch Âm Hôm Nay - Lịch Vạn Niên Việt Nam' }}</h1>
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
                <h2 id="pre_monthTitle">Lịch xxx âm tháng {{ $data['month'] }} năm {{ $data['year'] }}</h2>
            </div>
            <div class="month-controls">
                <a href="{{ route('page.cope.show.month', ['month' => $monthButton['prev']['month'], 'year' => $monthButton['prev']['year']]) }}"
                   title="Xem lịch tháng {{ $monthButton['prev']['month'] }} năm {{ $monthButton['prev']['year'] }}"
                   aria-label="Xem lịch tháng {{ $monthButton['prev']['month'] }} năm {{ $monthButton['prev']['year'] }}">‹</a>
                <a href="{{ route('page.cope.show.month', ['month' => date('m'), 'year' => date('Y')]) }}"
                   title="Xem lịch tháng {{ date('m') }} năm {{ date('Y') }}"
                   aria-label="Xem lịch tháng {{ date('m') }} năm {{ date('Y') }}">Tháng này</a>
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
                            $today = $month['date'] == $todayDate ? 'selected today' : '';
                            $dayOffices = !empty($month['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption2($month['options']['DAY_OFFICER'], 'DAY_OFFICER') : '';
                            $montViecnenlam = $dayOffices ? explode('. ', \App\Helpers\Helpers::getContentInBrackets($dayOffices)) : [];
                            $month['day'] = \App\Helpers\Helpers::checkNumber($month['day']);
                            $month['month'] = \App\Helpers\Helpers::checkNumber($month['month']);
                            $month['lunarDay'] = \App\Helpers\Helpers::checkNumber($month['lunarDay']);
                            $month['lunarMonth'] = \App\Helpers\Helpers::checkNumber($month['lunarMonth']);
                        @endphp
                        <a href="{{ route('page.cope.show.day', ['day' => $month['day'], 'month' => $month['month'], 'year' => $month['year']]) }}"
                           class="calendar-day btn-loading {{ $isDay }} {{ $isSaturday }} {{ $isSunday }} {{ $today }} {{ $today }}"
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
    @php
        $day = (int) $dE[2];
        $month = (int) $dE[1];
        $year = (int) $dE[0];
    @endphp
    <section class="section-block card home-calendar-content" aria-labelledby="today-lunar-title">
        <div class="calendar-content-topline">
            <a class="calendar-month-link" href="{{ route('page.cope.show.month', ['month' => $month, 'year' => $year]) }}" title="Xem chi tiết lịch tháng {{ $month }}/{{ $year }}">
                Xem chi tiết lịch tháng {{ $month }}/{{ $year }}
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="calendar-content-body">
            <article class="calendar-today-copy">
                <span class="section-label">LỊCH ÂM HÔM NAY</span>
                <h2 id="today-lunar-title">Hôm nay là ngày bao nhiêu âm lịch?</h2>
                <p class="calendar-content-lead">
                    Hôm nay, <strong>{{ $thu }} ngày {{ $day }}/{{ $month }}/{{ $year }}</strong> dương lịch,
                    tương ứng <strong>ngày {{ $lunarDay }}/{{ $lunarMonth }} năm Bính Ngọ</strong> âm lịch.
                    Hôm nay là ngày <strong>{{ $row['strDay'] }}</strong>, tháng
                    <strong>{{ $row['strMonth'] }}</strong>, năm <strong>{{ $row['strYear'] }}</strong>.
                </p>
                <p>
                    Tiết khí hiện tại là <strong>{{ $tietkhi }}</strong>. Người xem có thể
                    tra cứu thêm giờ hoàng đạo, hướng xuất hành, tuổi xung và những
                    việc nên hoặc không nên thực hiện trong ngày.
                </p>
                <a class="calendar-detail-cta"
                    href="{{ route('page.cope.show.day', ['day' => $day, 'month' => $month, 'year' => $year]) }}"
                   title="Xem ngày {{ $day }}/{{ $month }}/{{ $year }} tốt hay xấu">
                    Xem ngày {{ $day }}/{{ $month }}/{{ $year }} tốt hay xấu
                    <span aria-hidden="true">→</span>
                </a>
            </article>
            <aside class="calendar-lookup" aria-labelledby="calendar-lookup-title">
                <h3 id="calendar-lookup-title">Tra cứu lịch âm – lịch vạn niên</h3>
                <nav class="calendar-lookup-links" aria-label="Tra cứu lịch nhanh">
                    <a href="{{ route('page.cope.show.day', ['day' => $preNextData['yesterday']['d'], 'month' => $preNextData['yesterday']['m'], 'year' => $preNextData['yesterday']['y']]) }}" title="Lịch âm hôm qua">Lịch âm hôm qua</a>
                    <a class="is-current" href="{{ route('page.cope.show.day', ['day' => $day, 'month' => $month, 'year' => $year]) }}" title="Lịch âm hôm nay">Lịch âm hôm nay</a>
                    <a href="{{ route('page.cope.show.day', ['day' => $preNextData['tomorrow']['d'], 'month' => $preNextData['tomorrow']['m'], 'year' => $preNextData['tomorrow']['y']]) }}" title="Lịch âm ngày mai">Lịch âm ngày mai</a>
                    <a href="{{ route('page.cope.show.month', ['month' => $month, 'year' => $year]) }}" title="Lịch tháng {{ $month }}/{{ $year }}">Lịch tháng {{ $month }}/{{ $year }}</a>
                    <a href="{{ route('page.cope.show.year', ['year' => $year]) }}" title="Lịch năm {{ $year }}">Lịch năm {{ $year }}</a>
                    <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">Đổi ngày âm dương</a>
                </nav>
            </aside>
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
        <article class="card article-card" id="kien-thuc-lich-am">
            <span class="section-label">KIẾN THỨC LỊCH VIỆT</span>
            <h2>Lịch âm là gì? Cách xem lịch âm hôm nay</h2>
            <p>
                <strong>Lịch âm hôm nay</strong> giúp tra cứu ngày âm lịch tương ứng với ngày dương lịch,
                cùng các thông tin như Can Chi, tiết khí, giờ hoàng đạo, tuổi xung và hướng xuất hành.
                Tại <strong>Lịch Âm Tốt</strong>, bạn có thể xem nhanh lịch ngày, lịch tháng, lịch năm
                hoặc chuyển đổi ngày âm dương theo lịch Việt Nam.
            </p>
            <p class="knowledge-note">
                Khi xem ngày, nên đối chiếu nhiều thông tin như ngày âm dương, Can Chi,
                giờ hoàng đạo và mục đích công việc. Các nội dung về ngày tốt xấu mang tính
                tham khảo theo lịch pháp và quan niệm dân gian truyền thống.
            </p>
            <div class="faq">
                <details>
                    <summary>Âm lịch là gì?</summary>
                    <p>
                        Âm lịch là cách tính thời gian dựa chủ yếu vào chu kỳ của Mặt Trăng.
                        Lịch truyền thống Việt Nam sử dụng hệ thống âm dương lịch, kết hợp
                        chu kỳ Mặt Trăng với năm Mặt Trời để phù hợp với sự thay đổi của mùa.
                    </p>
                </details>
                <details>
                    <summary>Vì sao âm lịch có tháng nhuận?</summary>
                    <p>
                        Một năm âm lịch ngắn hơn năm dương lịch. Vì vậy, sau một số năm
                        sẽ cần thêm tháng nhuận để lịch âm tiếp tục phù hợp với chu kỳ
                        thời tiết và mùa trong năm.
                    </p>
                </details>
                <details>
                    <summary>Giờ hoàng đạo là gì?</summary>
                    <p>
                        Giờ hoàng đạo là những khung giờ được xem là thuận lợi theo
                        lịch pháp dân gian. Thông tin này thường được tham khảo khi
                        xuất hành, khai trương hoặc thực hiện một số công việc quan trọng.
                    </p>
                </details>
                <details>
                    <summary>Can Chi của ngày có ý nghĩa gì?</summary>
                    <p>
                        Can Chi được hình thành từ 10 Thiên Can và 12 Địa Chi.
                        Hệ thống này được dùng để gọi tên năm, tháng, ngày và giờ
                        trong lịch pháp truyền thống.
                    </p>
                </details>
            </div>
        </article>
        <aside class="card link-card" aria-labelledby="quick-lookup-title">
            <span class="section-label">TRA CỨU NHANH</span>
            <h2 id="quick-lookup-title">Tra cứu lịch âm theo nhu cầu</h2>
            <p class="lookup-intro">
                Chọn nội dung cần xem để tra cứu lịch âm, ngày tốt và các công cụ liên quan.
            </p>
            <div class="lookup-links">
                <a href="{{ route('page.cope.show.month', [
                        'month' => now()->month,
                        'year' => now()->year
                    ]) }}"
                   title="Lịch âm tháng {{ now()->month }}/{{ now()->year }}">
                    <strong>
                        Lịch âm tháng {{ now()->month }}/{{ now()->year }}
                    </strong>
                    <small>
                        Xem lịch âm dương và ngày tốt xấu trong tháng
                    </small>
                </a>
                <a href="{{ route('page.cope.show.year', ['year' => now()->year]) }}"
                   title="Lịch âm năm {{ now()->year }}">
                    <strong>
                        Lịch âm năm {{ now()->year }}
                    </strong>
                    <small>
                        Tra cứu lịch âm, ngày lễ và các tháng trong năm
                    </small>
                </a>
                <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">
                    <strong>Đổi ngày âm dương</strong>
                    <small>
                        Chuyển đổi ngày dương sang âm và ngược lại
                    </small>
                </a>
                <a href="{{ route('page.cate.index', ['slug' => 'bai-viet']) }}"
                   title="Bài viết tử vi, phong thủy">
                    <strong>Bài viết</strong>
                    <small>
                        Tử vi · Phong thủy · 12 con giáp · Lịch Việt
                    </small>
                </a>
            </div>
        </aside>
    </section>
    <article class="card seo-analysis" aria-labelledby="seo-analysis-title">
        <div class="cms-content">
            @php
                $content_title_home =  !empty($configData) ? \App\Helpers\Helpers::renderCode($configData, settingKey::CONTENT_HOME_TITLE) : '';
            @endphp
            @if($content_title_home)
                <span class="section-label">{!! $content_title_home !!}</span>
            @endif
            {!! !empty($configData) ? \App\Helpers\Helpers::renderCode($configData, settingKey::CONTENT_HOME) : '' !!}

            <h2>Lịch âm hôm nay - Tra cứu lịch vạn niên Việt Nam</h2>

            <p>
                <strong>Lịch Âm Tốt</strong> giúp tra cứu <strong>lịch âm hôm nay</strong>,
                lịch vạn niên và thông tin ngày âm dương theo lịch Việt Nam.
                Người dùng có thể xem ngày âm lịch, Can Chi, tiết khí, giờ hoàng đạo,
                tuổi xung, hướng xuất hành và các thông tin thường được tham khảo
                khi xem ngày tốt xấu.
            </p>

            <p>
                Bên cạnh lịch ngày, bạn có thể xem
                <a href="">lịch âm theo tháng</a>,
                <a href="">lịch âm theo năm</a>
                hoặc sử dụng công cụ
                <a href="">đổi ngày âm dương</a>
                để tra cứu các mốc thời gian trong quá khứ và tương lai.
            </p>


            <h3>Lịch âm hôm nay cho biết những thông tin gì?</h3>

            <p>
                Khi xem lịch âm hôm nay, thông tin cơ bản nhất là ngày dương lịch
                và ngày âm lịch tương ứng. Ngoài ra, lịch truyền thống còn sử dụng
                hệ thống Thiên Can, Địa Chi, tiết khí và nhiều yếu tố lịch pháp
                để mô tả đặc điểm của từng ngày.
            </p>

            <p>
                Người dùng có thể tra cứu Can Chi của ngày, tháng và năm,
                giờ hoàng đạo, tuổi xung, hướng xuất hành cũng như những việc
                nên làm hoặc nên hạn chế theo quan niệm lịch pháp dân gian.
                Nếu cần xem đầy đủ hơn, bạn có thể truy cập
                <a href="">lịch ngày hôm nay</a>
                để xem thông tin chi tiết.
            </p>


            <h3>Lịch vạn niên và lịch âm có gì khác nhau?</h3>

            <p>
                Trong cách sử dụng phổ biến, <strong>lịch âm</strong> thường được dùng
                để chỉ ngày tháng theo âm lịch, trong khi <strong>lịch vạn niên</strong>
                cung cấp phạm vi thông tin rộng hơn. Một trang lịch vạn niên có thể
                bao gồm ngày dương, ngày âm, Can Chi, tiết khí, giờ hoàng đạo,
                ngày hoàng đạo, ngày hắc đạo và nhiều dữ liệu lịch pháp truyền thống.
            </p>

            <p>
                Vì vậy, nếu chỉ muốn biết hôm nay là ngày bao nhiêu âm lịch,
                người dùng có thể xem nhanh phần ngày âm. Khi cần tìm hiểu kỹ hơn
                về đặc điểm của một ngày, lịch vạn niên sẽ cung cấp nhiều thông tin
                để tham khảo hơn.
            </p>


            <h3>Ngày tốt xấu được xem như thế nào?</h3>

            <p>
                Theo lịch pháp và quan niệm dân gian, việc xem ngày tốt xấu
                thường không dựa vào một yếu tố duy nhất. Tùy từng phương pháp,
                người xem có thể tham khảo Can Chi, ngày hoàng đạo hoặc hắc đạo,
                sao tốt, sao xấu, trực ngày, tuổi xung và tính chất của công việc.
            </p>

            <p>
                Một ngày được xem là thuận lợi cho một công việc chưa chắc đã phù hợp
                với tất cả các công việc khác. Vì vậy, khi tra cứu ngày tốt xấu,
                nên xem tổng hợp nhiều thông tin thay vì chỉ dựa vào một chỉ số riêng lẻ.
            </p>


            <h3>Giờ hoàng đạo hôm nay là gì?</h3>

            <p>
                <strong>Giờ hoàng đạo</strong> là những khung giờ được xem là thuận lợi
                theo cách tính của lịch pháp dân gian. Mỗi ngày có các giờ hoàng đạo
                và giờ hắc đạo khác nhau, thường được tham khảo khi xuất hành,
                bắt đầu công việc hoặc thực hiện một số việc quan trọng.
            </p>

            <p>
                Trên Lịch Âm Tốt, thông tin giờ hoàng đạo được hiển thị theo từng ngày
                để người dùng có thể tra cứu nhanh mà không cần tự tính Can Chi của giờ.
            </p>


            <h3>Tra cứu lịch âm theo ngày, tháng và năm</h3>

            <p>
                Ngoài lịch âm hôm nay, người dùng có thể tra cứu lịch theo từng ngày,
                từng tháng hoặc cả năm. Việc xem lịch theo thời gian giúp dễ dàng
                kiểm tra một ngày trong quá khứ hoặc tương lai, đối chiếu ngày âm dương
                và theo dõi các ngày trong từng tháng.
            </p>

            <p>
                Bạn có thể xem
                <a href="">lịch âm hôm qua</a>,
                <a href="">lịch âm hôm nay</a>,
                <a href="">lịch âm ngày mai</a>,
                <a href="">lịch âm theo tháng</a>
                hoặc
                <a href="">lịch âm theo năm</a>
                tùy theo nhu cầu tra cứu.
            </p>


            <h3>Đổi ngày dương sang ngày âm và ngược lại</h3>

            <p>
                Công cụ <a href="">đổi ngày âm dương</a> giúp tìm ngày âm tương ứng
                với một ngày dương hoặc chuyển từ ngày âm sang ngày dương.
                Tính năng này hữu ích khi cần tra cứu ngày sinh âm lịch, ngày giỗ,
                ngày lễ truyền thống hoặc các mốc thời gian được ghi theo lịch âm.
            </p>


            <h3>Lịch âm trong đời sống người Việt</h3>

            <p>
                Dù dương lịch được sử dụng phổ biến trong công việc và đời sống hằng ngày,
                lịch âm vẫn gắn với nhiều phong tục của người Việt như Tết Nguyên đán,
                ngày rằm, mùng một, giỗ chạp và các ngày lễ truyền thống.
                Vì vậy, nhu cầu tra cứu song song ngày âm và ngày dương
                vẫn rất phổ biến trong đời sống hiện nay.
            </p>

            <p class="knowledge-note">
                Các thông tin về ngày tốt xấu, giờ hoàng đạo, hướng xuất hành
                và các yếu tố lịch pháp trên Lịch Âm Tốt được cung cấp với mục đích
                tra cứu và tham khảo theo văn hóa, lịch pháp và quan niệm dân gian truyền thống.
            </p>

            <p class="cms-links">
                <b>Tra cứu tiếp: </b>
                <a href="{{ route('page.cope.show.month', ['month' => date('m'), 'year' => date('Y')]) }}"
                   title="Lịch âm tháng {{ date('m') }}">Lịch âm tháng {{ date('m') }}</a> ·
                <a href="{{ route('page.cope.show.year', ['year' => date('Y')]) }}" title="Lịch âm năm {{ date('Y') }}">Lịch
                    âm năm {{ date('Y') }}</a> ·
                <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">Đổi
                    ngày âm dương</a>
            </p>
        </div>
    </article>
@endsection

@section('scripts')
@endsection

