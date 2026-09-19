@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.page-schema', [
            'schemaType' => 'WebPage',
            'sectionName' => 'Thần số học',
            'sectionUrl' => url('/than-so-hoc'),
            'currentName' => 'Tra cứu thần số học',
            'aboutName' => 'Tra cứu thần số học theo ngày sinh và họ tên',
        ])
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Thần số học - Tra cứu thần số học online</p>
            <h1>Thần số học online - Giải mã tên, ngày sinh và các chỉ số cuộc đời</h1>
            <p>Tra cứu thần số học theo họ tên và ngày tháng năm sinh để xem số chủ đạo, chỉ số linh hồn, sứ mệnh, nhân
                cách, thái độ và những gợi ý định hướng bản thân trong cuộc sống.</p>
        </div>

        <form class="wform-child-age-form mb-4" action="" method="get" aria-label="Xem bói ngày sinh">
            <div class="wform-child-age-grid">
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Họ và tên</label>
                    <input
                        type="text"
                        name="fullName"
                        id="wform-full-name"
                        class="wform-input"
                        placeholder="Nhập họ và tên"
                        autocomplete="name"
                        maxlength="80"
                        value="{{ request('fullName', '') }}"
                        required
                    >
                </div>

                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Ngày sinh dương lịch</label>

                    <div class="wform-input-wrapper">
                        <input
                            type="text"
                            id="wform-birth-display"
                            class="wform-input"
                            readonly
                            placeholder="dd/mm/yyyy"
                            autocomplete="off"
                            value="{{ request('solarDate', '01/06/1995') }}"
                        >

                        <input
                            type="hidden"
                            name="solarDate"
                            id="wform-birth-value"
                            value="{{ request('solarDate', '01/06/1995') }}"
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
                    <label class="wform-label">Giới tính</label>

                    <select name="gender" id="wform-gender" class="wform-select" required>
                        <option value="">Chọn giới tính</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>
                            Nam
                        </option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>
                            Nữ
                        </option>
                    </select>
                </div>

                <div class="wform-submit-wrap">
                    <button type="submit" class="wform-btn-submit btn-sm-form">
                        Kết quả
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if(!empty($data['detail']))
        @php
            $detail = $data['detail'] ?? [];

            $person = data_get($detail, 'person', []);
            $indexes = collect(data_get($detail, 'indexes', []));
            $birthChart = data_get($detail, 'birthChart', []);
            $cycleNumbers = data_get($detail, 'cycleNumbers', []);
            $nameNumbers = data_get($detail, 'nameNumbers', []);
            $reading = data_get($detail, 'reading', []);

            $findIndex = fn($key) => $indexes->firstWhere('key', $key);

            $formatDate = function ($date) {
                if (!$date) return 'Đang cập nhật';
                try {
                    return \Carbon\Carbon::parse($date)->format('d/m/Y');
                } catch (\Throwable $e) {
                    return $date;
                }
            };

            $fullName = data_get($person, 'fullName', 'Người dùng');

            $lifePath = $findIndex('lifePath');
            $expression = $findIndex('expression');
            $soulUrge = $findIndex('soulUrge');
            $personality = $findIndex('personality');
            $maturity = $findIndex('maturity');

            $personalYear = $findIndex('personalYear');
            $personalMonth = $findIndex('personalMonth');
            $personalDay = $findIndex('personalDay');

            $coreIndexes = collect([
                $lifePath,
                $expression,
                $soulUrge,
                $personality,
                $maturity,
            ])->filter()->values();

            $currentIndexes = collect([
                $personalYear,
                $personalMonth,
                $personalDay,
            ])->filter()->values();

            $birthGrid = data_get($birthChart, 'grid', []);
            $birthSummary = data_get($birthChart, 'reading.summary');
            $fullArrows = data_get($birthChart, 'fullArrows', []);
            $dominantDigits = data_get($birthChart, 'dominantDigits', []);
            $emptyDigits = data_get($birthChart, 'emptyDigits', []);
            $trainingPoints = data_get($birthChart, 'reading.trainingPoints', []);

            $hiddenPassion = data_get($nameNumbers, 'hiddenPassion', []);
            $karmicLessons = data_get($nameNumbers, 'karmicLessons', []);
            $karmicDebt = data_get($nameNumbers, 'karmicDebt', []);
            $bridges = data_get($nameNumbers, 'bridges', []);

            $summary = data_get($reading, 'summary');

            $reportSections = data_get($detail, 'reportSections', data_get($reading, 'reportSections', []));
            $mainReport = collect($reportSections)->take(10)->values();

            $note = data_get($detail, 'note', data_get($detail, 'formulaEvidence.disclaimer'));
        @endphp

        <div class="wrap-content-views">
            <section class="wcviews-hero">
                <div>
                    <p class="wcviews-eyebrow">Thần số học cá nhân</p>
                    <h2 class="wcviews-title">Luận giải tổng quan cho {{ $fullName }}</h2>
                    <p class="wcviews-desc">
                        Bản rút gọn chỉ giữ lại các phần quan trọng nhất: bộ số cốt lõi,
                        tổng luận, biểu đồ ngày sinh, năng lượng tên gọi và chu kỳ hiện tại.
                    </p>

                    <div class="wcviews-person">
                        <span>Họ tên: <strong>{{ $fullName }}</strong></span>
                        <span>Giới tính: <strong>{{ data_get($person, 'genderLabel', 'Đang cập nhật') }}</strong></span>
                        <span>Ngày sinh: <strong>{{ $formatDate(data_get($person, 'solarDate')) }}</strong></span>
                        <span>Ngày xem: <strong>{{ $formatDate(data_get($person, 'targetDate')) }}</strong></span>
                    </div>
                </div>

                <div class="wcviews-hero-number">
                    <span>{{ data_get($lifePath, 'title', 'Đường đời') }}</span>
                    <strong>{{ data_get($lifePath, 'value', '-') }}</strong>
                    <small>{{ data_get($lifePath, 'meaning', 'Đang cập nhật') }}</small>
                </div>
            </section>

            @if($coreIndexes->isNotEmpty())
                <section class="wcviews-section wcviews-core-section">
                    <div class="wcviews-section-head">
                        <p class="wcviews-eyebrow">Bộ số cốt lõi</p>
                        <h3>5 chỉ số quan trọng nhất</h3>
                    </div>

                    <div class="wcviews-core-grid">
                        @foreach($coreIndexes as $item)
                            @php
                                $reading = trim((string) data_get($item, 'reading', ''));
                                $shortReading = $reading;//\Illuminate\Support\Str::limit($reading, 135);
                            @endphp

                            <article class="wcviews-core-card {{ $loop->first ? 'is-main' : '' }}">
                                <div class="wcviews-core-top">
                                    <span>{{ data_get($item, 'title') }}</span>
                                    <strong>{{ data_get($item, 'value') }}</strong>
                                </div>

                                <div class="wcviews-core-body">
                                    <h4>{{ data_get($item, 'meaning') }}</h4>

                                    @if($shortReading)
                                        <p>{{ $shortReading }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($summary || $mainReport->isNotEmpty())
                <section class="wcviews-section">
                    <div class="wcviews-section-head">
                        <p class="wcviews-eyebrow">Tổng luận</p>
                        <h3>Điểm chính trong bài số</h3>
                    </div>

                    @if($summary)
                        <div class="wcviews-summary">
                            {!! nl2br(e($summary)) !!}
                        </div>
                    @endif

                    @if($mainReport->isNotEmpty())
                        <div class="wcviews-report-list">
                            @foreach($mainReport as $section)
                                @php
                                    $content = trim((string) data_get($section, 'content', ''));
                                    $paragraphs = collect(preg_split("/\r\n|\n|\r/", $content))
                                        ->map(fn($p) => trim($p))
                                        ->filter()
                                        ->take(3)
                                        ->values();
                                @endphp

                                <article class="wcviews-report-card">
                                    <h4>{{ data_get($section, 'title', 'Luận giải') }}</h4>

                                    @foreach($paragraphs as $paragraph)
                                        <p>{{ $paragraph }}</p>
                                    @endforeach
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif

            <section class="wcviews-section wcviews-two-col">
                <div>
                    <div class="wcviews-section-head">
                        <p class="wcviews-eyebrow">Biểu đồ ngày sinh</p>
                        <h3>Lưới số 3x3</h3>
                    </div>

                    @if(!empty($birthGrid))
                        <div class="wcviews-birth-grid">
                            @foreach($birthGrid as $row)
                                @foreach($row as $cell)
                                    @php
                                        $count = (int) data_get($cell, 'count', 0);
                                    @endphp

                                    <div
                                        class="wcviews-birth-cell {{ $count <= 0 ? 'is-empty' : '' }} {{ $count >= 2 ? 'is-strong' : '' }}">
                                        <strong>{{ data_get($cell, 'digit') }}</strong>
                                        <span>{{ data_get($cell, 'label') }}</span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                    @if($birthSummary)
                        <p class="wcviews-small-note">{{ $birthSummary }}</p>
                    @endif
                </div>

                <div>
                    <div class="wcviews-section-head">
                        <p class="wcviews-eyebrow">Điểm nổi bật</p>
                        <h3>Mũi tên, số mạnh và số cần rèn</h3>
                    </div>

                    @foreach($fullArrows as $arrow)
                        <article class="wcviews-mini-card">
                            <span>{{ implode('-', data_get($arrow, 'digits', [])) }}</span>
                            <h4>{{ data_get($arrow, 'title') }}</h4>
                            <p>{{ data_get($arrow, 'reading') }}</p>
                        </article>
                    @endforeach

                    <div class="wcviews-stat-list">
                        @if(!empty($dominantDigits))
                            <div>
                                <span>Số nổi bật</span>
                                <strong>
                                    @foreach($dominantDigits as $digit)
                                        {{ data_get($digit, 'digit') }}
                                        x{{ data_get($digit, 'count') }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </strong>
                            </div>
                        @endif

                        @if(!empty($emptyDigits))
                            <div>
                                <span>Số cần rèn</span>
                                <strong>
                                    @foreach($emptyDigits as $digit)
                                        {{ data_get($digit, 'digit') }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </strong>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            @if($currentIndexes->isNotEmpty())
                <section class="wcviews-section">
                    <div class="wcviews-section-head">
                        <p class="wcviews-eyebrow">Chu kỳ hiện tại</p>
                        <h3>Năm, tháng và ngày cá nhân</h3>
                    </div>

                    <div class="wcviews-current-grid">
                        @foreach($currentIndexes as $item)
                            <article class="wcviews-current-card">
                                <span>{{ data_get($item, 'title') }}</span>
                                <strong>{{ data_get($item, 'value') }}</strong>
                                <h4>{{ data_get($item, 'meaning') }}</h4>
                                <p>{{ data_get($item, 'reading') }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if(!empty($trainingPoints))
                <section class="wcviews-section wcviews-light-section">
                    <div class="wcviews-section-head">
                        <p class="wcviews-eyebrow">Gợi ý rèn luyện</p>
                        <h3>Những điểm nên chú ý</h3>
                    </div>

                    <div class="wcviews-training-list">
                        @foreach($trainingPoints as $item)
                            <div>{{ $item }}</div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($note)
                <section class="wcviews-note">
                    <strong>Lưu ý tham khảo</strong>
                    <p>{{ $note }}</p>
                </section>
            @endif
        </div>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector(".wform-child-age-form");

            const fullNameInput = document.getElementById("wform-full-name");

            const inputDisplay = document.getElementById("wform-birth-display");
            const selectGroup = document.getElementById("wform-birth-group");
            const selectDay = document.getElementById("wform-birth-day");
            const selectMonth = document.getElementById("wform-birth-month");
            const selectYear = document.getElementById("wform-birth-year");
            const hiddenInput = document.getElementById("wform-birth-value");

            const genderSelect = document.getElementById("wform-gender");

            if (
                !form ||
                !fullNameInput ||
                !inputDisplay ||
                !selectGroup ||
                !selectDay ||
                !selectMonth ||
                !selectYear ||
                !hiddenInput ||
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

            function normalizeName(value) {
                return value
                    .replace(/\s+/g, " ")
                    .trim();
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

                const fullName = normalizeName(fullNameInput.value);
                const birthDate = hiddenInput.value.trim();
                const gender = genderSelect.value;

                fullNameInput.value = fullName;

                if (!fullName) {
                    e.preventDefault();
                    alert("Vui lòng nhập họ và tên.");
                    fullNameInput.focus();
                    return;
                }

                if (fullName.length > 80) {
                    e.preventDefault();
                    alert("Họ và tên không được vượt quá 80 ký tự.");
                    fullNameInput.focus();
                    return;
                }

                if (!isValidDateString(birthDate)) {
                    e.preventDefault();
                    alert("Ngày sinh không hợp lệ. Vui lòng chọn đúng ngày/tháng/năm dương lịch từ 1900 đến 2050.");
                    inputDisplay.focus();
                    selectGroup.classList.remove("wform-hidden");
                    return;
                }

                if (!["male", "female"].includes(gender)) {
                    e.preventDefault();
                    alert("Vui lòng chọn giới tính.");
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
