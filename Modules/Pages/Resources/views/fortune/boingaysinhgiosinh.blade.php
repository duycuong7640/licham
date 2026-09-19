@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.fortune-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Bói vui - Xem bói ngày sinh, giờ sinh</p>
            <h1>Xem Bói Ngày Sinh Giờ Sinh {{ date('Y') }} - Luận Vận Mệnh, Tính Cách, Công Danh, Tình Duyên</h1>
            <p>
                Tra cứu ý nghĩa ngày sinh và giờ sinh theo âm lịch để luận giải tổng quan vận mệnh, tính cách, công danh sự nghiệp, tài lộc, tình duyên gia đạo và lời khuyên chiêm nghiệm. Nội dung mang tính tham khảo theo quan niệm dân gian, giúp người xem hiểu thêm về bản thân và định hướng cuộc sống tích cực hơn.
            </p>
        </div>

        <form class="wform-child-age-form" action="" method="get" aria-label="Xem bói ngày sinh giờ sinh">
            <div class="wform-child-age-grid">
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Ngày sinh (Dương lịch)</label>

                    <div class="wform-input-wrapper">
                        <input
                            type="text"
                            id="wform-birth-display"
                            class="wform-input"
                            readonly
                            placeholder="dd/mm/yyyy"
                            autocomplete="off"
                            value="{{ request('birthDate', '01/06/1995') }}"
                        >

                        <input
                            type="hidden"
                            name="birthDate"
                            id="wform-birth-value"
                            value="{{ request('birthDate', '01/06/1995') }}"
                        >

                        <div id="wform-birth-group" class="wform-select-group wform-hidden">
                            <select id="wform-birth-day">
                                <option value="">Ngày</option>
                            </select>

                            <select id="wform-birth-month">
                                <option value="">Tháng</option>
                            </select>

                            <select id="wform-birth-year">
                                <option value="">Năm</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Giờ sinh</label>

                    <input
                        type="time"
                        name="birthTime"
                        id="wform-birth-time"
                        class="wform-input"
                        step="60"
                        value="{{ request('birthTime', '') }}"
                    >
                </div>

                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Giới tính</label>

                    <select name="gender" id="wform-gender" class="wform-select" required>
                        <option value="male" {{ request('gender', 'unknown') == 'male' ? 'selected' : '' }}>
                            Nam
                        </option>
                        <option value="female" {{ request('gender', 'unknown') == 'female' ? 'selected' : '' }}>
                            Nữ
                        </option>
                        <option value="other" {{ request('gender', 'unknown') == 'other' ? 'selected' : '' }}>
                            Khác
                        </option>
                    </select>
                </div>

                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Nơi sinh</label>
                    <input
                        type="text"
                        name="birthPlace"
                        id="wform-birth-place"
                        class="wform-input"
                        placeholder="Ví dụ: Hà Nội"
                        autocomplete="off"
                        maxlength="120"
                        value="{{ request('birthPlace', '') }}"
                    >
                </div>

                <div class="wform-submit-wrap">
                    <button type="submit" class="wform-btn-submit btn-sm-form">
                        Kết quả
                    </button>
                </div>
            </div>
        </form>
        <div class="wrap-content-bnsns">
            @if(!empty($data['detail']['report']))
                @php
                    $report = $data['detail']['report'];

                    $metadata = $report['metadata'] ?? [];
                    $lunarInfo = $metadata['lunarInfo'] ?? [];
                    $lunarRaw = $lunarInfo['raw'] ?? [];
                @endphp

                <div class="bnsns-result">
                    <div class="bnsns-head">
                        <h2>{{ $report['title'] ?? 'Kết quả xem bói ngày sinh' }}</h2>

                        @if(!empty($report['summary']))
                            <p class="bnsns-summary">{{ $report['summary'] }}</p>
                        @endif
                    </div>

                    <div class="bnsns-meta-row">
                        @if(!empty($report['birthDate']))
                            <span>Ngày sinh: {{ \Carbon\Carbon::parse($report['birthDate'])->format('d/m/Y') }}</span>
                        @endif

                        @if(!empty($report['birthTime']))
                            <span>Giờ sinh: {{ $report['birthTime'] }}</span>
                        @endif

                        @if(!empty($report['birthPlace']))
                            <span>Nơi sinh: {{ $report['birthPlace'] }}</span>
                        @endif

                        @if(!empty($report['gender']))
                            <span>Giới tính: {{ $report['gender'] == 'male' ? 'Nam' : ($report['gender'] == 'female' ? 'Nữ' : 'Khác') }}</span>
                        @endif
                    </div>

                    <div class="bnsns-overview">
                        <div>
                            <strong>Cung hoàng đạo</strong>
                            <span>{{ $report['westernZodiac'] ?? 'Đang cập nhật' }}</span>
                        </div>

                        <div>
                            <strong>Tuổi âm lịch</strong>
                            <span>{{ $report['lunarStemBranch'] ?? 'Đang cập nhật' }}</span>
                        </div>

                        <div>
                            <strong>Mệnh ngũ hành</strong>
                            <span>{{ $report['element'] ?? 'Đang cập nhật' }}</span>
                        </div>

                        <div>
                            <strong>Số từ luận giải</strong>
                            <span>{{ number_format($report['wordCount'] ?? 0) }} từ</span>
                        </div>
                    </div>

                    @if(!empty($lunarInfo))
                        <div class="bnsns-section">
                            <h3>Dữ liệu âm lịch tham khảo</h3>

                            <div class="bnsns-mini-grid">
                                @if(!empty($lunarInfo['naYin']))
                                    <p><strong>Nạp âm: </strong> <span>{{ $lunarInfo['naYin'] }}</span></p>
                                @endif

                                @if(!empty($lunarInfo['yearGanZhi']))
                                    <p><strong>Năm: </strong> <span>{{ $lunarInfo['yearGanZhi'] }}</span></p>
                                @endif

                                @if(!empty($lunarInfo['monthGanZhi']))
                                    <p><strong>Tháng: </strong> <span>{{ $lunarInfo['monthGanZhi'] }}</span></p>
                                @endif

                                @if(!empty($lunarInfo['dayGanZhi']))
                                    <p><strong>Ngày: </strong> <span>{{ $lunarInfo['dayGanZhi'] }}</span></p>
                                @endif

                                @if(!empty($lunarInfo['timeGanZhi']))
                                    <p><strong>Giờ: </strong> <span>{{ $lunarInfo['timeGanZhi'] }}</span></p>
                                @endif

                                @if(!empty($metadata['yinYang']))
                                    <p><strong>Âm dương: </strong> <span>{{ $metadata['yinYang'] }}</span></p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(!empty($report['content']))
                        <div class="bnsns-content">
                            {!! $report['content'] !!}
                        </div>
                    @endif

                    @if(!empty($lunarInfo['dayGood']) || !empty($lunarInfo['dayAvoid']))
                        <div class="bnsns-section">
                            <h3>Việc nên làm và nên tránh theo lịch ngày</h3>

                            @if(!empty($lunarInfo['dayGood']))
                                <p><strong>Nên làm:</strong> <span>{{ implode(', ', $lunarInfo['dayGood']) }}</span></p>
                            @endif

                            @if(!empty($lunarInfo['dayAvoid']))
                                <p><strong>Nên tránh:</strong> <span>{{ implode(', ', $lunarInfo['dayAvoid']) }}</span></p>
                            @endif
                        </div>
                    @endif

                    <div class="bnsns-note">
                        <p>
                            <strong>Lưu ý:</strong>
                            <span>{{ $metadata['note'] ?? 'Nội dung chỉ mang tính tham khảo theo quan niệm dân gian, không thay thế tư vấn chuyên môn.' }}</span>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector(".wform-child-age-form");

            const inputDisplay = document.getElementById("wform-birth-display");
            const selectGroup = document.getElementById("wform-birth-group");
            const selectDay = document.getElementById("wform-birth-day");
            const selectMonth = document.getElementById("wform-birth-month");
            const selectYear = document.getElementById("wform-birth-year");
            const hiddenInput = document.getElementById("wform-birth-value");

            const birthTimeInput = document.getElementById("wform-birth-time");
            const birthPlaceInput = document.getElementById("wform-birth-place");
            const genderSelect = document.getElementById("wform-gender");

            if (
                !form ||
                !inputDisplay ||
                !selectGroup ||
                !selectDay ||
                !selectMonth ||
                !selectYear ||
                !hiddenInput ||
                !birthTimeInput ||
                !birthPlaceInput ||
                !genderSelect
            ) {
                return;
            }

            function pad2(value) {
                value = parseInt(value, 10);
                return value < 10 ? "0" + value : String(value);
            }

            function isValidDateValue(day, month, year) {
                day = parseInt(day, 10);
                month = parseInt(month, 10);
                year = parseInt(year, 10);

                if (!day || !month || !year) return false;
                if (year < 1900 || year > 2050) return false;
                if (month < 1 || month > 12) return false;

                const maxDays = new Date(year, month, 0).getDate();

                return day >= 1 && day <= maxDays;
            }

            function isValidDateString(value) {
                if (!/^\d{2}\/\d{2}\/\d{4}$/.test(value)) {
                    return false;
                }

                const parts = value.split("/");
                return isValidDateValue(parts[0], parts[1], parts[2]);
            }

            function isValidTime(value) {
                if (!/^\d{2}:\d{2}$/.test(value)) {
                    return false;
                }

                const parts = value.split(":");
                const hour = parseInt(parts[0], 10);
                const minute = parseInt(parts[1], 10);

                return hour >= 0 && hour <= 23 && minute >= 0 && minute <= 59;
            }

            function normalizeTimeValue() {
                const value = birthTimeInput.value.trim();

                if (!value) return;

                const parts = value.split(":");

                if (parts.length < 2) return;

                const hour = parseInt(parts[0], 10);
                const minute = parseInt(parts[1], 10);

                if (Number.isNaN(hour) || Number.isNaN(minute)) return;

                birthTimeInput.value = `${pad2(hour)}:${pad2(minute)}`;
            }

            for (let m = 1; m <= 12; m++) {
                selectMonth.options.add(new Option(pad2(m), m));
            }

            for (let y = 2050; y >= 1900; y--) {
                selectYear.options.add(new Option(y, y));
            }

            const defaultDate = hiddenInput.value && hiddenInput.value.includes("/")
                ? hiddenInput.value
                : "01/06/1995";

            const parts = defaultDate.split("/");
            const defaultDay = parseInt(parts[0], 10) || 1;
            const defaultMonth = parseInt(parts[1], 10) || 6;
            const defaultYear = parseInt(parts[2], 10) || 1995;

            selectDay.dataset.default = defaultDay;
            selectMonth.value = defaultMonth;
            selectYear.value = defaultYear;

            function updateDays(isInitial = false) {
                const year = parseInt(selectYear.value, 10) || 1995;
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

                if (day && month && year && isValidDateValue(day, month, year)) {
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

            birthTimeInput.addEventListener("change", function () {
                normalizeTimeValue();
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

            form.addEventListener("submit", function (e) {
                updateInputDisplay();
                normalizeTimeValue();

                const birthDate = hiddenInput.value.trim();
                const birthTime = birthTimeInput.value.trim();
                const birthPlace = birthPlaceInput.value.trim();
                const gender = genderSelect.value;

                if (!isValidDateString(birthDate)) {
                    e.preventDefault();
                    alert("Ngày sinh không hợp lệ. Vui lòng chọn đúng ngày/tháng/năm từ 1900 đến 2050.");
                    inputDisplay.focus();
                    return;
                }

                if (!isValidTime(birthTime) && birthTime) {
                    e.preventDefault();
                    alert("Giờ sinh không hợp lệ. Vui lòng nhập theo định dạng HH:mm, ví dụ 07:30.");
                    birthTimeInput.focus();
                    return;
                }

                if (birthPlace && birthPlace.length > 120) {
                    e.preventDefault();
                    alert("Nơi sinh không được vượt quá 120 ký tự.");
                    birthPlaceInput.focus();
                    return;
                }

                if (!["unknown", "male", "female", "other"].includes(gender)) {
                    e.preventDefault();
                    alert("Giới tính không hợp lệ.");
                    genderSelect.focus();
                    return;
                }

                const submitBtn = form.querySelector(".wform-btn-submit");

                if (submitBtn) {
                    if (submitBtn.dataset.loading === "1") {
                        e.preventDefault();
                        return;
                    }

                    submitBtn.dataset.loading = "1";
                    submitBtn.dataset.originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.classList.add("is-loading");
                    submitBtn.innerHTML = "Đang xem...";
                }
            });

            updateDays(true);
        });
    </script>
@endsection
