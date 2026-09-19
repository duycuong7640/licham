@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.fortune-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Bói vui - Tính trùng tang</p>
            <h1>Xem trùng tang theo ngày mất – Tính Trùng tang, Nhập mộ, Thiên di</h1>
            <p>
                Công cụ tính trùng tang theo ngày mất, giờ mất và tuổi âm người mất. Kết quả giúp tham khảo các cung
                Trùng tang, Nhập mộ, Thiên di theo quan niệm dân gian, kèm luận giải rõ ràng và lời khuyên phù hợp khi
                xem ngày giờ tang sự.
            </p>
        </div>

        <form class="wform-child-age-form" action="" method="get" aria-label="Tính trùng tang">
            <div class="wform-child-age-grid">
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Năm sinh (Âm lịch)</label>
                    <select name="birthYear" class="wform-select">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option value="{{ $i }}" {{ request('birthYear', 1945) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Giới tính</label>
                    <select name="gender" class="wform-select">
                        <option value="male" {{ request('gender', 'male') == 'male' ? 'selected' : '' }}>
                            Nam
                        </option>
                        <option value="female" {{ request('gender', 'male') == 'female' ? 'selected' : '' }}>
                            Nữ
                        </option>
                    </select>
                </div>
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Ngày mất (Dương lịch)</label>
                    <div class="wform-input-wrapper">
                        <input
                            type="text"
                            id="wform-father-display"
                            class="wform-input"
                            readonly
                            placeholder="dd/mm/yyyy"
                            autocomplete="off"
                            value="{{ request('deathSolarDate', '01/06/2026') }}"
                        >
                        <input
                            type="hidden"
                            name="deathSolarDate"
                            id="wform-father-value"
                            value="{{ request('deathSolarDate', '01/06/2026') }}"
                        >
                        <div id="wform-father-group" class="wform-select-group wform-hidden">
                            <select id="wform-father-day">
                                <option value="">Ngày</option>
                            </select>

                            <select id="wform-father-month">
                                <option value="">Tháng</option>
                            </select>

                            <select id="wform-father-year">
                                <option value="">Năm</option>
                            </select>
                        </div>
                    </div>
                </div>
                @php
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
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Giờ mất</label>
                    <div class="wform-input-wrapper">
                        <select aria-label="Năm" name="deathHour" class="fortune-select-year-view">
                            @foreach($hours as $key => $value)
                                <option
                                    value="{{ \App\Helpers\Helpers::renderSlug($key) }}" {{ request('deathHour', 'suu') == \App\Helpers\Helpers::renderSlug($key) ? 'selected' : '' }}>
                                    {{ $key }}({{ $value }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="wform-submit-wrap">
                    <button type="submit" class="wform-btn-submit btn-sm-form">Kết quả</button>
                </div>
            </div>
        </form>
        <div class="wrap-content-tct">
            @if(!empty($data['detail']))
                @php
                    $detail = $data['detail'];

                    $input = $detail['input'] ?? [];
                    $normalized = $detail['normalized'] ?? [];
                    $canChi = $detail['canChi'] ?? [];
                    $result = $detail['result'] ?? [];
                    $positions = $detail['positions'] ?? [];
                    $counts = $detail['counts'] ?? [];
                    $mitigation = $detail['mitigation'] ?? [];
                    $checks = $detail['checks'] ?? [];
                    $reading = $detail['reading'] ?? [];
                @endphp

                <div class="tct-result">
                    <h2>{{ $reading['headline'] ?? ($result['headline'] ?? 'Kết quả tính Trùng tang') }}</h2>

                    @if(!empty($reading['summary']) || !empty($result['summary']))
                        <p class="tct-summary">
                            {{ $reading['summary'] ?? $result['summary'] }}
                        </p>
                    @endif

                    <div class="tct-status-row">
                        @if(isset($result['score']))
                            <span class="tct-score">Điểm: {{ $result['score'] }}/10</span>
                        @endif
                        <span class="tct-status tct-status-{{ $result['level'] ?? 'safe' }}">
                            {{ $result['finalStatus'] ?? '' }}
                        </span>
                    </div>

                    <div class="tct-info-grid">
                        <div>
                            <h3>Thông tin người mất</h3>
                            <p><strong>Ngày mất dương lịch:</strong>
                                <span>{{ $normalized['deathSolarDate'] ?? '' }}</span></p>
                            <p><strong>Ngày mất âm lịch:</strong> <span>{{ $normalized['deathLunarDate'] ?? '' }}</span>
                            </p>
                            <p><strong>Năm sinh âm lịch:</strong> <span>{{ $normalized['birthLunarYear'] ?? '' }}</span>
                            </p>
                            <p><strong>Tuổi âm:</strong> <span>{{ $normalized['lunarAge'] ?? '' }}</span></p>
                            <p><strong>Giới tính:</strong> <span>{{ $normalized['gender'] ?? '' }}</span></p>
                            <p>
                                <strong>Giờ mất:</strong>
                                <span>
                            {{ $normalized['deathHour']['label'] ?? '' }}
                                    @if(!empty($normalized['deathHour']['range']))
                                        ({{ $normalized['deathHour']['range'] }})
                                    @endif
                        </span>
                            </p>
                        </div>

                        <div>
                            <h3>Can chi đối chiếu</h3>
                            <p><strong>Năm sinh:</strong> <span>{{ $canChi['birthYear'] ?? '' }}</span></p>
                            <p><strong>Năm mất:</strong> <span>{{ $canChi['deathYear'] ?? '' }}</span></p>
                            <p><strong>Tháng mất:</strong> <span>{{ $canChi['deathMonth'] ?? '' }}</span></p>
                            <p><strong>Ngày mất:</strong> <span>{{ $canChi['deathDay'] ?? '' }}</span></p>
                            <p><strong>Giờ mất:</strong> <span>{{ $canChi['deathHour'] ?? '' }}</span></p>
                        </div>
                    </div>

                    <div class="tct-section">
                        <h3>Kết quả bấm cung chính</h3>

                        <div class="tct-position-table">
                            <div class="tct-table-head">
                                <span>Mốc xét</span>
                                <span>Cung</span>
                                <span>Trạng thái</span>
                                <span>Mức</span>
                            </div>

                            @foreach($positions as $item)
                                <div class="tct-table-row {{ !empty($item['isViolated']) ? 'is-bad' : 'is-good' }}">
                                    <span>{{ $item['label'] ?? '' }}</span>
                                    <span>{{ $item['branchLabel'] ?? '' }}</span>
                                    <span>{{ $item['statusLabel'] ?? '' }}</span>
                                    <span>{{ $item['severityRank'] ?? '' }}</span>

                                    @if(!empty($item['note']))
                                        <p>{{ $item['note'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tct-section">
                        <h3>Tổng hợp hiệu lực</h3>

                        <div class="tct-count-grid">
                            <p><strong>Trùng tang:</strong> <span>{{ $counts['trungTang'] ?? 0 }}</span></p>
                            <p><strong>Thiên di:</strong> <span>{{ $counts['thienDi'] ?? 0 }}</span></p>
                            <p><strong>Nhập mộ:</strong> <span>{{ $counts['nhapMo'] ?? 0 }}</span></p>
                            <p><strong>Trùng tang còn hiệu lực:</strong>
                                <span>{{ $mitigation['effectiveTrungTang'] ?? 0 }}</span></p>
                        </div>

                        @if(!empty($mitigation['rule']))
                            <p><strong>Quy tắc làm nhẹ:</strong> <span>{{ $mitigation['rule'] }}</span></p>
                        @endif

                        @if(!empty($mitigation['explanation']))
                            <p>{{ $mitigation['explanation'] }}</p>
                        @endif
                    </div>

                    @if(!empty($checks))
                        <div class="tct-section">
                            <h3>Kiểm tra phụ</h3>

                            <div class="tct-check-list">
                                @foreach($checks as $check)
                                    <div class="tct-check-item {{ !empty($check['isViolated']) ? 'is-warning' : '' }}">
                                        <div class="tct-check-title">
                                            <strong>{{ $check['name'] ?? '' }}</strong>
                                            <span>{{ !empty($check['isViolated']) ? 'Có lưu ý' : 'Không phạm' }}</span>
                                        </div>

                                        @if(!empty($check['conclusion']))
                                            <p>{{ $check['conclusion'] }}</p>
                                        @endif

                                        @if(!empty($check['details']) && is_array($check['details']))
                                            @if(array_is_list($check['details']))
                                                @foreach($check['details'] as $line)
                                                    @if(is_array($line))
                                                        <p class="tct-sub-line">
                                                            {{ $line['label'] ?? '' }}
                                                            @if(!empty($line['branchLabel']))
                                                                - {{ $line['branchLabel'] }}
                                                            @endif
                                                        </p>
                                                    @else
                                                        <p class="tct-sub-line">{{ $line }}</p>
                                                    @endif
                                                @endforeach
                                            @else
                                                @if(!empty($check['details']['birthGroup']))
                                                    <p class="tct-sub-line">
                                                        Nhóm tuổi: {{ implode(', ', $check['details']['birthGroup']) }}
                                                    </p>
                                                @endif

                                                @if(!empty($check['details']['avoidBranch']))
                                                    <p class="tct-sub-line">
                                                        Chi cần tránh: {{ $check['details']['avoidBranch'] }}
                                                    </p>
                                                @endif

                                                @if(!empty($check['details']['avoidDays']))
                                                    <p class="tct-sub-line">
                                                        Ngày nên
                                                        tránh: {{ implode(', ', $check['details']['avoidDays']) }}
                                                    </p>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($reading['details']))
                        <div class="tct-section">
                            <h3>Luận giải chi tiết</h3>

                            <div class="tct-reading-list">
                                @foreach($reading['details'] as $line)
                                    <p>{{ $line }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($reading['advice']))
                        <div class="tct-section tct-advice">
                            <h3>Lời khuyên</h3>

                            @foreach($reading['advice'] as $line)
                                <p>{{ $line }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($reading['disclaimer']))
                        <div class="tct-note">
                            <p>
                                <strong>Lưu ý:</strong>
                                <span>{{ $reading['disclaimer'] }}</span>
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
            const inputDisplay = document.getElementById("wform-father-display");
            const selectGroup = document.getElementById("wform-father-group");
            const selectDay = document.getElementById("wform-father-day");
            const selectMonth = document.getElementById("wform-father-month");
            const selectYear = document.getElementById("wform-father-year");
            const hiddenInput = document.getElementById("wform-father-value");

            if (!inputDisplay || !selectGroup || !selectDay || !selectMonth || !selectYear || !hiddenInput) {
                return;
            }

            for (let m = 1; m <= 12; m++) {
                const label = m < 10 ? "0" + m : String(m);
                selectMonth.options.add(new Option(label, m));
            }

            for (let y = 2050; y >= 1900; y--) {
                selectYear.options.add(new Option(y, y));
            }

            const defaultDate = hiddenInput.value && hiddenInput.value.includes("/")
                ? hiddenInput.value
                : "01/06/2026";

            const parts = defaultDate.split("/");
            const defaultDay = parseInt(parts[0], 10);
            const defaultMonth = parseInt(parts[1], 10);
            const defaultYear = parseInt(parts[2], 10);

            selectDay.dataset.default = defaultDay || 1;
            selectMonth.value = defaultMonth || 6;
            selectYear.value = defaultYear || 2026;

            function pad2(value) {
                value = parseInt(value, 10);
                return value < 10 ? "0" + value : String(value);
            }

            function updateDays(isInitial = false) {
                const year = parseInt(selectYear.value, 10) || 2026;
                const month = parseInt(selectMonth.value, 10);

                const currentSelectedDay = isInitial
                    ? parseInt(selectDay.dataset.default, 10)
                    : parseInt(selectDay.value, 10);

                selectDay.innerHTML = '<option value="">Ngày</option>';

                if (!month || !year) {
                    updateInputDisplay();
                    return;
                }

                const maxDays = new Date(year, month, 0).getDate();

                for (let d = 1; d <= maxDays; d++) {
                    selectDay.options.add(new Option(pad2(d), d));
                }

                if (currentSelectedDay && currentSelectedDay <= maxDays) {
                    selectDay.value = currentSelectedDay;
                } else {
                    selectDay.value = "";

                    if (!isInitial && currentSelectedDay > maxDays) {
                        alert(`Tháng ${pad2(month)} năm ${year} không có ngày ${currentSelectedDay}. Vui lòng chọn lại.`);
                    }
                }

                updateInputDisplay();
            }

            function updateInputDisplay() {
                const day = selectDay.value;
                const month = selectMonth.value;
                const year = selectYear.value;

                if (day && month && year) {
                    const formatted = `${pad2(day)}/${pad2(month)}/${year}`;

                    inputDisplay.value = formatted;
                    hiddenInput.value = formatted;
                } else {
                    inputDisplay.value = "";
                    hiddenInput.value = "";
                }
            }

            selectMonth.addEventListener("change", function () {
                updateDays(false);
            });

            selectYear.addEventListener("change", function () {
                updateDays(false);
            });

            selectDay.addEventListener("change", function () {
                updateInputDisplay();
            });

            inputDisplay.addEventListener("click", function (e) {
                e.stopPropagation();
                selectGroup.classList.remove("wform-hidden");
            });

            selectGroup.addEventListener("click", function (e) {
                e.stopPropagation();
            });

            document.addEventListener("click", function () {
                selectGroup.classList.add("wform-hidden");
            });

            updateDays(true);
        });
    </script>
@endsection
