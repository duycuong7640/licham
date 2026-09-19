@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.fortune-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Bói vui - Xem tuổi xây nhà</p>
            <h1>Xem Tuổi Xây Nhà {{ date('Y') }} - Tra Cứu Tuổi Làm Nhà Theo Tam Tai, Kim Lâu, Hoang Ốc</h1>
            <p>
                Công cụ xem tuổi xây nhà giúp tra cứu năm làm nhà có hợp với tuổi gia chủ hay không dựa trên tuổi mụ, Tam Tai, Kim Lâu và Hoang Ốc. Kết quả luận giải rõ từng hạn, gợi ý năm tốt, tuổi mượn phù hợp và những lưu ý quan trọng trước khi động thổ, sửa nhà hoặc xây nhà mới.
            </p>
        </div>

        @php
            $solarDate = request('solarDate', '1998-08-18');
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
                <label class="fortune-label">Năm cần xem</label>
                <div class="fortune-input-wrapper">
                    <select aria-label="Năm" name="year" id="yearSelect" class="fortune-select-year-view">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option value="{{ $i }}" {{ request('year', 2020) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <button type="submit" class="fortune-btn-submit-inline btn-sm-form">Xem kết quả</button>
        </form>
        <div class="wrap-content-xtxn">
            @if(!empty($data['detail']))
                @php
                    $detail = $data['detail'];

                    $owner = $detail['owner'] ?? [];
                    $buildingYear = $detail['buildingYear'] ?? [];
                    $checks = $detail['checks'] ?? [];
                    $tamTai = $checks['tamTai'] ?? [];
                    $kimLau = $checks['kimLau'] ?? [];
                    $hoangOc = $checks['hoangOc'] ?? [];
                    $result = $detail['result'] ?? [];
                    $reading = $detail['reading'] ?? [];
                    $focus = $reading['focus'] ?? [];
                    $formulaEvidence = $detail['formulaEvidence'] ?? [];
                @endphp

                <div class="xtxn-result-box">
                    <h2>{{ $reading['headline'] ?? 'Kết quả xem tuổi xây nhà' }}</h2>

                    @if(!empty($reading['summary']))
                        <p class="xtxn-summary">{{ $reading['summary'] }}</p>
                    @endif

                    <div class="xtxn-score-box">
                        <p>
                            <strong>{{ $result['score'] ?? 0 }}/{{ $result['maxScore'] ?? 10 }}</strong>
                            <span>{{ $result['level'] ?? '' }}</span>
                            <span>{{ $result['decisionLabel'] ?? '' }}</span>
                            <span>{{ !empty($result['isGoodYear']) ? 'Năm thuận' : 'Nên cân nhắc' }}</span>
                        </p>
                    </div>

                    <div class="xtxn-info-grid xtxn-info-grid-2">
                        <div>
                            <h3>Thông tin gia chủ</h3>
                            <p><strong>Ngày dương:</strong> <span>{{ $owner['solarDate'] ?? '' }}</span></p>
                            <p><strong>Ngày âm:</strong> <span>{{ $owner['lunarDate'] ?? '' }}</span></p>
                            <p><strong>Năm âm lịch:</strong> <span>{{ $owner['lunarYear'] ?? '' }}</span></p>
                            <p><strong>Tuổi:</strong> <span>{{ $owner['canChi'] ?? '' }}</span></p>
                        </div>

                        <div>
                            <h3>Năm xây nhà</h3>
                            <p><strong>Năm:</strong> <span>{{ $buildingYear['year'] ?? '' }}</span></p>
                            <p><strong>Can chi:</strong> <span>{{ $buildingYear['canChi'] ?? '' }}</span></p>
                            <p><strong>Tuổi mụ:</strong> <span>{{ $detail['lunarAge'] ?? '' }}</span></p>
                            <p><strong>Công thức:</strong> <span>{{ $detail['formula'] ?? '' }}</span></p>
                        </div>
                    </div>

                    <div class="xtxn-card xtxn-verdict-card">
                        <h3>Kết luận nhanh</h3>

                        @if(!empty($focus['oneLineAdvice']))
                            <p>{{ $focus['oneLineAdvice'] }}</p>
                        @endif

                        <p><strong>Trạng thái:</strong> <span>{{ $focus['status'] ?? ($result['decisionLabel'] ?? '') }}</span></p>

                        @if(!empty($result['violations']))
                            <p>
                                <strong>Hạn phạm:</strong>
                                <span>{{ implode(', ', $result['violations']) }}</span>
                            </p>
                        @else
                            <p><strong>Hạn phạm:</strong> <span>Không phạm Tam Tai, Kim Lâu, Hoang Ốc</span></p>
                        @endif

                        <p><strong>Số hạn phạm:</strong> <span>{{ $result['violationCount'] ?? 0 }}</span></p>

                        @if(isset($result['shouldBorrowAge']))
                            <p>
                                <strong>Có nên mượn tuổi:</strong>
                                <span>{{ !empty($result['shouldBorrowAge']) ? 'Nên cân nhắc mượn tuổi nếu vẫn xây' : 'Không cần mượn tuổi nếu các yếu tố khác thuận' }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="xtxn-card">
                        <h3>Đối chiếu 3 hạn chính</h3>

                        <div class="xtxn-check-list">
                            @foreach([
                                'Tam Tai' => $tamTai,
                                'Kim Lâu' => $kimLau,
                                'Hoang Ốc' => $hoangOc,
                            ] as $label => $check)
                                @if(!empty($check))
                                    <div class="xtxn-check-item {{ !empty($check['isViolated']) ? 'xtxn-check-bad' : 'xtxn-check-good' }}">
                                        <div class="xtxn-check-head">
                                            <strong>{{ $label }}</strong>
                                            <span>{{ !empty($check['isViolated']) ? 'Phạm' : 'Không phạm' }}</span>
                                        </div>

                                        <p><strong>Kết quả:</strong> <span>{{ $check['name'] ?? $label }}</span></p>

                                        @if(isset($check['score']))
                                            <p><strong>Điểm:</strong> <span>{{ $check['score'] }}</span></p>
                                        @endif

                                        @if(!empty($check['formula']))
                                            <p><strong>Cách tính:</strong> <span>{{ $check['formula'] }}</span></p>
                                        @endif

                                        @if(!empty($check['conclusion']))
                                            <p>{{ $check['conclusion'] }}</p>
                                        @endif

                                        @if(!empty($check['affected']))
                                            <p><strong>Ảnh hưởng:</strong> <span>{{ $check['affected'] }}</span></p>
                                        @endif

                                        @if(!empty($check['meaning']))
                                            <p><strong>Ý nghĩa:</strong> <span>{{ $check['meaning'] }}</span></p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @if(!empty($detail['goodYears']))
                        <div class="xtxn-card">
                            <h3>Năm tốt gần nhất có thể tham khảo</h3>

                            <div class="xtxn-year-list">
                                @foreach($detail['goodYears'] as $year)
                                    <p>
                                        <strong>{{ $year['year'] ?? '' }}</strong>
                                        <span>{{ $year['canChi'] ?? '' }}</span>
                                        <span>Tuổi mụ {{ $year['lunarAge'] ?? '' }}</span>
                                        <span>{{ $year['hoangOc'] ?? '' }}</span>
                                        <span>{{ $year['score'] ?? 0 }}/10 điểm</span>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($detail['borrowedAges']))
                        <div class="xtxn-card">
                            <h3>Gợi ý tuổi có thể mượn</h3>

                            <div class="xtxn-year-list">
                                @foreach($detail['borrowedAges'] as $age)
                                    <p>
                                        <strong>{{ $age['birthYear'] ?? '' }}</strong>
                                        <span>{{ $age['canChi'] ?? '' }}</span>
                                        <span>Tuổi mụ {{ $age['lunarAge'] ?? '' }}</span>
                                        <span>{{ $age['score'] ?? 0 }}/10 điểm</span>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($formulaEvidence['steps']))
                        <div class="xtxn-card">
                            <h3>Luận chứng công thức</h3>

                            @if(!empty($formulaEvidence['disclaimer']))
                                <p class="xtxn-warning-text">{{ $formulaEvidence['disclaimer'] }}</p>
                            @endif

                            <div class="xtxn-step-list">
                                @foreach($formulaEvidence['steps'] as $index => $step)
                                    <div class="xtxn-step-item">
                                        <div class="xtxn-step-number">{{ $index + 1 }}</div>

                                        <div>
                                            <h3>{{ $step['title'] ?? '' }}</h3>

                                            @if(!empty($step['input']))
                                                <p><strong>Dữ liệu:</strong> <span>{{ $step['input'] }}</span></p>
                                            @endif

                                            @if(!empty($step['formula']))
                                                <p><strong>Cách tính:</strong> <span>{{ $step['formula'] }}</span></p>
                                            @endif

                                            @if(!empty($step['output']))
                                                <p><strong>Kết quả:</strong> <span>{{ $step['output'] }}</span></p>
                                            @endif

                                            @if(!empty($step['conclusion']))
                                                <p>{{ $step['conclusion'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($reading['longReading']))
                        <h3 class="xtxn-section-title">Luận giải chi tiết</h3>

                        @foreach($reading['longReading'] as $section)
                            <div class="xtxn-card xtxn-report-section">
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
                        <div class="xtxn-card xtxn-poem">
                            <h3>Thơ luận</h3>

                            @foreach($reading['poem'] as $line)
                                <p><span>{{ $line }}</span></p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($detail['note']))
                        <div class="xtxn-note">
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
