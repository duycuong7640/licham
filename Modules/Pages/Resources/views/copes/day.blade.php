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
        $prevUrl = $date->year <= 1900 ? '' : route('page.cope.show.day', ['day' => $date->copy()->subDay()->format('d-m-Y')]);
        $nowUrl = $date->year <= 1900 ? '' : route('page.cope.show.day', ['day' => $date->format('d-m-Y')]);
        $nextUrl = $date->year >= 2500 ? '' : route('page.cope.show.day', ['day' => $date->copy()->addDay()->format('d-m-Y')]);
    @endphp
    @push('schema')
        @php
            $pageUrl = $data['seo']['canonical'] ?? request()->url();
            $homeUrl = rtrim(route('page.home'), '/');
            $pageTitle = $data['seo']['title_seo'] ?? '';
            $pageDescription = $data['seo']['meta_des'] ?? '';

            $schema = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'WebPage',
                        '@id' => $pageUrl . '#webpage',
                        'url' => $pageUrl,
                        'name' => $pageTitle,
                        'description' => $pageDescription,
                        'isPartOf' => [
                            '@id' => $homeUrl . '#website',
                        ],
                        'about' => [
                            '@type' => 'Thing',
                            'name' => 'Ngày ' . $day . ' tháng ' . $month . ' năm ' . $year,
                        ],
                        'breadcrumb' => [
                            '@id' => $pageUrl . '#breadcrumb',
                        ],
                        'inLanguage' => 'vi-VN',
                    ],
                    [
                        '@type' => 'BreadcrumbList',
                        '@id' => $pageUrl . '#breadcrumb',
                        'itemListElement' => [
                            [
                                '@type' => 'ListItem',
                                'position' => 1,
                                'name' => 'Trang chủ',
                                'item' => $homeUrl,
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 2,
                                'name' => 'Xem ngày tốt xấu',
                                'item' => url('/xem-ngay-tot-xau'),
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 3,
                                'name' => 'Ngày ' . $day . '/' . $month . '/' . $year,
                                'item' => $pageUrl,
                            ],
                        ],
                    ],
                ],
            ];
        @endphp

        <script type="application/ld+json">
            {!! json_encode(
                $schema,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS
                | JSON_HEX_QUOT
            ) !!}
        </script>
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Tra cứu ngày tốt xấu</p>
            <h1>Ngày {{ $day }} tháng {{ $month }} năm {{ $year }} tốt hay xấu?</h1>
            <p>Xem chi tiết ngày dương lịch, âm lịch, ngày giờ hoàng đạo, hướng xuất hành và các việc nên làm để bạn chủ
                động chọn thời điểm phù hợp.</p>
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

        <section class="day-hero-card" aria-labelledby="dayTitle">
            <div class="day-hero-top">
                <a href="{{ $prevUrl }}" aria-label="Ngày trước"
                   data-day-nav="prev">‹</a>
                <strong id="dayTitle">Tháng {{ $month }} năm {{ $year }}</strong>
                <a href="{{ $nextUrl }}" aria-label="Ngày sau"
                   data-day-nav="next">›</a>
            </div>
            <div class="day-hero-body">
                <div class="day-hero-main">
                    <div class="day-number" data-day-detail="solar-day">{{ $day }}</div>
                    <h2 data-day-detail="weekday">{{ \App\Helpers\Helpers::formatVietnameseDateNumber($row['day']) }}</h2>
                    {!! $row['description'] !!}
                </div>

                <div class="day-core-grid">
                    <div class="lunar-month"
                         data-day-detail="lunar-month">{{ \App\Helpers\Helpers::monthToText($month) }}</div>
                    <article class="day-core-card day-stems-card">
                        <p>Ngày: <strong data-day-detail="day-can-chi">{{ $row['strDay'] }}</strong></p>
                        <p>Tháng: <strong data-day-detail="month-can-chi">{{ $row['strMonth'] }}</strong></p>
                        <p>Năm: <strong data-day-detail="year-can-chi">{{ $row['strYear'] }}</strong></p>
                        <p>Thời gian: <strong data-day-detail="hour-can-chi">{{ date('H:i') }}</strong></p>
                    </article>
                    <article class="day-core-card lunar-card">
                        <strong data-day-detail="lunar-day">
                            {{ $lunarDay }}
                            <span style="font-size: 13px;">{{ $lunarMonth }}/{{ $lunarYear }}</span>
                        </strong>
                        <span class="lunar-animal">
                            <img src="{{ \App\Helpers\Helpers::getZodiacOrder($row['strYear']) }}">
                        </span>
                    </article>
                    <article class="day-core-card quality-card">
                        <p>Là ngày: <strong
                                data-day-detail="day-quality">{{ $row['isDay'] ? 'Hoàng đạo' : 'Hắc đạo' }}</strong></p>
                        <p>Trực:
                            <strong>{{ !empty($row['options']['DAY_OFFICER']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['DAY_OFFICER'], 'DAY_OFFICER') : '' }}</strong>
                        </p>
                        <p>Tiết khí:
                            <strong>{{ !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'SOLAR_SEASONS') : '' }}</strong>
                        </p>
                        <p>Giờ: <strong>{{ \App\Helpers\Helpers::getCurrentGioCanChi() }}</strong></p>
                    </article>
                </div>

                <div class="good-hours">
                    <div class="section-title-row">
                        <h2>Giờ hoàng đạo</h2>
                        <span>Giờ tốt trong ngày</span>
                    </div>
                    <div class="hour-chip-grid">
                        @if(!empty($row['options']['AUSPICIOUS_HOUR']))
                            @foreach($row['options']['AUSPICIOUS_HOUR'] as $value)
                                @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                                <span>{{ !empty($time['title']) ? $time['title'] : '' }} <b>{{ !empty($time['hour']) ? $time['hour'] : '' }}</b></span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="day-switch-bar">
                <a href="{{ $prevUrl }}" data-day-nav="prev">Hôm qua</a>
                <a class="active" href="{{ $nowUrl }}" data-day-nav="today">Hôm nay</a>
                <a href="{{ $nextUrl }}" data-day-nav="next">Ngày mai</a>
            </div>
        </section>

        <section class="day-table-section" aria-labelledby="tableTitle">
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

                @if(!empty($row['options']['PENG_ZU_TABOOS']))
                    <article class="info-table-card wide">
                        <h3>Bành tổ bách kỵ</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['PENG_ZU_TABOOS'] as $value)
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

                @if(!empty($row['options']['HISTORICAL_IN_EVENTS']))
                    <article class="info-table-card wide">
                        <h3>Ngày này năm xưa</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['HISTORICAL_IN_EVENTS'] as $value)
                                    @php
                                        $step = explode('(//)', $value['value']);
                                    @endphp
                                    @if(!empty($step[0]))
                                        <tr>
                                            <th><strong>{!! $step[0] !!}</strong></th>
                                            <td class="wrap-content">{!! $step[1] !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                @if(!empty($row['options']['HISTORICAL_OUT_EVENTS']))
                    <article class="info-table-card wide">
                        <h3>Sự kiện quốc tế</h3>
                        <div class="responsive-table">
                            <table>
                                <tbody>
                                @foreach($row['options']['HISTORICAL_OUT_EVENTS'] as $value)
                                    @php
                                        $step = explode('(//)', $value['value']);
                                    @endphp
                                    @if(!empty($step[0]))
                                        <tr>
                                            <th><strong>{!! $step[0] !!}</strong></th>
                                            <td class="wrap-content">{!! $step[1] !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endif

                <article class="info-table-card">
                    <h3>Thông tin</h3>
                    <div class="responsive-table">
                        <div class="wrap-content">
                            @php
                                $siteName = env('SITENAME', config('app.name'));

                                $isHoangDao = !empty($row['isDay']) ? 'Hoàng đạo' : 'Hắc đạo';

                                $goodHours = collect($row['options']['AUSPICIOUS_HOUR'] ?? [])
                                    ->pluck('value')
                                    ->map(fn($v) => trim(strip_tags($v)))
                                    ->filter()
                                    ->implode(', ');

                                $incompatible = !empty($row['options']['INCOMPATIBLE_AGES'])
                                    ? trim(strip_tags(\App\Helpers\Helpers::getCopeValueByOption(
                                        $row['options']['INCOMPATIBLE_AGES'],
                                        'INCOMPATIBLE_AGES',
                                        false
                                    )))
                                    : '';

                                $directions = collect($row['options']['DIRECTION_OF_TRAVEL'] ?? [])
                                    ->pluck('value')
                                    ->map(fn($v) => trim(strip_tags($v)))
                                    ->filter()
                                    ->implode(' ');

                                $trucValue = !empty($row['options']['DAY_OFFICER'])
                                    ? trim(strip_tags(\App\Helpers\Helpers::getCopeValueByOption(
                                        $row['options']['DAY_OFFICER'],
                                        'DAY_OFFICER'
                                    )))
                                    : '';

                                $goodStars = collect($row['options']['GOOD_STAR'] ?? [])
                                    ->pluck('value')
                                    ->map(fn($v) => trim(strip_tags($v)))
                                    ->filter()
                                    ->take(6)
                                    ->implode('; ');

                                $badStars = collect($row['options']['BAD_STAR'] ?? [])
                                    ->pluck('value')
                                    ->map(fn($v) => trim(strip_tags($v)))
                                    ->filter()
                                    ->take(6)
                                    ->implode('; ');

                                $paragraphs = [];

                                // MỞ ĐẦU
                                $paragraphs[] = "<p><strong>Lịch Vạn Niên {$year}</strong> - Lịch Vạn Sự - Xem ngày đẹp, xem ngày tốt xấu, ngày {$day} tháng {$month} năm {$year}, tức ngày {$lunarDay}-{$lunarMonth}-{$lunarYear} âm lịch, là ngày <strong>{$isHoangDao}</strong>. Thông tin dưới đây được <a href='" . route('page.home') . "' title='{$siteName}'>{$siteName}</a> tổng hợp nhằm giúp bạn tra cứu nhanh giờ hoàng đạo, tuổi xung ngày, hướng xuất hành, trực ngày cùng các yếu tố cát hung quan trọng trước khi thực hiện những công việc liên quan đến cưới hỏi, khai trương, động thổ, nhập trạch, xuất hành hoặc ký kết hợp đồng.</p>";

                                // GIỜ TỐT
                                if ($goodHours) {
                                    $paragraphs[] = "<p>Trong ngày này, các khung giờ được đánh giá có nhiều cát khí bao gồm {$goodHours}. Đây là những khoảng thời gian thường được lựa chọn để bắt đầu công việc mới, tiến hành giao dịch, gặp gỡ đối tác hoặc thực hiện các kế hoạch quan trọng với mong muốn mọi việc diễn ra thuận lợi và hanh thông hơn.</p>";
                                }

                                // TUỔI XUNG
                                if ($incompatible) {
                                    $paragraphs[] = "<p>Xét theo hệ thống Can Chi, các tuổi xung khắc với ngày gồm {$incompatible}. Những người thuộc nhóm tuổi này không nhất thiết phải kiêng kỵ hoàn toàn, tuy nhiên nên cân nhắc kỹ lưỡng hơn khi tiến hành việc lớn hoặc kết hợp lựa chọn giờ tốt, hướng tốt để gia tăng sự yên tâm và hạn chế những yếu tố chưa thuận lợi.</p>";
                                }

                                // HƯỚNG XUẤT HÀNH
                                if ($directions) {
                                    $paragraphs[] = "<p>Về phương diện xuất hành, các hướng cát lợi trong ngày được xác định là {$directions}. Theo quan niệm dân gian, việc lựa chọn đúng phương vị khi bắt đầu hành trình hoặc triển khai công việc quan trọng có thể góp phần tạo nên tâm lý tích cực và mang lại nhiều thuận lợi hơn trong quá trình thực hiện.</p>";
                                }

                                // SAO + TRỰC
                                $starContent = [];

                                if ($trucValue) {
                                    $starContent[] = "ngày thuộc Trực {$trucValue}";
                                }

                                if ($goodStars) {
                                    $starContent[] = "có sự hiện diện của các cát tinh như {$goodStars}";
                                }

                                if ($badStars) {
                                    $starContent[] = "đồng thời cũng xuất hiện một số hung tinh cần lưu ý như {$badStars}";
                                }

                                if (!empty($starContent)) {
                                    $paragraphs[] = "<p>Xét theo các yếu tố lịch pháp cổ truyền, " . implode(', ', $starContent) . ". Vì vậy, khi xem ngày tốt xấu, bạn nên đánh giá tổng thể dựa trên trực ngày, sao tốt, sao xấu, tuổi của người thực hiện công việc và mục đích cụ thể thay vì chỉ dựa vào một yếu tố riêng lẻ.</p>";
                                }

                                // KẾT LUẬN
                                $paragraphs[] = "<p>Tổng hợp các dữ liệu về ngày {$day}/{$month}/{$year}, bao gồm trạng thái {$isHoangDao}, giờ tốt, tuổi xung khắc, hướng xuất hành, trực ngày cùng hệ thống sao cát hung, có thể thấy đây là những thông tin hữu ích giúp bạn tham khảo trước khi đưa ra quyết định đối với các công việc quan trọng. Tuy nhiên, việc xem ngày tốt xấu chỉ nên được xem là một yếu tố hỗ trợ, bởi kết quả cuối cùng vẫn phụ thuộc vào sự chuẩn bị, năng lực thực hiện, hoàn cảnh thực tế và tinh thần chủ động của mỗi người.</p>";

                                $paragraphs[] = "<p>Thông tin được {$siteName} biên soạn dựa trên các nguyên tắc của lịch âm dương, Can Chi, Ngũ Hành, hệ thống Trực ngày, Nhị Thập Bát Tú và nhiều tài liệu lịch pháp phương Đông được sử dụng phổ biến hiện nay. Nội dung mang giá trị tham khảo nhằm phục vụ nhu cầu tra cứu hằng ngày, giúp bạn có thêm góc nhìn trước khi lựa chọn thời điểm thích hợp cho những kế hoạch trong công việc cũng như cuộc sống.</p>";

                                $paragraphs[] = "<p>Cảm ơn bạn đã sử dụng hệ thống tra cứu của <strong>{$siteName}</strong>. Chúng tôi sẽ liên tục cập nhật dữ liệu Lịch Vạn Niên, lịch âm hôm nay, ngày hoàng đạo, ngày hắc đạo, giờ hoàng đạo, tuổi hợp tuổi xung, hướng xuất hành và nhiều kiến thức tử vi phong thủy hữu ích khác. Hy vọng những thông tin này sẽ giúp bạn dễ dàng tra cứu, chủ động sắp xếp công việc và có thêm sự tự tin khi bắt đầu những dự định quan trọng trong tương lai.</p>";

                                $finalText = implode('', $paragraphs);
                            @endphp
                            {!! $finalText !!}
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
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

            let url = @json(route('page.cope.show.day', ['day' => '__DATE__']));
            url = url.replace('__DATE__', date);

            window.location.href = url;
        });
    </script>
@endsection
