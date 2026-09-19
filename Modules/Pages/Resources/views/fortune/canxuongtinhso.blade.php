@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.fortune-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Bói vui - Cân xương tính sô</p>
            <h1>Cân Xương Tính Số - Xem Số Mệnh Theo Giờ Ngày Tháng Năm Sinh</h1>
            <p>
                Cân xương tính số giúp bạn tham khảo số mệnh theo giờ sinh, ngày sinh, tháng sinh và năm sinh âm lịch dựa trên quan niệm dân gian.
                Công cụ luận giải tổng quan về vận mệnh, tính cách, tài lộc, công danh và những điểm cần lưu ý trong cuộc sống.
            </p>
        </div>

        @php
            $solarDate = request('solarDate', '1998-08-18');
            $hours = [
                "Tý"   => "23h-1h",
                "Sửu"  => "1h-3h",
                "Dần"  => "3h-5h",
                "Mão"  => "5h-7h",
                "Thìn" => "7h-9h",
                "Tị"   => "9h-11h",
                "Ngọ"  => "11h-13h",
                "Mùi"  => "13h-15h",
                "Thân" => "15h-17h",
                "Dậu"  => "17h-19h",
                "Tuất" => "19h-21h",
                "Hợi"  => "21h-23h",
            ];
        @endphp
        <form class="fortune-date-lookup-row" action="" method="get" aria-label="Chọn ngày cần xem">
            <input type="hidden" name="solarDate" id="solarDate" value="{{ $solarDate }}">

            <div class="fortune-picker-container">
                <label class="fortune-label">Nhập ngày sinh để xem (Dương lịch)</label>
                <div class="fortune-input-wrapper">
                    <input type="text" id="fortune-display" class="fortune-input" readonly placeholder="Ngày sinh..." autocomplete="off">
                    <div id="fortune-group" class="fortune-select-group fortune-hidden">
                        <select id="fortune-day"><option value="">Ngày</option></select>
                        <select id="fortune-month"><option value="">Tháng</option></select>
                        <select id="fortune-year"><option value="">Năm</option></select>
                    </div>
                </div>
            </div>

            <div class="fortune-picker-container">
                <label class="fortune-label">Giờ sinh</label>
                <div class="fortune-input-wrapper">
                    <select aria-label="Năm" name="birthHour" id="yearSelect" class="fortune-select-year-view">
                        @foreach($hours as $key => $value)
                            <option value="{{ \App\Helpers\Helpers::renderSlug($key) }}" {{ request('birthHour', 'thin') == \App\Helpers\Helpers::renderSlug($key) ? 'selected' : '' }}>
                                {{ $key }}({{ $value }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="fortune-btn-submit-inline btn-sm-form">Xem kết quả</button>
        </form>
        <div class="wrap-content-cxts">
            @if(!empty($data['detail']))
                @php
                    $detail = $data['detail'];

                    $totalWeight = $detail['totalWeight'] ?? [];
                    $details = $detail['details'] ?? [];
                    $reading = $detail['reading'] ?? [];
                    $longReading = $detail['longReading'] ?? [];
                @endphp

                <div class="cxts-result-box">
                    <h2>{{ $reading['title'] ?? 'Kết quả cân xương tính số' }}</h2>

                    @if(!empty($reading['summary']))
                        <p class="cxts-summary">{{ $reading['summary'] }}</p>
                    @elseif(!empty($detail['summary']))
                        <p class="cxts-summary">{{ $detail['summary'] }}</p>
                    @endif

                    <div class="cxts-score-box">
                        <p>
                            <strong>{{ $totalWeight['label'] ?? '' }}</strong>
                            <span>{{ $reading['level'] ?? '' }}</span>
                            <span>{{ $detail['totalChi'] ?? 0 }} chỉ</span>
                        </p>
                    </div>

                    <div class="cxts-info-grid cxts-info-grid-2">
                        <div>
                            <h3>Thông tin ngày sinh</h3>
                            <p><strong>Ngày dương:</strong> <span>{{ $detail['solarDate'] ?? '' }}</span></p>
                            <p><strong>Ngày âm:</strong> <span>{{ $detail['lunarDate'] ?? '' }}</span></p>
                            <p><strong>Năm âm lịch:</strong> <span>{{ $detail['lunarYearCanChi'] ?? '' }}</span></p>
                            <p><strong>Giờ sinh:</strong> <span>{{ $detail['birthHour'] ?? '' }}</span></p>
                        </div>

                        <div>
                            <h3>Tổng cân lượng</h3>
                            <p><strong>Lượng:</strong> <span>{{ $totalWeight['luong'] ?? 0 }}</span></p>
                            <p><strong>Chỉ:</strong> <span>{{ $totalWeight['chi'] ?? 0 }}</span></p>
                            <p><strong>Kết quả:</strong> <span>{{ $totalWeight['label'] ?? '' }}</span></p>

                            @if(!empty($detail['sourceHint']))
                                <p><strong>Nguồn luận:</strong> <span>{{ $detail['sourceHint'] }}</span></p>
                            @endif
                        </div>
                    </div>

                    @if(!empty($details))
                        <div class="cxts-card">
                            <h3>Bảng lập cân lượng</h3>

                            <div class="cxts-weight-list">
                                @foreach([
                                    'year' => 'Năm sinh',
                                    'month' => 'Tháng sinh',
                                    'day' => 'Ngày sinh',
                                    'hour' => 'Giờ sinh',
                                ] as $key => $label)
                                    @if(!empty($details[$key]))
                                        <div class="cxts-weight-item">
                                            <div>
                                                <strong>{{ $label }}</strong>
                                                <span>{{ $details[$key]['label'] ?? '' }}</span>
                                            </div>

                                            <p>
                                                <span>{{ $details[$key]['weight'] ?? '' }}</span>
                                                <strong>{{ $details[$key]['chi'] ?? 0 }} chỉ</strong>
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            @if(!empty($detail['formula']))
                                <p class="cxts-formula">
                                    <strong>Công thức:</strong>
                                    <span>{{ $detail['formula'] }}</span>
                                </p>
                            @endif
                        </div>
                    @endif

                    <div class="cxts-card cxts-verdict-card">
                        <h3>Tổng luận nhanh</h3>

                        @if(!empty($reading['classicLine']))
                            <p>{{ $reading['classicLine'] }}</p>
                        @elseif(!empty($reading['summary']))
                            <p>{{ $reading['summary'] }}</p>
                        @endif
                    </div>

                    <div class="cxts-card">
                        <h3>Luận giải từng phương diện</h3>

                        <div class="cxts-aspect-list">
                            @foreach([
                                'character' => 'Tính nết và căn khí',
                                'career' => 'Công danh sự nghiệp',
                                'wealth' => 'Tài lộc và của để dành',
                                'family' => 'Tình duyên gia đạo',
                                'health' => 'Thân tâm và phúc khí',
                                'timing' => 'Tiền vận, trung vận, hậu vận',
                                'advice' => 'Lời khuyên chiêm nghiệm',
                            ] as $key => $title)
                                @if(!empty($reading[$key]))
                                    <div class="cxts-aspect-item">
                                        <h3>{{ $title }}</h3>
                                        <p>{{ $reading[$key] }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @if(!empty($longReading))
                        <h3 class="cxts-section-title">Luận giải chi tiết</h3>

                        @foreach($longReading as $section)
                            <div class="cxts-card cxts-report-section">
                                <h3>{{ $section['title'] ?? '' }}</h3>

                                @foreach(explode("\n", $section['content'] ?? '') as $line)
                                    @if(trim($line) !== '')
                                        <p>{{ $line }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    @endif

                    @if(!empty($reading['poem']))
                        <div class="cxts-card cxts-poem">
                            <h3>Thơ luận</h3>

                            @foreach($reading['poem'] as $line)
                                <p><span>{{ $line }}</span></p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($detail['note']))
                        <div class="cxts-note">
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
            const inputDisplay = document.getElementById("fortune-display");
            const selectGroup = document.getElementById("fortune-group");
            const selectDay = document.getElementById("fortune-day");
            const selectMonth = document.getElementById("fortune-month");
            const selectYear = document.getElementById("fortune-year");
            const femaleHidden = document.getElementById("solarDate");
            const yearSelect = document.getElementById("yearSelect");

            for (let m = 1; m <= 12; m++) selectMonth.options.add(new Option(m < 10 ? "0" + m : m, m));
            for (let y = 2050; y >= 1900; y--) selectYear.options.add(new Option(y, y));

            const dateStr = femaleHidden.value && femaleHidden.value.includes('/') ? femaleHidden.value : '18/08/1998';
            const defaultParts = dateStr.split('/');

            selectDay.dataset.default = parseInt(defaultParts[0]);
            selectMonth.value = parseInt(defaultParts[1]);
            selectYear.value = parseInt(defaultParts[2]);

            if (!yearSelect) {
                yearSelect.value = "2020";
            }

            function updateDays(isInitial = false) {
                const year = parseInt(selectYear.value) || 2000;
                const month = parseInt(selectMonth.value);
                const currentSelectedDay = isInitial ? parseInt(selectDay.dataset.default) : parseInt(selectDay.value);

                selectDay.innerHTML = '<option value="">Ngày</option>';
                if (!month) return;

                const maxDays = new Date(year, month, 0).getDate();
                for (let d = 1; d <= maxDays; d++) selectDay.options.add(new Option(d < 10 ? "0" + d : d, d));

                if (currentSelectedDay && currentSelectedDay <= maxDays) {
                    selectDay.value = currentSelectedDay;
                } else if (currentSelectedDay && currentSelectedDay > maxDays) {
                    if (!isInitial) alert(`Tháng ${month} không có ngày ${currentSelectedDay}! Vui lòng chọn lại.`);
                    selectDay.value = "";
                }
                updateInputDisplay();
            }

            function updateInputDisplay() {
                const d = selectDay.value ? (selectDay.value < 10 ? "0" + selectDay.value : selectDay.value) : "";
                const m = selectMonth.value ? (selectMonth.value < 10 ? "0" + selectMonth.value : selectMonth.value) : "";
                const y = selectYear.value;

                if (d && m && y) {
                    const formatted = `${d}/${m}/${y}`;
                    inputDisplay.value = formatted;
                    if (femaleHidden) femaleHidden.value = formatted;
                } else {
                    inputDisplay.value = "";
                }
            }

            selectMonth.addEventListener("change", () => updateDays(false));
            selectYear.addEventListener("change", () => updateDays(false));
            selectDay.addEventListener("change", updateInputDisplay);

            updateDays(true);

            inputDisplay.addEventListener("click", (e) => {
                e.stopPropagation();
                selectGroup.classList.remove("fortune-hidden");
            });

            selectGroup.addEventListener("click", (e) => e.stopPropagation());

            document.addEventListener("click", () => selectGroup.classList.add("fortune-hidden"));
        });
    </script>
@endsection
