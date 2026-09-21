@extends('pages::layouts.master')

@section('content')
    <header class="card convert-heading">
        <span class="section-label">CÔNG CỤ LỊCH VIỆT</span>
        <h1>Đổi ngày âm dương</h1>
        <p>
            Chuyển đổi ngày dương sang âm hoặc ngày âm sang dương, đồng thời xem
            nhanh Can Chi, tiết khí và giờ tốt của ngày đã chọn.
        </p>
    </header>

    @php
        $row = $data['day'];
        [$y, $m, $d] = explode('-', $row['day']);
        [$lunarYear, $lunarMonth, $lunarDay] = explode('-', $row['lunarDay']);
        $thu = \App\Helpers\Helpers::formatVietnameseDateNumber($row['day']);
        $tietkhi = !empty($row['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($row['options']['LUNAR'], 'SOLAR_SEASONS') : '';
        $dayKhongMinh = !empty($row['options']['KONG_MING_FORTUNE_DAY'][0]) ? \App\Helpers\Helpers::getKongMingFortune($row['options']['KONG_MING_FORTUNE_DAY'][0]['value']) : [];
    @endphp
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
                <div class="field">
                    <label>Hình thức chuyển đổi</label>
                    <div class="text-changeAd">Dương lịch sang âm lịch</div>
                </div>
                <div id="pre_solarFields" class="convert-fields">
                    <div class="field convert-field-wide">
                        <label for="pre_convertDate">Chọn ngày dương lịch</label>
                        <input
                            id="pre_convertDate"
                            class="vietnamese-date-input"
                            type="text"
                            name="date"
                            value="{{ $d }}/{{ $m }}/{{ $y }}"
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
                    <label class="pre_leap-check">
                        <input id="pre_lunarLeap" type="checkbox"/> Tháng nhuận
                    </label>
                </div>
                <button class="btn convert-submit" type="submit">
                    Chuyển đổi ngày
                </button>
            </form>
            <p class="convert-hint">
                Dữ liệu demo sẽ được thay bằng kết quả chính xác từ API lịch pháp.
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
    <section class="section-block card calendar-card">
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

    <section class="section-block convert-guide-grid">
        <article class="card convert-guide">
            <span class="section-label">HƯỚNG DẪN TRA CỨU</span>
            <h2>Cách đổi ngày dương sang âm và ngược lại</h2>
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

            <p class="cms-links">
                <b>Tra cứu tiếp:</b>
                <a href="{{ route('page.cope.show.day', ['day' => date('d'), 'month' => date('m'), 'year' => date('Y')]) }}" title="Âm lịch hôm nay">Âm lịch hôm nay</a> ·
                <a href="{{ route('page.cope.show.month', ['month' => date('m'), 'year' => date('Y')]) }}" title="Lịch âm tháng {{ date('m') }}">Lịch âm tháng {{ date('m') }}</a> ·
                <a href="{{ route('page.cope.show.year', ['year' => date('Y')]) }}" title="Lịch âm năm {{ date('Y') }}">Lịch âm năm {{ date('Y') }}</a>
            </p>
        </div>
    </article>
@endsection
