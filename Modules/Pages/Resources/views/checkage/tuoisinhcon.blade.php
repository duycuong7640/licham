@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.age-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Xem tuổi vợ chồng hợp khắc theo năm sinh</p>
            <h1>{{ !empty($data['hashTag']) ? $data['hashTag']['h1'] : $data['category']['h1'] }}</h1>
            <p>{!! !empty($data['hashTag']) ? $data['hashTag']['description'] : $data['category']['description'] !!}</p>
        </div>

        @php
            $fatherSolarDate = request('fatherSolarDate', '1995-05-15');
            $motherSolarDate = request('motherSolarDate', '1998-08-18');
            $childSolarDate = request('childSolarDate', '2022-09-25');

            [$fatherYear, $fatherMonth, $fatherDay] = explode('-', $fatherSolarDate);
            [$motherYear, $motherMonth, $motherDay] = explode('-', $motherSolarDate);
            [$childYear, $childMonth, $childDay] = explode('-', $childSolarDate);
            $row = $data['detail'];
        @endphp
        <form class="fortune-date-lookup-row" action="" method="get" aria-label="Chọn ngày cần xem">
            <input type="hidden" name="fatherSolarDate" id="fatherSolarDate" value="{{ $fatherSolarDate }}">
            <input type="hidden" name="motherSolarDate" id="motherSolarDate" value="{{ $motherSolarDate }}">
            <input type="hidden" name="childSolarDate" id="childSolarDate" value="{{ $childSolarDate }}">

            <div class="fortune-picker-container">
                <label class="fortune-label">Ngày sinh bố (DL)</label>
                <div class="fortune-input-wrapper">
                    <input type="text" id="fortune-display-1" class="fortune-input" readonly
                           placeholder="Ngày sinh của bố..." autocomplete="off">

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

            <div class="fortune-picker-container">
                <label class="fortune-label">Ngày sinh mẹ (DL)</label>
                <div class="fortune-input-wrapper">
                    <input type="text" id="fortune-display-2" class="fortune-input" readonly
                           placeholder="Ngày sinh của mẹ..." autocomplete="off">

                    <div id="fortune-group-2" class="fortune-select-group fortune-hidden">
                        <select id="fortune-day-2">
                            <option value="">Ngày</option>
                        </select>
                        <select id="fortune-month-2">
                            <option value="">Tháng</option>
                        </select>
                        <select id="fortune-year-2">
                            <option value="">Năm</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="fortune-picker-container">
                <label class="fortune-label">Ngày sinh con (DL)</label>
                <div class="fortune-input-wrapper">
                    <input type="text" id="fortune-display-3" class="fortune-input" readonly
                           placeholder="Ngày sinh của con..." autocomplete="off">

                    <div id="fortune-group-3" class="fortune-select-group fortune-hidden">
                        <select id="fortune-day-3">
                            <option value="">Ngày</option>
                        </select>
                        <select id="fortune-month-3">
                            <option value="">Tháng</option>
                        </select>
                        <select id="fortune-year-3">
                            <option value="">Năm</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="fortune-btn-submit-inline btn-sm-form">Xem kết quả</button>
        </form>
        @if(!empty($row['criteria']))
            <div class="wrap-content-love pd-0">
                @php
                    $reading = $row['reading'] ?? [];
                    $father = $row['father'] ?? [];
                    $mother = $row['mother'] ?? [];
                    $child = $row['child'] ?? [];
                @endphp
                <div class="love-result-box pd-0 mb-0">
                    <h2>Bố {{ $father['lunarYear'] }}, mẹ {{ $mother['lunarYear'] }} và con {{ $child['lunarYear'] }} có
                        hợp tuổi không?</h2>
                    @foreach($row['reading']['longReading'] as $value)
                        @if(in_array($value['key'], ['overview']))
                            <p class="love-summary mb-6">{{ $value['content'] }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            <section class="check-age-wrap">
                <div class="check-age-content">
                    <div class="check-age-content-block">
                        @foreach($row['reading']['longReading'] as $value)
                            @if(!in_array($value['key'], ['overview']))
                                <h3>{{ $value['title'] }}</h3>
                                <p>{{ $value['content'] }}</p>
                            @endif
                        @endforeach
                    </div>

                    <p>
                        Xem tuổi bố mẹ và con là hình thức tham khảo theo quan niệm dân gian, không phải phương pháp dự
                        đoán khoa học.
                        Kết quả luận giải được tổng hợp dựa trên Can Chi, Ngũ hành, Nạp âm, cung mệnh và các mối quan hệ
                        hợp – xung
                        giữa tuổi của bố, mẹ và con. Trong đời sống gia đình thực tế, sự hòa hợp còn phụ thuộc vào tình
                        yêu thương,
                        cách nuôi dạy, sự thấu hiểu, sẻ chia và đồng hành giữa các thành viên.
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
                "fatherSolarDate",
                "{{ $fatherDay }}",
                "{{ $fatherMonth }}",
                "{{ $fatherYear }}"
            );

            initFortuneDatePicker(
                "fortune-display-2",
                "fortune-group-2",
                "fortune-day-2",
                "fortune-month-2",
                "fortune-year-2",
                "motherSolarDate",
                "{{ $motherDay }}",
                "{{ $motherMonth }}",
                "{{ $motherYear }}"
            );

            initFortuneDatePicker(
                "fortune-display-3",
                "fortune-group-3",
                "fortune-day-3",
                "fortune-month-3",
                "fortune-year-3",
                "childSolarDate",
                "{{ $childDay }}",
                "{{ $childMonth }}",
                "{{ $childYear }}"
            );

            document.addEventListener("click", function () {
                document.querySelectorAll(".fortune-select-group").forEach(function (group) {
                    group.classList.add("fortune-hidden");
                });
            });
        });
    </script>
@endsection
