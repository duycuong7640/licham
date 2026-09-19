@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.fortune-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Bói vui - Sinh con theo ý muốn</p>
            <h1>Xem Sinh Con Theo Ý Muốn {{ date('Y') }} - Luận Trai Gái Theo Tuổi Bố Mẹ</h1>
            <p>
                Tra cứu sinh con trai hay con gái theo kinh nghiệm dân gian dựa trên tuổi âm của bố mẹ, tháng thụ thai và tháng sinh dự kiến. Kết quả dùng để tham khảo vui, không thay thế tư vấn y khoa và không nên dùng để tạo áp lực lựa chọn giới tính.
            </p>
        </div>

        <form class="wform-child-age-form" action="" method="get" aria-label="Xem sinh con hợp tuổi">
            <div class="wform-child-age-grid">
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Ngày sinh Bố (Dương lịch)</label>
                    <div class="wform-input-wrapper">
                        <input
                            type="text"
                            id="wform-father-display"
                            class="wform-input"
                            readonly
                            placeholder="dd/mm/yyyy"
                            autocomplete="off"
                            value="{{ request('fatherSolarDate', '01/06/1990') }}"
                        >
                        <input
                            type="hidden"
                            name="fatherSolarDate"
                            id="wform-father-value"
                            value="{{ request('fatherSolarDate', '01/06/1990') }}"
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
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Ngày sinh Mẹ (Dương lịch)</label>
                    <div class="wform-input-wrapper">
                        <input
                            type="text"
                            id="wform-mother-display"
                            class="wform-input"
                            readonly
                            placeholder="dd/mm/yyyy"
                            autocomplete="off"
                            value="{{ request('motherSolarDate', '01/06/1996') }}"
                        >
                        <input
                            type="hidden"
                            name="motherSolarDate"
                            id="wform-mother-value"
                            value="{{ request('motherSolarDate', '01/06/1996') }}"
                        >
                        <div id="wform-mother-group" class="wform-select-group wform-hidden">
                            <select id="wform-mother-day">
                                <option value="">Ngày</option>
                            </select>

                            <select id="wform-mother-month">
                                <option value="">Tháng</option>
                            </select>

                            <select id="wform-mother-year">
                                <option value="">Năm</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="wform-picker-container wform-col-3">
                    <label class="wform-label">Tháng thụ thai (DL)</label>

                    <select name="conceptionMonth" class="wform-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('conceptionMonth', 6) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="wform-picker-container wform-col-3">
                    <label class="wform-label">Năm thụ thai (DL)</label>

                    <select name="conceptionYear" class="wform-select">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option value="{{ $i }}" {{ request('conceptionYear', 2021) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="wform-picker-container wform-col-3">
                    <label class="wform-label">Tháng sinh nở (DL)</label>

                    <select name="birthMonth" class="wform-select">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('birthMonth', 4) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="wform-picker-container wform-col-3">
                    <label class="wform-label">Năm sinh nở (DL)</label>

                    <select name="birthYear" class="wform-select">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option value="{{ $i }}" {{ request('birthYear', 2022) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="wform-submit-wrap">
                    <button type="submit" class="wform-btn-submit btn-sm-form">Kết quả</button>
                </div>
            </div>
        </form>
        <div class="wrap-content-sctym">
            @if(!empty($data['detail']))
                @php
                    $detail = $data['detail'];

                    $father = $detail['father'] ?? [];
                    $mother = $detail['mother'] ?? [];
                    $conception = $detail['conception'] ?? [];
                    $birth = $detail['birth'] ?? [];
                    $ages = $detail['ages'] ?? [];
                    $result = $detail['result'] ?? [];
                    $reading = $detail['reading'] ?? [];
                    $focus = $reading['focus'] ?? [];
                @endphp

                <div class="sctym-result-box">
                    <h2>{{ $reading['headline'] ?? 'Kết quả sinh con theo ý muốn' }}</h2>

                    @if(!empty($reading['summary']))
                        <p class="sctym-summary">{{ $reading['summary'] }}</p>
                    @endif

                    <div class="sctym-score-box">
                        <p>
                            <strong>{{ $result['finalGenderLabel'] ?? 'Đang tham khảo' }}</strong>
                            <span>{{ $result['agreementLevel'] ?? '' }}</span>
                            <span>Trai {{ $result['boyPercent'] ?? 0 }}%</span>
                            <span>Gái {{ $result['girlPercent'] ?? 0 }}%</span>
                        </p>
                    </div>

                    <div class="sctym-info-grid sctym-info-grid-2">
                        <div>
                            <h3>Thông tin bố</h3>
                            <p><strong>Ngày dương:</strong> <span>{{ $father['solarDate'] ?? '' }}</span></p>
                            <p><strong>Ngày âm:</strong> <span>{{ $father['lunarDate'] ?? '' }}</span></p>
                            <p><strong>Năm âm lịch:</strong> <span>{{ $father['lunarYear'] ?? '' }}</span></p>
                            <p><strong>Tuổi:</strong> <span>{{ $father['canChi'] ?? '' }}</span></p>
                            <p><strong>Nạp âm:</strong> <span>{{ $father['napAm']['name'] ?? '' }}</span></p>
                            <p><strong>Ngũ hành:</strong> <span>{{ $father['napAm']['elementLabel'] ?? '' }}</span></p>
                        </div>

                        <div>
                            <h3>Thông tin mẹ</h3>
                            <p><strong>Ngày dương:</strong> <span>{{ $mother['solarDate'] ?? '' }}</span></p>
                            <p><strong>Ngày âm:</strong> <span>{{ $mother['lunarDate'] ?? '' }}</span></p>
                            <p><strong>Năm âm lịch:</strong> <span>{{ $mother['lunarYear'] ?? '' }}</span></p>
                            <p><strong>Tuổi:</strong> <span>{{ $mother['canChi'] ?? '' }}</span></p>
                            <p><strong>Nạp âm:</strong> <span>{{ $mother['napAm']['name'] ?? '' }}</span></p>
                            <p><strong>Ngũ hành:</strong> <span>{{ $mother['napAm']['elementLabel'] ?? '' }}</span></p>
                        </div>
                    </div>

                    <div class="sctym-info-grid sctym-info-grid-3">
                        <div>
                            <h3>Tháng thụ thai</h3>
                            <p><strong>Dương lịch:</strong> <span>Tháng {{ $conception['solarMonth'] ?? '' }}/{{ $conception['solarYear'] ?? '' }}</span></p>
                            <p><strong>Âm lịch:</strong> <span>Tháng {{ $conception['lunarMonth'] ?? '' }}/{{ $conception['lunarYear'] ?? '' }}</span></p>
                            <p><strong>Mốc quy đổi:</strong> <span>{{ $conception['conversionAnchor'] ?? '' }}</span></p>
                        </div>

                        <div>
                            <h3>Tháng sinh dự kiến</h3>
                            <p><strong>Dương lịch:</strong> <span>Tháng {{ $birth['solarMonth'] ?? '' }}/{{ $birth['solarYear'] ?? '' }}</span></p>
                            <p><strong>Âm lịch:</strong> <span>Tháng {{ $birth['lunarMonth'] ?? '' }}/{{ $birth['lunarYear'] ?? '' }}</span></p>
                            <p><strong>Mốc quy đổi:</strong> <span>{{ $birth['conversionAnchor'] ?? '' }}</span></p>
                        </div>

                        <div>
                            <h3>Tuổi âm khi sinh</h3>
                            <p><strong>Tuổi âm bố:</strong> <span>{{ $ages['fatherLunarAgeAtBirth'] ?? '' }}</span></p>
                            <p><strong>Tuổi âm mẹ:</strong> <span>{{ $ages['motherLunarAgeAtBirth'] ?? '' }}</span></p>
                            <p><strong>Tuổi mẹ tra bảng:</strong> <span>{{ $ages['motherLunarAgeForChineseChart'] ?? '' }}</span></p>
                        </div>
                    </div>

                    <div class="sctym-card">
                        <h3>Kết luận nhanh</h3>

                        @if(!empty($focus['oneLineAdvice']))
                            <p>{{ $focus['oneLineAdvice'] }}</p>
                        @endif

                        <p><strong>Kết quả nghiêng về:</strong> <span>{{ $focus['finalGender'] ?? ($result['finalGenderLabel'] ?? '') }}</span></p>
                        <p><strong>Mức đồng thuận:</strong> <span>{{ $focus['agreement'] ?? ($result['agreementLevel'] ?? '') }}</span></p>
                        <p><strong>Số phép nghiêng trai:</strong> <span>{{ $result['boyVotes'] ?? 0 }}/{{ $result['totalMethods'] ?? 4 }}</span></p>
                        <p><strong>Số phép nghiêng gái:</strong> <span>{{ $result['girlVotes'] ?? 0 }}/{{ $result['totalMethods'] ?? 4 }}</span></p>
                    </div>

                    @if(!empty($detail['methods']))
                        <div class="sctym-card">
                            <h3>Các phép tính dân gian</h3>

                            <div class="sctym-method-list">
                                @foreach($detail['methods'] as $method)
                                    <div class="sctym-method-item">
                                        <div class="sctym-method-head">
                                            <strong>{{ $method['title'] ?? '' }}</strong>
                                            <span>{{ $method['genderLabel'] ?? '' }}</span>
                                        </div>

                                        @if(!empty($method['details']))
                                            @foreach($method['details'] as $line)
                                                <p>{{ $line }}</p>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($detail['monthSuggestions']))
                        <div class="sctym-card">
                            <h3>Gợi ý tháng thụ thai cùng năm</h3>

                            <div class="sctym-month-list">
                                @foreach($detail['monthSuggestions'] as $month)
                                    <p>
                                        <strong>Tháng {{ $month['conceptionMonth'] ?? '' }}</strong>
                                        <span>Âm {{ $month['conceptionLunarMonth'] ?? '' }}</span>
                                        <span>Trai {{ $month['boyVotes'] ?? 0 }}/4</span>
                                        <span>Gái {{ $month['girlVotes'] ?? 0 }}/4</span>
                                        <span>{{ $month['label'] ?? '' }}</span>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($reading['longReading']))
                        <h3 class="sctym-section-title">Luận giải chi tiết</h3>

                        @foreach($reading['longReading'] as $section)
                            <div class="sctym-card sctym-report-section">
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
                        <div class="sctym-card sctym-poem">
                            <h3>Thơ luận</h3>

                            @foreach($reading['poem'] as $line)
                                <p><span>{{ $line }}</span></p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($detail['note']))
                        <div class="sctym-note">
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
        document.addEventListener('DOMContentLoaded', function () {
            const WFORM_MIN_YEAR = 1900;
            const WFORM_MAX_YEAR = 2050;

            function wformPad2(value) {
                return String(value).padStart(2, '0');
            }

            function wformDaysInMonth(month, year) {
                return new Date(year, month, 0).getDate();
            }

            function wformParseDate(value) {
                if (!value) {
                    return {
                        day: '',
                        month: '',
                        year: ''
                    };
                }

                const parts = value.trim().split('/');

                if (parts.length !== 3) {
                    return {
                        day: '',
                        month: '',
                        year: ''
                    };
                }

                const day = Number(parts[0]);
                const month = Number(parts[1]);
                const year = Number(parts[2]);

                if (
                    !day ||
                    !month ||
                    !year ||
                    month < 1 ||
                    month > 12 ||
                    year < WFORM_MIN_YEAR ||
                    year > WFORM_MAX_YEAR
                ) {
                    return {
                        day: '',
                        month: '',
                        year: ''
                    };
                }

                const maxDay = wformDaysInMonth(month, year);

                if (day < 1 || day > maxDay) {
                    return {
                        day: '',
                        month: '',
                        year: ''
                    };
                }

                return {
                    day: day,
                    month: month,
                    year: year
                };
            }

            function wformFillDay(select, month, year, selectedDay) {
                const currentMonth = Number(month) || 1;
                const currentYear = Number(year) || new Date().getFullYear();
                const maxDay = wformDaysInMonth(currentMonth, currentYear);

                select.innerHTML = '<option value="">Ngày</option>';

                for (let i = 1; i <= maxDay; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = i;

                    if (Number(selectedDay) === i) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                }
            }

            function wformFillMonth(select, selectedMonth) {
                select.innerHTML = '<option value="">Tháng</option>';

                for (let i = 1; i <= 12; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = i;

                    if (Number(selectedMonth) === i) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                }
            }

            function wformFillYear(select, selectedYear) {
                select.innerHTML = '<option value="">Năm</option>';

                for (let i = WFORM_MIN_YEAR; i <= WFORM_MAX_YEAR; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = i;

                    if (Number(selectedYear) === i) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                }
            }

            function wformCloseAllGroups(exceptId = '') {
                document.querySelectorAll('.wform-select-group').forEach(function (group) {
                    if (!exceptId || group.id !== exceptId) {
                        group.classList.add('wform-hidden');
                    }
                });
            }

            function wformUpdateValue(type) {
                const daySelect = document.getElementById('wform-' + type + '-day');
                const monthSelect = document.getElementById('wform-' + type + '-month');
                const yearSelect = document.getElementById('wform-' + type + '-year');
                const displayInput = document.getElementById('wform-' + type + '-display');
                const hiddenInput = document.getElementById('wform-' + type + '-value');

                const day = Number(daySelect.value);
                const month = Number(monthSelect.value);
                const year = Number(yearSelect.value);

                if (!day || !month || !year) {
                    return;
                }

                const value = wformPad2(day) + '/' + wformPad2(month) + '/' + year;

                displayInput.value = value;
                hiddenInput.value = value;
            }

            function wformRefreshDay(type) {
                const daySelect = document.getElementById('wform-' + type + '-day');
                const monthSelect = document.getElementById('wform-' + type + '-month');
                const yearSelect = document.getElementById('wform-' + type + '-year');

                const oldDay = Number(daySelect.value);
                const month = Number(monthSelect.value) || 1;
                const year = Number(yearSelect.value) || new Date().getFullYear();
                const maxDay = wformDaysInMonth(month, year);
                const nextDay = oldDay > maxDay ? maxDay : oldDay;

                wformFillDay(daySelect, month, year, nextDay);
            }

            function wformInitDatePicker(type) {
                const displayInput = document.getElementById('wform-' + type + '-display');
                const group = document.getElementById('wform-' + type + '-group');
                const daySelect = document.getElementById('wform-' + type + '-day');
                const monthSelect = document.getElementById('wform-' + type + '-month');
                const yearSelect = document.getElementById('wform-' + type + '-year');
                const hiddenInput = document.getElementById('wform-' + type + '-value');

                if (!displayInput || !group || !daySelect || !monthSelect || !yearSelect || !hiddenInput) {
                    return;
                }

                const currentDate = wformParseDate(displayInput.value);

                wformFillMonth(monthSelect, currentDate.month);
                wformFillYear(yearSelect, currentDate.year);
                wformFillDay(daySelect, currentDate.month, currentDate.year, currentDate.day);

                if (currentDate.day && currentDate.month && currentDate.year) {
                    const value = wformPad2(currentDate.day) + '/' + wformPad2(currentDate.month) + '/' + currentDate.year;
                    displayInput.value = value;
                    hiddenInput.value = value;
                }

                displayInput.addEventListener('click', function () {
                    const groupId = 'wform-' + type + '-group';

                    wformCloseAllGroups(groupId);
                    group.classList.toggle('wform-hidden');
                });

                daySelect.addEventListener('change', function () {
                    wformUpdateValue(type);
                });

                monthSelect.addEventListener('change', function () {
                    wformRefreshDay(type);
                    wformUpdateValue(type);
                });

                yearSelect.addEventListener('change', function () {
                    wformRefreshDay(type);
                    wformUpdateValue(type);
                });
            }

            wformInitDatePicker('father');
            wformInitDatePicker('mother');

            document.addEventListener('click', function (event) {
                if (!event.target.closest('.wform-input-wrapper')) {
                    wformCloseAllGroups();
                }
            });

            const form = document.querySelector('.wform-child-age-form');

            if (form) {
                form.addEventListener('submit', function (event) {
                    const fatherValue = document.getElementById('wform-father-value')?.value;
                    const motherValue = document.getElementById('wform-mother-value')?.value;

                    if (!wformParseDate(fatherValue).year || !wformParseDate(motherValue).year) {
                        event.preventDefault();
                        alert('Vui lòng chọn đầy đủ ngày sinh của bố và mẹ.');
                    }
                });
            }
        });
    </script>
@endsection
