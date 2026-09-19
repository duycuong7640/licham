@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.age-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Xem tuổi làm nhà năm {{ date('Y') }} theo tuổi gia chủ</p>
            <h1>{{ !empty($data['hashTag']) ? $data['hashTag']['h1'] : $data['category']['h1'] }}</h1>
            <p>{!! !empty($data['hashTag']) ? $data['hashTag']['description'] : $data['category']['description'] !!}</p>
        </div>

        @php
            $solarDate = request('solarDate', '1995-05-15');
            [$maleYear, $maleMonth, $maleDay] = explode('-', $solarDate);

            $row = $data['detail'];
        @endphp
        <form class="wform-child-age-form" action="" method="get" aria-label="Chọn ngày cần xem">
            <input type="hidden" name="solarDate" id="solarDate" value="{{ $solarDate }}">
            <div class="wform-child-age-grid">
                <div class="wform-picker-container wform-col-6">
                    <label class="fortune-label">Ngày sinh gia chủ (Dương lịch)</label>
                    <div class="fortune-input-wrapper">
                        <input type="text" id="fortune-display-1" class="fortune-input" readonly
                               placeholder="Ngày sinh của gia chủ..." autocomplete="off">

                        <div id="fortune-group-1" class="fortune-select-group fortune-hidden">
                            <select id="fortune-day-1">
                                <option value="">Ngày</option>
                            </select>
                            <select id="fortune-month-1">
                                <option value="">Tháng</option>
                            </select>
                            <select id="fortune-year-1">
                                <option value="">Năm</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="wform-picker-container wform-col-6">
                    <label class="fortune-label">Giờ sinh</label>
                    <div class="fortune-input-wrapper">
                        <select aria-label="Năm" name="birthHour" id="birthHour" class="fortune-select-year-view" style="font-weight: normal;">
                            @foreach(dataKey::GIO_SINH as $k => $r)
                                <option value="{{ $k }}" {{ request('birthHour', 'ty') == $k ? 'selected' : '' }}>
                                    {{ $r }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="wform-picker-container wform-col-6">
                    <label class="fortune-label">Giới tính</label>
                    <div class="fortune-input-wrapper">
                        <select aria-label="Năm" name="gender" id="gender" class="fortune-select-year-view" style="font-weight: normal;">
                            <option value="male" {{ request('gender', 'male') == 'male' ? 'selected' : '' }}>
                                Nam
                            </option>
                            <option value="female" {{ request('gender', 'unknown') == 'female' ? 'selected' : '' }}>
                                Nữ
                            </option>
                        </select>
                    </div>
                </div>

                <div class="wform-picker-container wform-col-6">
                    <label class="fortune-label">Năm dự kiến làm nhà</label>
                    <div class="fortune-input-wrapper">
                        <select aria-label="Năm" name="buildingYear" id="buildingYear" class="fortune-select-year-view" style="font-weight: normal;">
                            @for($i = 1900; $i <= 2050; $i++)
                                <option
                                    value="{{ $i }}" {{ request('buildingYear', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="wform-submit-wrap">
                    <button type="submit" class="fortune-btn-submit-inline btn-sm-form">Xem kết quả</button>
                </div>
            </div>
        </form>
        @if(!empty($row['reading']))
            <div class="wrap-content-love pd-0">
                @php
                    $reading = $row['reading'] ?? [];
                @endphp
                <div class="love-result-box pd-0 mb-0">
                    <h2>Tuổi {{ $row['owner']['canChi'] }} {{ $row['owner']['lunarYear'] }} làm nhà năm {{ $row['input']['buildingYear'] }} có tốt không?</h2>
                    @foreach($row['reading']['sections'] as $value)
                        @if(in_array($value['key'], ['overview']))
                            <p class="love-summary mb-6">{{ $value['content'] }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            <section class="check-age-wrap">
                <div class="check-age-content">
                    <div class="check-age-content-block">
                        @foreach($row['reading']['sections'] as $value)
                            @if(!in_array($value['key'], ['overview']))
                                <h3>{{ $value['title'] }}</h3>
                                <p>{{ $value['content'] }}</p>
                            @endif
                        @endforeach
                    </div>

                    <p>
                        Xem tuổi làm nhà là hình thức tham khảo theo quan niệm dân gian, dựa trên tuổi gia chủ
                        và các yếu tố như Kim Lâu, Hoang Ốc, Tam Tai. Kết quả chỉ mang tính tham khảo khi lựa chọn
                        thời điểm xây dựng, sửa chữa hoặc động thổ; quyết định thực tế vẫn nên cân nhắc thêm điều kiện
                        tài chính, pháp lý, thiết kế, thi công và nhu cầu của gia đình.
                    </p>
                </div>
            </section>
        @endif
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

            document.addEventListener("click", function () {
                document.querySelectorAll(".fortune-select-group").forEach(function (group) {
                    group.classList.add("fortune-hidden");
                });
            });
        });
    </script>
@endsection
