@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.age-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Xem tuổi xông đất - Tra cứu tuổi xông nhà hợp tuổi</p>
            <h1>{{ !empty($data['hashTag']) ? $data['hashTag']['h1'] : $data['category']['h1'] }}</h1>
            <p>{!! !empty($data['hashTag']) ? $data['hashTag']['description'] : $data['category']['description'] !!}</p>
        </div>

        @php
            $solarDate = request('solarDate', '1998-08-18');
            $row = $data['detail'];
        @endphp
        <form class="fortune-date-lookup-row mb-8" action="" method="get" aria-label="Chọn ngày cần xem">
            <input type="hidden" name="solarDate" id="solarDate" value="{{ $solarDate }}">

            <div class="fortune-picker-container">
                <label class="fortune-label">Nhập ngày sinh để xem (Dương lịch)</label>
                <div class="fortune-input-wrapper">
                    <input type="text" id="fortune-display" class="fortune-input" readonly placeholder="Ngày sinh..."
                           autocomplete="off">
                    <div id="fortune-group" class="fortune-select-group fortune-hidden">
                        <select id="fortune-day">
                            <option value="">Ngày</option>
                        </select>
                        <select id="fortune-month">
                            <option value="">Tháng</option>
                        </select>
                        <select id="fortune-year">
                            <option value="">Năm</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="fortune-picker-container">
                <label class="fortune-label">Năm cần xem</label>
                <div class="fortune-input-wrapper">
                    <select aria-label="Năm" name="targetYear" id="yearSelect" class="fortune-select-year-view">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option
                                value="{{ $i }}" {{ request('targetYear', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <button type="submit" class="fortune-btn-submit-inline btn-sm-form">Xem kết quả</button>
        </form>
        @if(!empty($row['input']) && !empty($row['ownerYearContext']))
            <section class="check-age-wrap">
                <div class="check-age-card">
                    <div class="check-age-card-head">
                        <div>
                            <span class="check-age-eyebrow">Thông tin tra cứu</span>
                            <h2>Thông tin gia chủ</h2>
                        </div>
                        <span class="check-age-badge">Xông đất {{ $row['input']['targetYear'] }}</span>
                    </div>
                    <div class="check-age-info-grid">
                        <div class="check-age-info-item">
                            <span class="check-age-info-label">
                                Ngày sinh dương lịch
                            </span>
                            <strong class="check-age-info-value">
                                {{ $row['owner']['solarDate'] }}
                            </strong>
                        </div>
                        <div class="check-age-info-item">
                            <span class="check-age-info-label">
                                Ngày sinh âm lịch
                            </span>
                            <strong class="check-age-info-value">
                                {{ $row['owner']['lunarDate'] }}
                                <small>{{ $row['owner']['canChi'] }}</small>
                            </strong>
                        </div>
                        <div class="check-age-info-item">
                            <span class="check-age-info-label">
                                Năm xem tuổi xông đất
                            </span>
                            <strong class="check-age-info-value check-age-highlight">
                                {{ $row['targetYear']['lunarYear'] }}
                                <small>{{ $row['targetYear']['canChi'] }}</small>
                            </strong>
                        </div>

                    </div>

                </div>
                <div class="check-age-card check-age-result">
                    <div class="check-age-result-head">
                        <div>
                            <span class="check-age-eyebrow">
                                Kết quả gợi ý
                            </span>
                            <h2>
                                Tuổi xông đất
                                năm {{ $row['targetYear']['canChi'] }} {{ $row['targetYear']['lunarYear'] }}
                                cho gia chủ {{ $row['owner']['canChi'] }}
                            </h2>
                            <p>
                                Các tuổi xông đất <span class="good-text">Tốt</span> cho gia chủ
                                năm {{ $row['targetYear']['canChi'] }} {{ $row['targetYear']['lunarYear'] }} là:
                            </p>
                        </div>
                    </div>
                    <div class="check-age-ranking mb-6">
                        <div class="check-age-ranking-head">
                            <span>Hạng</span>
                            <span>Tuổi xông đất</span>
                            <span>Mệnh</span>
                            <span>Điểm</span>
                            <span>Đánh giá</span>
                        </div>
                        @if(!empty($row['goodCandidates']))
                            @foreach($row['goodCandidates'] as $k=>$value)
                                <div class="check-age-ranking-row @if($k <= 2) check-age-top @endif">
                                    <span class="check-age-rank">{{ $k + 1 }}</span>
                                    <div class="check-age-person">
                                        <strong>{{ $value['visitor']['canChi'] }}</strong>
                                        <span>({{ $value['visitor']['birthYear'] }})</span>
                                    </div>
                                    <div class="check-age-element">
                                        <strong>{{ $value['visitor']['napAm'] }}</strong>
                                        <span>Mệnh {{ $value['visitor']['element'] }}</span>
                                    </div>
                                    <div class="check-age-score">
                                        <strong>{{ $value['result']['score'] }}</strong>
                                        <span>/12</span>
                                    </div>
                                    <div class="check-age-status-wrap" tabindex="0">
                                        <span class="check-age-status check-age-status-excellent">
                                            Tốt
                                            <span class="check-age-status-help">?</span>
                                        </span>
                                        <div class="check-age-tooltip">
                                            <strong>Vì sao
                                                tuổi {{ $value['visitor']['canChi'] }} {{ $value['visitor']['birthYear'] }}
                                                phù hợp?</strong>
                                            <p>{{ $value['reading']['summary'] }}</p>
                                            <div class="check-age-tooltip-score">
                                                Điểm tổng hợp:
                                                <b>{{ $value['result']['score'] }}/12</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="check-age-result-head">
                        <div>
                            <p>
                                Các tuổi xông đất <span class="good-text">Xấu</span> cho gia chủ
                                năm {{ $row['targetYear']['canChi'] }} {{ $row['targetYear']['lunarYear'] }} là:
                            </p>
                        </div>
                    </div>
                    <div class="check-age-ranking">
                        <div class="check-age-ranking-head">
                            <span>Hạng</span>
                            <span>Tuổi xông đất</span>
                            <span>Mệnh</span>
                            <span>Điểm</span>
                            <span>Đánh giá</span>
                        </div>
                        @if(!empty($row['badCandidates']))
                            @foreach($row['badCandidates'] as $k=>$value)
                                <div class="check-age-ranking-row @if($k <= 2) check-age-top @endif">
                                    <span class="check-age-rank">{{ $k + 1 }}</span>
                                    <div class="check-age-person">
                                        <strong>{{ $value['visitor']['canChi'] }}</strong>
                                        <span>({{ $value['visitor']['birthYear'] }})</span>
                                    </div>
                                    <div class="check-age-element">
                                        <strong>{{ $value['visitor']['napAm'] }}</strong>
                                        <span>Mệnh {{ $value['visitor']['element'] }}</span>
                                    </div>
                                    <div class="check-age-score">
                                        <strong>{{ $value['result']['score'] }}</strong>
                                        <span>/12</span>
                                    </div>
                                    <div class="check-age-status-wrap" tabindex="0">
                                        <span class="check-age-status check-age-status-bad">
                                            Xấu
                                            <span class="check-age-status-help">?</span>
                                        </span>
                                        <div class="check-age-tooltip">
                                            <strong>Vì sao
                                                tuổi {{ $value['visitor']['canChi'] }} {{ $value['visitor']['birthYear'] }}
                                                không phù hợp?</strong>
                                            <p>{{ $value['reading']['summary'] }}</p>
                                            <div class="check-age-tooltip-score">
                                                Điểm tổng hợp:
                                                <b>{{ $value['result']['score'] }}/12</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="check-age-content">
                    <div class="check-age-content-block">
                        <h2>Vì sao những tuổi trên phù hợp xông đất năm {{ $row['input']['targetYear'] }}?</h2>
                        @foreach($row['reading']['sections'] as $value)
                            @if(in_array($value['key'], ['overview']))
                                <p>{{ $value['content'] }}</p>
                            @endif
                        @endforeach
                    </div>

                    <div class="check-age-note">
                        <div class="check-age-note-icon">i</div>
                        <div>
                            <strong>Lưu ý khi chọn người xông đất</strong>
                            @foreach($row['reading']['sections'] as $value)
                                @if($value['key'] == 'custom')
                                    <p>{{ $value['content'] }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @foreach($row['reading']['sections'] as $value)
                        @if(in_array($value['key'], ['good_ages', 'bad_ages', 'method']))
                            <p>{{ $value['content'] }}</p>
                        @endif
                    @endforeach

                    <p>Xem tuổi xông đất là một phong tục dân gian mang ý nghĩa cầu may đầu năm, không phải phương pháp dự báo khoa học. Kết quả tra cứu được tham khảo dựa trên Can Chi, Ngũ hành, Nạp âm và các mối quan hệ hợp – xung. Khi lựa chọn người xông đất thực tế, gia chủ nên ưu tiên người khỏe mạnh, vui vẻ, gia đạo thuận hòa và có thiện ý để tạo không khí tích cực cho năm mới.</p>
                </div>
            </section>
        @endif
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
