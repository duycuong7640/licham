@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.fortune-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Bói vui - Xem năm lấy vợ</p>
            <h1>Xem Năm Lấy Vợ Theo Tuổi Nam {{ date('Y') }} Chuẩn Phong Tục Việt</h1>
            <p>
                Tra cứu năm cưới phù hợp theo tuổi chú rể, xem tuổi mụ, các năm nên cưới và những lưu ý quan trọng trước khi kết hôn.
                Kết quả giúp bạn tham khảo thời điểm tổ chức hôn lễ thuận lợi, đồng thời đối chiếu thêm tuổi cô dâu để có lựa chọn phù hợp hơn.
            </p>
        </div>

        @php
            $maleSolarDate = request('maleSolarDate', '1998-08-18');
        @endphp
        <form class="fortune-date-lookup-row" action="" method="get" aria-label="Chọn ngày cần xem">
            <input type="hidden" name="maleSolarDate" id="maleSolarDate" value="{{ $maleSolarDate }}">

            <div class="fortune-picker-container">
                <label class="fortune-label">Nhập ngày sinh để xem</label>
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
                    <select aria-label="Năm" name="weddingYear" id="yearSelect" class="fortune-select-year-view">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option value="{{ $i }}" {{ request('weddingYear', 2020) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <button type="submit" class="fortune-btn-submit-inline btn-sm-form">Xem kết quả</button>
        </form>
        <div class="wrap-content-boy">
            @if(!empty($data['detail']))
                @php
                    $detail = $data['detail'];
                    $reading = $detail['reading'] ?? [];
                    $result = $detail['result'] ?? [];
                @endphp

                <div class="groom-result-box">
                    <h2>{{ $reading['headline'] ?? 'Kết quả xem năm lấy vợ' }}</h2>

                    @if(!empty($reading['summary']))
                        <p class="groom-summary">{{ $reading['summary'] }}</p>
                    @endif

                    <div class="groom-score-box">
                        <p>
                            <strong>{{ $result['score'] ?? 0 }}/{{ $result['maxScore'] ?? 10 }}</strong>
                            <span>{{ $result['level'] ?? '' }}</span>
                            <span>{{ !empty($result['isReferenceOnly']) ? 'Chỉ tham khảo tuổi nam' : '' }}</span>
                        </p>
                    </div>

                    <div class="groom-person-grid">
                        <div>
                            <h3>Thông tin nam mệnh</h3>
                            <p><strong>Ngày dương:</strong> <span>{{ $detail['male']['solarDate'] ?? '' }}</span></p>
                            <p><strong>Ngày âm:</strong> <span>{{ $detail['male']['lunarDate'] ?? '' }}</span></p>
                            <p><strong>Năm âm lịch:</strong> <span>{{ $detail['male']['lunarYear'] ?? '' }}</span></p>
                            <p><strong>Tuổi:</strong> <span>{{ $detail['male']['canChi'] ?? '' }}</span></p>
                        </div>

                        <div>
                            <h3>Năm dự định cưới</h3>
                            <p><strong>Năm:</strong> <span>{{ $detail['weddingYear']['year'] ?? '' }}</span></p>
                            <p><strong>Can chi:</strong> <span>{{ $detail['weddingYear']['canChi'] ?? '' }}</span></p>
                            <p><strong>Tuổi mụ nam:</strong> <span>{{ $detail['lunarAge'] ?? '' }}</span></p>
                            <p><strong>Công thức:</strong> <span>{{ $detail['formula'] ?? '' }}</span></p>
                        </div>
                    </div>

                    <div class="groom-card groom-warning">
                        <h3>Cần hiểu đúng</h3>
                        <p>
                            <strong>Tuổi nam chỉ là lớp tham khảo.</strong>
                            <span>Khi chọn năm cưới hỏi, dân gian thường ưu tiên tuổi cô dâu, ngày tháng cưới và sự đồng thuận hai họ.</span>
                        </p>
                    </div>

                    <div class="groom-card">
                        <h3>Kết luận nhanh</h3>

                        @if(!empty($reading['verdict']))
                            <p>{{ $reading['verdict'] }}</p>
                        @endif

                        @if(!empty($reading['focus']))
                            <p><strong>Trạng thái:</strong> <span>{{ $reading['focus']['status'] ?? '' }}</span></p>
                            <p><strong>Điểm cần lưu ý:</strong> <span>{{ $reading['focus']['mainConcern'] ?? '' }}</span></p>
                            <p><strong>Lời khuyên:</strong> <span>{{ $reading['focus']['oneLineAdvice'] ?? '' }}</span></p>
                        @endif
                    </div>

                    <div class="groom-card">
                        <h3>Kết quả tham khảo tuổi nam</h3>

                        <p><strong>Có dùng làm kết luận chính không:</strong> <span>Không, chỉ tham khảo</span></p>
                        <p><strong>Có điểm cần lưu ý không:</strong> <span>{{ !empty($result['hasAdvisoryConcern']) ? 'Có' : 'Không' }}</span></p>

                        @if(!empty($result['advisoryKimLauType']))
                            <p><strong>Tín hiệu tham khảo:</strong> <span>{{ $result['advisoryKimLauType'] }}</span></p>
                        @endif

                        @if(!empty($result['affected']))
                            <p><strong>Thường nhắc tới:</strong> <span>{{ $result['affected'] }}</span></p>
                        @endif

                        <p><strong>Hàng đơn vị tuổi mụ:</strong> <span>{{ $result['unitDigit'] ?? '' }}</span></p>
                        <p><strong>Đối chiếu chia 9:</strong> <span>dư {{ $result['divideByNineRemainder'] ?? '' }}</span></p>
                    </div>

                    @if(!empty($reading['actionPlan']))
                        <div class="groom-card">
                            <h3>Việc chú rể nên chuẩn bị</h3>

                            @foreach($reading['actionPlan'] as $item)
                                <p><span>{{ $item }}</span></p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($detail['nextGoodYears']))
                        <div class="groom-card">
                            <h3>Năm tham khảo thuận hơn cho tuổi nam</h3>

                            <div class="groom-year-list">
                                @foreach($detail['nextGoodYears'] as $year)
                                    <p>
                                        <strong>{{ $year['year'] }}</strong>
                                        <span>{{ $year['canChi'] }}</span>
                                        <span>Tuổi mụ {{ $year['lunarAge'] }}</span>
                                        <span>Hàng đơn vị {{ $year['unitDigit'] }}</span>
                                        <span>Chia 9 dư {{ $year['divideByNineRemainder'] }}</span>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($detail['checks']))
                        <div class="groom-card">
                            <h3>Đối chiếu cách tính</h3>

                            @foreach($detail['checks'] as $check)
                                <p>
                                    <strong>{{ $check['method'] ?? '' }}:</strong>

                                    @if(isset($check['unitDigit']))
                                        <span>hàng đơn vị {{ $check['unitDigit'] }}</span>
                                    @endif

                                    @if(isset($check['remainder']))
                                        <span>dư {{ $check['remainder'] }}</span>
                                    @endif

                                    @if(isset($check['value']))
                                        <span>giá trị {{ $check['value'] }}</span>
                                    @endif

                                    <span>{{ !empty($check['hasAdvisoryConcern']) ? 'Có điểm cần lưu ý' : 'Không có điểm cần lưu ý' }}</span>
                                </p>

                                @if(!empty($check['note']))
                                    <p><span>{{ $check['note'] }}</span></p>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($reading['longReading']))
                        <h3 class="groom-section-title">Luận giải chi tiết</h3>

                        @foreach($reading['longReading'] as $section)
                            <div class="groom-card groom-report-section">
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
                        <div class="groom-card groom-poem">
                            <h3>Thơ luận năm cưới</h3>

                            @foreach($reading['poem'] as $line)
                                <p><span>{{ $line }}</span></p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($detail['note']))
                        <div class="groom-note">
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
            const femaleHidden = document.getElementById("maleSolarDate");
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
