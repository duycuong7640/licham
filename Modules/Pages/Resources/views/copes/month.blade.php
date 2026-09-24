@extends('pages::layouts.master')

@section('content')
    @php
        $months = \App\Helpers\Helpers::buildCalendar($data['lists']);
        $rowDay = $data['day'];
        $todayDate = $data['day']['day'];
        $monthButton = $data['mData'];
        $monthButton['month'] = \App\Helpers\Helpers::checkNumber($monthButton['month']);
        $monthButton['year'] = \App\Helpers\Helpers::checkNumber($monthButton['year']);
        $data['month'] = $monthButton['month'];
        $data['year'] = $monthButton['year'];
    @endphp
    <section class="card month-page-head">
        <div>
            <span class="section-label">LỊCH VẠN NIÊN THEO THÁNG</span>
            <h1 id="pre_monthPageTitle">Lịch âm tháng {{ $monthButton['month'] }} năm {{ $monthButton['year'] }}</h1>
            <p>
                Xem ngày âm, ngày tốt xấu, ngày lễ và thông tin cần biết trong tháng.
            </p>
        </div>
        <div class="month-toolbar"
             data-url="{{ route('page.cope.show.month', ['month' => '__MONTH__', 'year' => '__YEAR__']) }}">
            <div class="field">
                <label for="pre_monthSelect">Tháng</label>
                <select id="pre_monthSelect">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $monthButton['month'] ? 'selected' : '' }}>
                            Tháng {{ $m }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="field">
                <label for="pre_yearSelect">Năm</label>
                <select id="pre_yearSelect">
                    @for($y = 1900; $y <= 2050; $y++)
                        <option value="{{ $y }}" {{ $y == $monthButton['year'] ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
    </section>
    <section class="section-block card calendar-card">
        <div class="section-head" id="lich-thang">
            <div>
                <span class="section-label">LỊCH ÂM DƯƠNG</span>
                <h2 id="pre_monthTitle">Tháng {{ $data['month'] }} năm {{ $data['year'] }}</h2>
            </div>
            <div class="month-controls">
                <a href="{{ route('page.cope.show.month', ['month' => \App\Helpers\Helpers::checkNumber($monthButton['prev']['month']), 'year' => $monthButton['prev']['year']]) }}"
                   title="Xem lịch tháng {{ \App\Helpers\Helpers::checkNumber($monthButton['prev']['month']) }} năm {{ $monthButton['prev']['year'] }}"
                   aria-label="Xem lịch tháng {{ \App\Helpers\Helpers::checkNumber($monthButton['prev']['month']) }} năm {{ $monthButton['prev']['year'] }}">‹</a>
                <a href="{{ route('page.cope.show.month', ['month' => $data['month'], 'year' => $data['year']]) }}"
                   title="Xem lịch tháng {{ $data['month'] }} năm {{ $data['year'] }}"
                   aria-label="Xem lịch tháng {{ $data['month'] }} năm {{ $data['year'] }}">Tháng này</a>
                <a href="{{ route('page.cope.show.month', ['month' => \App\Helpers\Helpers::checkNumber($monthButton['next']['month']), 'year' => $monthButton['next']['year']]) }}"
                   title="Xem lịch tháng {{ \App\Helpers\Helpers::checkNumber($monthButton['next']['month']) }} năm {{ $monthButton['next']['year'] }}"
                   aria-label="Xem lịch tháng {{ \App\Helpers\Helpers::checkNumber($monthButton['next']['month']) }} năm {{ $monthButton['next']['year'] }}">›</a>
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
                           class="calendar-day {{ $isDay }} {{ $isSaturday }} {{ $isSunday }} {{ $today }} {{ $today }}"
                           data-date="{{ \Carbon\Carbon::parse($month['date'], 'Asia/Ho_Chi_Minh')->utc()->format('Y-m-d\TH:i:s.v\Z') }}"
                           aria-label="Ngày {{ $month['day'] }} tháng {{ $month['month'] }}, âm lịch {{ $month['lunarDay'] }} tháng {{ $month['lunarMonth'] }}">
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
    <section class="section-block month-insights">
        <article class="card month-day-list good-list" id="ngay-hoang-dao">
            <span class="section-label">NGÀY HOÀNG ĐẠO</span>
            <h2>Ngày tốt trong tháng {{ $monthButton['month'] }} năm {{ $monthButton['year'] }}</h2>
            <div id="pre_goodDays" class="date-link-grid">
                @foreach($months as $values)
                    @foreach($values as $month)
                        @if(!empty($month['id']) && $month['isDay'])
                            <a href="{{ route('page.cope.show.day', ['day' => $month['day'], 'month' => $month['month'], 'year' => $month['year']]) }}">Ngày {{ \App\Helpers\Helpers::checkNumber($month['day']) }}/{{ \App\Helpers\Helpers::checkNumber($month['month']) }}/{{ $month['year'] }}</a>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </article>
        <article class="card month-day-list bad-list">
            <span class="section-label">NGÀY HẮC ĐẠO</span>
            <h2>Ngày xấu trong tháng {{ $monthButton['month'] }} năm {{ $monthButton['year'] }}</h2>
            <div id="pre_badDays" class="date-link-grid">
                @foreach($months as $values)
                    @foreach($values as $month)
                        @if(!empty($month['id']) && !$month['isDay'])
                            <a href="{{ route('page.cope.show.day', ['day' => $month['day'], 'month' => $month['month'], 'year' => $month['year']]) }}">Ngày {{ \App\Helpers\Helpers::checkNumber($month['day']) }}/{{ \App\Helpers\Helpers::checkNumber($month['month']) }}/{{ $month['year'] }}</a>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </article>
    </section>
    <section class="section-block" id="ngay-xuat-hanh">
        <div class="section-head">
            <div>
                <span class="section-label">NGÀY XUẤT HÀNH ÂM LỊCH</span>
                <h2>Danh sách chi tiết ngày xuất hành âm lịch</h2>
            </div>
        </div>
        <div class="detail-table">
            @foreach($months as $values)
                @foreach($values as $month)
                    @if(!empty($month['id']))
                        @php
                            $dayKhongMinh = !empty($month['options']['KONG_MING_FORTUNE_DAY'][0]) ? \App\Helpers\Helpers::getKongMingFortune($month['options']['KONG_MING_FORTUNE_DAY'][0]['value']) : [];
                        @endphp
                        <div class="detail-row detail-row-custom">
                            <div class="detail-content">
                                {{ \App\Helpers\Helpers::checkNumber($month['lunarDay']) }}/{{ \App\Helpers\Helpers::checkNumber($month['lunarMonth']) }} - Ngày
                                <strong>{{ !empty($dayKhongMinh[0]) ? $dayKhongMinh[0] : '' }}</strong>: {{ !empty($dayKhongMinh[1]) ? $dayKhongMinh[1] : '' }}
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </section>
    @if(!empty($data['historical_events']))
        <section class="section-block" id="su-kien">
            <div class="section-head">
                <div>
                    <span class="section-label">SỰ KIỆN LỊCH SỬ</span>
                    <h2>Danh sách chi tiết sự kiện lịch sử tháng {{ $monthButton['month'] }}</h2>
                </div>
            </div>
            <div class="detail-table">
                @foreach($data['historical_events'] as $event)
                    <div class="detail-row">
                        <h3>{{ \App\Helpers\Helpers::checkNumber($event['day']) }}/{{ \App\Helpers\Helpers::checkNumber($event['month']) }}/{{ $event['year'] }}</h3>
                        <div class="detail-content">
                            {!! $event['value'] !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
    @php
        $monthDays = collect($months)
            ->flatten(1)
            ->filter(fn($item) => !empty($item['id']))
            ->values();

        $goodDaysCount = $monthDays
            ->filter(fn($item) => !empty($item['isDay']))
            ->count();

        $badDaysCount = $monthDays
            ->filter(fn($item) => empty($item['isDay']))
            ->count();

        $importantEvents = [];
        $importantEventDates = dataKey::HISTORIES_IMPORTANT_LISTS[(int)$data['month']] ?? [];

        if (!empty($data['historical_events'])) {
            foreach ($data['historical_events'] as $event) {

                $eventDate =
                    \App\Helpers\Helpers::checkNumber($event['day'])
                    . '/'
                    . \App\Helpers\Helpers::checkNumber($event['month'])
                    . '/'
                    . $event['year'];

                if (in_array($eventDate, $importantEventDates)) {
                    $importantEvents[] = $event;

                    if (count($importantEvents) >= 3) {
                        break;
                    }
                }
            }
        }
    @endphp
    <section class="section-block month-information">
        <article class="card month-seo">
            <span class="section-label">
                THÔNG TIN TRONG THÁNG
            </span>
            <h2 id="pre_seoMonthTitle">
                Tổng quan lịch âm tháng {{ $data['month'] }} năm {{ $data['year'] }}
            </h2>
            <p>
                <strong>
                    Lịch âm tháng {{ $data['month'] }}/{{ $data['year'] }}
                </strong>
                giúp đối chiếu ngày dương và ngày âm, tra cứu Can Chi,
                ngày hoàng đạo – hắc đạo, giờ tốt và thông tin lịch pháp
                của từng ngày trong tháng.
                Tháng này có
                <strong>{{ $goodDaysCount }} ngày hoàng đạo</strong>
                và
                <strong>{{ $badDaysCount }} ngày hắc đạo</strong>.
            </p>

            <h3>Cách xem lịch tháng</h3>

            <p>
                Số lớn trong mỗi ô là ngày dương lịch, số nhỏ là ngày âm.
                Di chuột hoặc chạm vào từng ngày để xem nhanh Can Chi,
                giờ hoàng đạo và thông tin phù hợp; chọn ngày để xem
                đầy đủ thông tin chi tiết.
            </p>

            <h3>Lưu ý khi xem ngày tốt xấu</h3>

            <p>
                Một ngày có thể thuận lợi theo yếu tố này nhưng chưa phù hợp
                theo yếu tố khác. Vì vậy, nên đối chiếu thêm trực ngày,
                sao tốt – sao xấu, tuổi xung và mục đích công việc.
                Các thông tin lịch pháp mang tính tra cứu và tham khảo.
            </p>
        </article>
        <aside class="card month-events">
            <span class="section-label">NGÀY LỄ · SỰ KIỆN</span>
            <h2>Dấu mốc trong tháng</h2>
            <ul id="pre_monthEvents">
                @php
                    $dem = 0;
                    $histories_important = dataKey::HISTORIES_IMPORTANT_LISTS[(int)$data['month']];
                @endphp
                @foreach($data['historical_events'] as $event)
                    @php $evDay = \App\Helpers\Helpers::checkNumber($event['day']).'/'.\App\Helpers\Helpers::checkNumber($event['month']).'/'.\App\Helpers\Helpers::checkNumber($event['year']) @endphp
                    @if(in_array($evDay, $histories_important))
                        <li>
                            <time>{{ \App\Helpers\Helpers::checkNumber($event['day']).'/'.\App\Helpers\Helpers::checkNumber($event['month']) }}</time>
                            <span class="text-event"><b>{{ strip_tags($event['value']) }}</b></span>
                        </li>
                        @php $dem ++; @endphp
                        @if($dem >= 3) @break @endif
                    @endif
                @endforeach
            </ul>
            <a id="pre_yearOverviewLink" href="{{ route('page.cope.show.year', ['year' => $data['year']]) }}"
               title="Xem lịch năm {{ $data['year'] }}">
                Xem toàn bộ lịch năm →
            </a>
        </aside>
    </section>
    <article class="card seo-analysis" aria-labelledby="convert-seo-title">
        <div class="cms-content">
            <span class="section-label">
            TRA CỨU LIÊN QUAN
        </span>

            <h2 id="month-related-title">
                Tra cứu lịch âm theo ngày, tháng và năm
            </h2>

            <p>
                Trang lịch âm tháng {{ $data['month'] }}/{{ $data['year'] }}
                giúp theo dõi toàn bộ ngày âm dương trong tháng và tra cứu nhanh
                ngày hoàng đạo, ngày hắc đạo, Can Chi, giờ tốt cùng thông tin
                lịch khác liên quan. Nếu cần xem kỹ một ngày cụ thể,
                bạn có thể chọn trực tiếp ngày đó trên bảng lịch để mở
                trang thông tin chi tiết.
            </p>

            <p>
                Ngoài việc xem lịch theo tháng, bạn có thể
                <a href="{{ route('page.home') }}" title="Lịch âm hôm nay">
                    xem lịch âm hôm nay
                </a>
                để tra cứu ngày hiện tại,
                <a href="{{ route('page.cope.show.year', ['year' => $data['year']]) }}" title="Lịch âm năm {{ $data['year'] }}">
                    xem lịch âm năm {{ $data['year'] }}
                </a>
                để theo dõi đầy đủ 12 tháng trong năm hoặc sử dụng
                <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">
                    công cụ đổi ngày âm dương
                </a>
                khi cần tìm ngày âm tương ứng với ngày dương và ngược lại.
            </p>

            <p>
                Khi tra cứu ngày tốt xấu, nên xem thông tin của từng ngày
                trong bối cảnh cụ thể thay vì chỉ dựa vào một tiêu chí riêng lẻ.
                Các dữ liệu trên Lịch Âm Tốt được trình bày nhằm hỗ trợ tra cứu
                lịch Việt và tham khảo các quan niệm lịch pháp truyền thống.
            </p>

            <p class="cms-links">
                <b>Tra cứu tiếp: </b>
                <a href="{{ route('page.cope.show.day', ['day' => (int) date('d'), 'month' => (int) date('m'), 'year' => date('Y')]) }}" title="Âm lịch hôm nay">Âm lịch hôm nay</a> ·
                <a href="{{ route('page.cope.show.year', ['year' => date('Y')]) }}" title="Lịch âm năm {{ date('Y') }}">Lịch âm năm {{ date('Y') }}</a> ·
                <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">Đổi ngày âm dương</a>
            </p>
        </div>
    </article>
@endsection

@section('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const toolbar = document.querySelector('.month-toolbar');
            const monthSelect = document.getElementById('pre_monthSelect');
            const yearSelect = document.getElementById('pre_yearSelect');

            if (!toolbar || !monthSelect || !yearSelect) {
                return;
            }

            function changeMonth() {
                const month = monthSelect.value;
                const year = yearSelect.value;

                const url = toolbar.dataset.url
                    .replace('__MONTH__', month)
                    .replace('__YEAR__', year);

                window.location.href = url;
            }

            monthSelect.addEventListener('change', changeMonth);
            yearSelect.addEventListener('change', changeMonth);
        });
    </script>
@endsection
