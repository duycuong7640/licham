@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.fortune-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Bói vui - Sinh con hợp tuổi</p>
            <h1>Xem Tuổi Sinh Con Hợp Bố Mẹ, Chọn Năm Sinh Con Tốt</h1>
            <p>
                Tra cứu tuổi sinh con hợp bố mẹ theo can chi, ngũ hành, thiên can, địa chi và tuổi mụ.
                Kết quả giúp bạn tham khảo năm sinh con phù hợp, tuổi con hợp - khắc với bố mẹ và những lưu ý quan trọng
                khi chọn thời điểm sinh em bé.
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
                <div class="wform-picker-container wform-col-6">
                    <label class="wform-label">Năm dự kiến sinh</label>

                    <select name="expectedBirthYear" class="wform-select">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option value="{{ $i }}" {{ request('expectedBirthYear', 2021) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="wform-picker-container wform-col-6">
                    <div class="wform-submit-wrap">
                        <button type="submit" class="wform-btn-submit btn-sm-form">Kết quả</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="wrap-content-scht">
            @if(!empty($data['detail']))
                @php
                    $detail = $data['detail'];
                    $father = $detail['father'] ?? [];
                    $mother = $detail['mother'] ?? [];
                    $child = $detail['child'] ?? [];
                    $reading = $detail['reading'] ?? [];
                    $focus = $detail['focus'] ?? [];
                    $readingFocus = $reading['focus'] ?? [];
                @endphp

                <div class="scht-result-box">
                    <h2>{{ $reading['headline'] ?? 'Kết quả sinh con hợp tuổi' }}</h2>

                    @if(!empty($reading['summary']))
                        <p class="scht-summary">{{ $reading['summary'] }}</p>
                    @endif

                    <div class="scht-score-box">
                        <p>
                            <strong>{{ $detail['totalScore'] ?? 0 }}/{{ $detail['maxScore'] ?? 10 }}</strong>
                            <span>{{ $detail['level'] ?? '' }}</span>
                            <span>{{ $detail['percent'] ?? 0 }}%</span>
                        </p>
                    </div>

                    <div class="scht-info-grid">
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

                        <div>
                            <h3>Năm sinh con dự kiến</h3>
                            <p><strong>Năm:</strong> <span>{{ $detail['expectedBirthYear'] ?? '' }}</span></p>
                            <p><strong>Can chi:</strong> <span>{{ $child['canChi'] ?? '' }}</span></p>
                            <p><strong>Nạp âm:</strong> <span>{{ $child['napAm']['name'] ?? '' }}</span></p>
                            <p><strong>Ngũ hành:</strong> <span>{{ $child['napAm']['elementLabel'] ?? '' }}</span></p>
                        </div>
                    </div>

                    <div class="scht-card">
                        <h3>Kết luận nhanh</h3>

                        @if(!empty($focus['oneLineAdvice']))
                            <p>{{ $focus['oneLineAdvice'] }}</p>
                        @elseif(!empty($readingFocus['oneLineAdvice']))
                            <p>{{ $readingFocus['oneLineAdvice'] }}</p>
                        @endif

                        @php
                            $strongest = $focus['strongest'] ?? ($readingFocus['strongest'] ?? null);
                            $needsAttention = $focus['needsAttention'] ?? ($readingFocus['needsAttention'] ?? null);
                        @endphp

                        @if(!empty($strongest))
                            <p>
                                <strong>Điểm sáng:</strong>
                                <span>{{ is_array($strongest) ? implode(', ', $strongest) : $strongest }}</span>
                            </p>
                        @endif

                        @if(!empty($needsAttention))
                            <p>
                                <strong>Cần lưu ý:</strong>
                                <span>{{ is_array($needsAttention) ? implode(', ', $needsAttention) : $needsAttention }}</span>
                            </p>
                        @endif
                    </div>

                    @if(!empty($detail['criteria']))
                        <div class="scht-card">
                            <h3>Bóc tách 3 tiêu chí</h3>

                            <div class="scht-criteria-list">
                                @foreach($detail['criteria'] as $item)
                                    <div class="scht-criteria-item">
                                        <div class="scht-criteria-head">
                                            <strong>{{ $item['title'] ?? '' }}</strong>
                                            <span>{{ $item['score'] ?? 0 }}/{{ $item['maxScore'] ?? 0 }} - {{ $item['rating'] ?? '' }}</span>
                                        </div>

                                        @if(!empty($item['conclusion']))
                                            <p>{{ $item['conclusion'] }}</p>
                                        @endif

                                        @if(!empty($item['details']))
                                            @foreach($item['details'] as $line)
                                                <p class="scht-small-line">{{ $line }}</p>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($detail['recommendedYears']))
                        <div class="scht-card">
                            <h3>Năm sinh con có thể tham khảo thêm</h3>

                            <div class="scht-year-list">
                                @foreach($detail['recommendedYears'] as $year)
                                    <p>
                                        <strong>{{ $year['year'] ?? '' }}</strong>
                                        <span>{{ $year['canChi'] ?? '' }}</span>
                                        <span>{{ $year['score'] ?? 0 }}/10 điểm</span>
                                        <span>{{ $year['level'] ?? '' }}</span>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($reading['longReading']))
                        <h3 class="scht-section-title">Luận giải chi tiết</h3>

                        @foreach($reading['longReading'] as $section)
                            <div class="scht-card scht-report-section">
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
                        <div class="scht-card scht-poem">
                            <h3>Thơ luận</h3>

                            @foreach($reading['poem'] as $line)
                                <p><span>{{ $line }}</span></p>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($detail['note']))
                        <div class="scht-note">
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
