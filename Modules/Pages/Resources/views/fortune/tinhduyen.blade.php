@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.fortune-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Bói vui - Tình duyên</p>
            <h1>Gieo Quẻ Tình Duyên: Xem Đường Tình Cảm Năm {{ date('Y') }} Của Bạn</h1>
            <p>Bói tình yêu theo thần số học và cung hoàng đạo. Giải mã thần giao cách cảm, độ hòa hợp và dự báo đường
                tình duyên chuẩn xác nhất cho cặp đôi.</p>
        </div>

        @php
            $maleSolarDate = request('maleSolarDate', '1995-05-15');
            $femaleSolarDate = request('femaleSolarDate', '1998-08-18');

            [$maleYear, $maleMonth, $maleDay] = explode('-', $maleSolarDate);
            [$femaleYear, $femaleMonth, $femaleDay] = explode('-', $femaleSolarDate);
        @endphp

        <form class="fortune-date-lookup-row" action="" method="get" aria-label="Chọn ngày cần xem">
            <input type="hidden" name="maleSolarDate" id="maleSolarDate" value="{{ $maleSolarDate }}">
            <input type="hidden" name="femaleSolarDate" id="femaleSolarDate" value="{{ $femaleSolarDate }}">

            <div class="fortune-picker-container">
                <label class="fortune-label">Ngày sinh Nam (Dương lịch)</label>
                <div class="fortune-input-wrapper">
                    <input type="text" id="fortune-display-1" class="fortune-input" readonly placeholder="Ngày sinh Nam..." autocomplete="off">

                    <div id="fortune-group-1" class="fortune-select-group fortune-hidden">
                        <select id="fortune-day-1"><option value="">Ngày</option></select>
                        <select id="fortune-month-1"><option value="">Tháng</option></select>
                        <select id="fortune-year-1"><option value="">Năm</option></select>
                    </div>
                </div>
            </div>

            <div class="fortune-picker-container">
                <label class="fortune-label">Ngày sinh Nữ (Dương lịch)</label>
                <div class="fortune-input-wrapper">
                    <input type="text" id="fortune-display-2" class="fortune-input" readonly placeholder="Ngày sinh Nữ..." autocomplete="off">

                    <div id="fortune-group-2" class="fortune-select-group fortune-hidden">
                        <select id="fortune-day-2"><option value="">Ngày</option></select>
                        <select id="fortune-month-2"><option value="">Tháng</option></select>
                        <select id="fortune-year-2"><option value="">Năm</option></select>
                    </div>
                </div>
            </div>

            <button type="submit" class="fortune-btn-submit-inline btn-sm-form">Xem kết quả</button>
        </form>

        <div class="wrap-content-love">
            @if(!empty($data['detail']))
                @php
                    $detail = $data['detail'];
                    $reading = $detail['reading'] ?? [];
                @endphp

                <div class="love-result-box">
                    <h2>{{ $reading['headline'] ?? 'Kết quả bói tình duyên' }}</h2>

                    @if(!empty($reading['summary']))
                        <p class="love-summary">{{ $reading['summary'] }}</p>
                    @endif

                    <div class="love-score-box">
                        <p>
                            <strong>{{ $detail['totalScore'] ?? 0 }}/{{ $detail['maxScore'] ?? 10 }}</strong>
                            <span>{{ $detail['percent'] ?? 0 }}%</span>
                            <span>{{ $detail['level'] ?? '' }}</span>
                        </p>
                    </div>

                    <div class="love-person-grid">
                        <div>
                            <h3>Nam mệnh</h3>
                            <p><strong>Ngày dương:</strong> <span>{{ $detail['male']['solarDate'] ?? '' }}</span></p>
                            <p><strong>Ngày âm:</strong> <span>{{ $detail['male']['lunarDate'] ?? '' }}</span></p>
                            <p><strong>Tuổi:</strong> <span>{{ $detail['male']['canChi'] ?? '' }}</span></p>
                            <p><strong>Nạp âm:</strong> <span>{{ $detail['male']['napAm']['name'] ?? '' }} - {{ $detail['male']['napAm']['elementLabel'] ?? '' }}</span></p>
                            <p><strong>Cung phi:</strong> <span>{{ $detail['male']['palace']['name'] ?? '' }} - {{ $detail['male']['palace']['elementLabel'] ?? '' }}</span></p>
                        </div>

                        <div>
                            <h3>Nữ mệnh</h3>
                            <p><strong>Ngày dương:</strong> <span>{{ $detail['female']['solarDate'] ?? '' }}</span></p>
                            <p><strong>Ngày âm:</strong> <span>{{ $detail['female']['lunarDate'] ?? '' }}</span></p>
                            <p><strong>Tuổi:</strong> <span>{{ $detail['female']['canChi'] ?? '' }}</span></p>
                            <p><strong>Nạp âm:</strong> <span>{{ $detail['female']['napAm']['name'] ?? '' }} - {{ $detail['female']['napAm']['elementLabel'] ?? '' }}</span></p>
                            <p><strong>Cung phi:</strong> <span>{{ $detail['female']['palace']['name'] ?? '' }} - {{ $detail['female']['palace']['elementLabel'] ?? '' }}</span></p>
                        </div>
                    </div>

                    @if(!empty($reading['chemistry']))
                        <div class="love-card">
                            <h3>Độ hút tình cảm</h3>
                            <p>{{ $reading['chemistry'] }}</p>
                        </div>
                    @endif

                    @if(!empty($reading['diagnosis']))
                        <div class="love-card">
                            <h3>Chẩn đoán tổng quan</h3>

                            @foreach($reading['diagnosis'] as $title => $content)
                                <p>
                                    <strong>{{ ucfirst($title) }}:</strong>
                                    <span>{{ $content }}</span>
                                </p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($detail['criteria']))
                        <h3 class="love-section-title">Luận giải từng tiêu chí</h3>

                        @foreach($detail['criteria'] as $item)
                            <div class="love-card">
                                <h3>{{ $item['title'] ?? '' }}</h3>

                                <p>
                                    <strong>{{ $item['rating'] ?? '' }}</strong>
                                    <span>{{ $item['score'] ?? 0 }}/{{ $item['maxScore'] ?? 0 }} điểm</span>
                                </p>

                                <p>{{ $item['conclusion'] ?? '' }}</p>

                                @if(!empty($item['details']))
                                    @foreach($item['details'] as $line)
                                        <p><span>{{ $line }}</span></p>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    @endif

                    @if(!empty($reading['strengths']) || !empty($reading['risks']))
                        <div class="love-two-col">
                            @if(!empty($reading['strengths']))
                                <div class="love-card love-good">
                                    <h3>Điểm sáng</h3>
                                    @foreach($reading['strengths'] as $item)
                                        <p><span>{{ $item }}</span></p>
                                    @endforeach
                                </div>
                            @endif

                            @if(!empty($reading['risks']))
                                <div class="love-card love-bad">
                                    <h3>Điểm cần hóa giải</h3>
                                    @foreach($reading['risks'] as $item)
                                        <p><span>{{ $item }}</span></p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(!empty($reading['focus']))
                        <div class="love-card">
                            <h3>Trọng tâm cần nhớ</h3>
                            <p><strong>Mạnh nhất:</strong> <span>{{ $reading['focus']['strongest'] ?? '' }}</span></p>
                            <p><strong>Cần hóa giải:</strong> <span>{{ $reading['focus']['needsHealing'] ?? '' }}</span></p>
                            <p><strong>Lời khuyên:</strong> <span>{{ $reading['focus']['oneLineAdvice'] ?? '' }}</span></p>
                        </div>
                    @endif

                    @if(!empty($reading['decisionGuide']))
                        <div class="love-card">
                            <h3>Gợi ý trước khi quyết định</h3>

                            @if(!empty($reading['decisionGuide']['timing']))
                                <p>{{ $reading['decisionGuide']['timing'] }}</p>
                            @endif

                            @if(!empty($reading['decisionGuide']['actions']))
                                @foreach($reading['decisionGuide']['actions'] as $action)
                                    <p><span>{{ $action }}</span></p>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    @if(!empty($reading['questionsBeforeMarriage']))
                        <div class="love-card">
                            <h3>Câu hỏi nên hỏi nhau trước khi đi xa</h3>

                            @foreach($reading['questionsBeforeMarriage'] as $question)
                                <p><span>{{ $question }}</span></p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($reading['reportSections']))
                        <h3 class="love-section-title">Luận giải chi tiết</h3>

                        @foreach($reading['reportSections'] as $section)
                            <div class="love-card love-report-section">
                                <h3>{{ $section['title'] ?? '' }}</h3>

                                @foreach(explode("\n", $section['content'] ?? '') as $line)
                                    @if(trim($line) !== '')
                                        <p>{{ $line }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    @endif

                    @if(!empty($detail['note']))
                        <div class="love-note">
                            <p>
                                <strong>Lưu ý:</strong>
                                <span>{{ $detail['note'] }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            function initFortuneDatePicker(inputId, groupId, dayId, monthId, yearId, hiddenId, defaultDay, defaultMonth, defaultYear) {
                const inputDisplay = document.getElementById(inputId);
                const selectGroup = document.getElementById(groupId);
                const selectDay = document.getElementById(dayId);
                const selectMonth = document.getElementById(monthId);
                const selectYear = document.getElementById(yearId);
                const hiddenInput = document.getElementById(hiddenId);

                for (let m = 1; m <= 12; m++) {
                    selectMonth.options.add(new Option(String(m).padStart(2, "0"), m));
                }

                for (let y = 2050; y >= 1900; y--) {
                    selectYear.options.add(new Option(y, y));
                }

                if (defaultMonth) selectMonth.value = parseInt(defaultMonth);
                if (defaultYear) selectYear.value = parseInt(defaultYear);

                function updateInputDisplay() {
                    const d = selectDay.value ? String(selectDay.value).padStart(2, "0") : "";
                    const m = selectMonth.value ? String(selectMonth.value).padStart(2, "0") : "";
                    const y = selectYear.value;

                    if (d && m && y) {
                        inputDisplay.value = `${d}/${m}/${y}`;
                        hiddenInput.value = `${y}-${m}-${d}`;
                    } else {
                        inputDisplay.value = "";
                        hiddenInput.value = "";
                    }
                }

                function updateDays(isInitial = false) {
                    const year = parseInt(selectYear.value) || 2000;
                    const month = parseInt(selectMonth.value);
                    const currentSelectedDay = isInitial ? parseInt(defaultDay) : parseInt(selectDay.value);

                    selectDay.innerHTML = '<option value="">Ngày</option>';

                    if (!month) {
                        updateInputDisplay();
                        return;
                    }

                    const maxDays = new Date(year, month, 0).getDate();

                    for (let d = 1; d <= maxDays; d++) {
                        selectDay.options.add(new Option(String(d).padStart(2, "0"), d));
                    }

                    if (currentSelectedDay && currentSelectedDay <= maxDays) {
                        selectDay.value = currentSelectedDay;
                    } else {
                        selectDay.value = "";
                    }

                    updateInputDisplay();
                }

                selectMonth.addEventListener("change", function () {
                    updateDays(false);
                });

                selectYear.addEventListener("change", function () {
                    updateDays(false);
                });

                selectDay.addEventListener("change", updateInputDisplay);

                updateDays(true);

                inputDisplay.addEventListener("click", function (e) {
                    e.stopPropagation();

                    document.querySelectorAll(".fortune-select-group").forEach(function (group) {
                        group.classList.add("fortune-hidden");
                    });

                    selectGroup.classList.remove("fortune-hidden");
                });

                selectGroup.addEventListener("click", function (e) {
                    e.stopPropagation();
                });
            }

            initFortuneDatePicker(
                "fortune-display-1",
                "fortune-group-1",
                "fortune-day-1",
                "fortune-month-1",
                "fortune-year-1",
                "maleSolarDate",
                "{{ $maleDay }}",
                "{{ $maleMonth }}",
                "{{ $maleYear }}"
            );

            initFortuneDatePicker(
                "fortune-display-2",
                "fortune-group-2",
                "fortune-day-2",
                "fortune-month-2",
                "fortune-year-2",
                "femaleSolarDate",
                "{{ $femaleDay }}",
                "{{ $femaleMonth }}",
                "{{ $femaleYear }}"
            );

            document.addEventListener("click", function () {
                document.querySelectorAll(".fortune-select-group").forEach(function (group) {
                    group.classList.add("fortune-hidden");
                });
            });
        });
    </script>
@endsection
