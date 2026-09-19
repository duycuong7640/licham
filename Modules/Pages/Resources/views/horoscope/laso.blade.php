@extends('pages::layouts.app')

@section('content')
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Lập lá số Tử Vi online</p>
            <h1>Lập lá số Tử Vi online miễn phí, xem luận giải chi tiết</h1>
            <p>Nhập ngày giờ sinh, giới tính và loại lịch để lập lá số Tử Vi nhanh chóng. Hệ thống hỗ trợ xem các cung
                quan trọng, đại vận, tiểu vận và gợi ý luận giải dễ hiểu theo từng phần.</p>
        </div>
        @php
            $currentYear = (int) date('Y');

            $oldName = request('name', '');
            $oldDay = (int) request('day', 1);
            $oldMonth = (int) request('month', 1);
            $oldYear = (int) request('year', 1995);
            $oldCalendarType = request('calendarType', 'solar');
            $oldTimeOfBirth = request('timeOfBirth', array_key_first($hours ?? []));

            $oldGender = request('gender', 'male');
            $oldViewYear = (int) request('viewYear', $currentYear);

            if ($oldDay < 1 || $oldDay > 31) {
                $oldDay = 1;
            }

            if ($oldMonth < 1 || $oldMonth > 12) {
                $oldMonth = 1;
            }

            if ($oldYear < 1900 || $oldYear > $currentYear) {
                $oldYear = 1995;
            }

            if (!in_array($oldCalendarType, ['solar', 'lunar'], true)) {
                $oldCalendarType = 'solar';
            }

            if (!in_array($oldGender, ['male', 'female'], true)) {
                $oldGender = 'male';
            }

            $oldBirthDate = str_pad($oldDay, 2, '0', STR_PAD_LEFT) . '/' . str_pad($oldMonth, 2, '0', STR_PAD_LEFT) . '/' . $oldYear;
        @endphp

        <form action="" method="get" class="horoscope-form mb-4" id="horoscopeForm" novalidate>
            <div class="horoscope-form__grid">
                <label class="horoscope-form__field">
                    <span>Họ và tên</span>
                    <input
                        type="text"
                        name="name"
                        id="horoscopeFormName"
                        value="{{ $oldName }}"
                        placeholder="Ví dụ: Nguyễn An"
                        autocomplete="name"
                    >
                    <small class="horoscope-form__error" data-error-for="name"></small>
                </label>

                <label class="horoscope-form__field horoscope-form__date-picker">
            <span>
                Ngày sinh
                <em id="horoscopeCalendarLabel">
                    {{ $oldCalendarType === 'lunar' ? '(Âm lịch)' : '(Dương lịch)' }}
                </em>
            </span>

                    <input
                        type="text"
                        id="horoscopeFormDate"
                        value="{{ $oldBirthDate }}"
                        placeholder="dd/mm/yyyy"
                        inputmode="numeric"
                        autocomplete="off"
                        readonly
                    >

                    <div class="horoscope-form__date-dropdown" id="horoscopeFormDateDropdown">
                        <select id="horoscopeFormDaySelect" aria-label="Chọn ngày"></select>
                        <select id="horoscopeFormMonthSelect" aria-label="Chọn tháng"></select>
                        <select id="horoscopeFormYearSelect" aria-label="Chọn năm"></select>
                    </div>

                    <small class="horoscope-form__error" data-error-for="birthDate"></small>
                </label>

                <label class="horoscope-form__field">
                    <span>Lịch sinh</span>
                    <select name="calendarType" id="horoscopeFormCalendarType">
                        <option value="solar" {{ $oldCalendarType === 'solar' ? 'selected' : '' }}>Dương lịch</option>
                        <option value="lunar" {{ $oldCalendarType === 'lunar' ? 'selected' : '' }}>Âm lịch</option>
                    </select>
                    <small class="horoscope-form__error" data-error-for="calendarType"></small>
                </label>

                <label class="horoscope-form__field">
                    <span>Giờ sinh</span>
                    <select name="timeOfBirth" id="horoscopeFormTimeOfBirth">
                        @foreach(dataKey::GIO_SINH as $k => $row)
                            <option value="{{ $k }}" {{ (string) $oldTimeOfBirth === (string) $k ? 'selected' : '' }}>
                                {{ $row }}
                            </option>
                        @endforeach
                    </select>
                    <small class="horoscope-form__error" data-error-for="timeOfBirth"></small>
                </label>

                <label class="horoscope-form__field">
                    <span>Giới tính</span>
                    <select name="gender" id="horoscopeFormGender">
                        <option value="male" {{ $oldGender === 'male' ? 'selected' : '' }}>Nam giới</option>
                        <option value="female" {{ $oldGender === 'female' ? 'selected' : '' }}>Nữ giới</option>
                    </select>
                    <small class="horoscope-form__error" data-error-for="gender"></small>
                </label>

                <label class="horoscope-form__field">
                    <span>Năm xem</span>
                    <select name="viewYear" id="horoscopeFormViewYear">
                        @for($y = $currentYear; $y <= $currentYear + 1; $y++)
                            <option value="{{ $y }}" {{ (string) $oldViewYear === (string) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                    <small class="horoscope-form__error" data-error-for="viewYear"></small>
                </label>
            </div>

            <input type="hidden" name="day" id="horoscopeFormDay" value="{{ $oldDay }}">
            <input type="hidden" name="month" id="horoscopeFormMonth" value="{{ $oldMonth }}">
            <input type="hidden" name="year" id="horoscopeFormYear" value="{{ $oldYear }}">

            <div class="horoscope-form__footer">
                <p class="horoscope-form__note" id="horoscopeFormNote"></p>
                <button class="horoscope-form__button button button-red" type="submit">
                    Xem luận giải
                </button>
            </div>
        </form>

        @if(!empty($data['detail']['htmlArr']))
            <div class="wrap-horoscope-view">
                <div id="result">
                    <div class="horoscope-output">
                        <div class="mobile-width-scroll">
                            <div class="mobile-width">
                                {!! str_replace('DOMAIN', '<a href="https://'.env('DOMAIN_RUN').'/" title="'.env('DOMAIN_RUN').'">'.env('DOMAIN_RUN').'</a>', $data['detail']['htmlArr']['laso']) !!}
                                {!! $data['detail']['htmlArr']['script'] !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    {!! $data['detail']['htmlArr']['laso_content']['radar'] !!}
                    @php $content = !empty($data['detail']['htmlArr']['laso_content']['content']) ? $data['detail']['htmlArr']['laso_content']['content'] : ''; @endphp
                    {!! $content !!}
                </div>
            </div>
        @endif
    </div>
@endsection

@section('style')
    <style type="text/css">
        body {
            margin: 0;
            background: #f3f1ee;
            color: #161616;
        }

        .horoscope-output {
            max-width: 1240px;
            margin: 0 auto 0 auto;
            padding: 0 12px;
        }

        .horoscope-output {
            color: #141414;
        }

        .tabs-hs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            max-width: 1220px;
            margin: 8px auto 10px;
        }

        .tab-hs {
            min-width: auto;
            height: 38px;
            padding: 0 14px;
            border: 1px solid #cfcfcf;
            background: #fff;
            color: #333;
            border-radius: 4px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .tab-hs.is-active {
            background: #8b155d;
            border-color: #8b155d;
            color: #fff;
        }

        .tab-panel-hs {
            display: none;
        }

        .tab-panel-hs.is-active {
            display: block;
        }

        .sheet-hs {
            width: min(1220px, 100%);
            margin: 0 auto 18px;
            background: linear-gradient(rgba(255, 252, 245, .5), rgba(255, 252, 245, .5)), url('{{ asset('/static/web/images/Trongdong.a9b32311.svg') }}') center 42% / 1080px no-repeat, #fffcf5;
            border: 1px solid #d6d6d6;
            border-left: 0;
            border-bottom: 0;
            overflow-x: auto;
            position: relative;
            box-shadow: 0 1px 0 rgba(0, 0, 0, .06);
        }

        .grid-hs {
            /*min-width: 1120px;*/
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(4, 278px);
            position: relative;
            z-index: 2;
        }

        .palace-hs {
            position: relative;
            z-index: 2;
            border-left: 1px solid rgba(190, 190, 190, .64);
            border-bottom: 1px solid rgba(190, 190, 190, .64);
            padding: 18px 10px 50px;
            overflow: hidden;
            background: rgba(255, 252, 245, .34);
            cursor: pointer;
            transition: background .15s ease, box-shadow .15s ease;
        }

        .palace-hs:focus-visible, .palace-hs:hover, .palace-hs-fk.is-selected {
            outline: none;
            background: rgba(255, 252, 245, .72);
            box-shadow: inset 0px 0px 8px 1px rgba(32, 32, 32, .14);
        }

        .p0 {
            grid-column: 1;
            grid-row: 1;
        }

        .p1 {
            grid-column: 2;
            grid-row: 1;
        }

        .p2 {
            grid-column: 3;
            grid-row: 1;
        }

        .p3 {
            grid-column: 4;
            grid-row: 1;
        }

        .p4 {
            grid-column: 1;
            grid-row: 2;
        }

        .p5 {
            grid-column: 4;
            grid-row: 2;
        }

        .p6 {
            grid-column: 1;
            grid-row: 3;
        }

        .p7 {
            grid-column: 4;
            grid-row: 3;
        }

        .p8 {
            grid-column: 1;
            grid-row: 4;
        }

        .p9 {
            grid-column: 2;
            grid-row: 4;
        }

        .p10 {
            grid-column: 3;
            grid-row: 4;
        }

        .p11 {
            grid-column: 4;
            grid-row: 4;
        }

        .center-hs {
            grid-column: 2 / 4;
            grid-row: 2 / 4;
            border-left: 1px solid rgba(190, 190, 190, .64);
            border-bottom: 1px solid rgba(190, 190, 190, .64);
            /*padding: 18px 156px 24px 52px;*/
            padding: 18px 10px 9px 45px;
            background: linear-gradient(rgba(255, 252, 245, .5), rgba(255, 252, 245, .5)), url('{{ asset('/static/web/images/Trongdong.a9b32311.svg') }}') center 48% / 720px no-repeat, rgba(255, 252, 245, .5);
            color: #20242c;
            position: relative;
            overflow: hidden;
        }

        .head {
            display: grid;
            grid-template-columns: 40px 1fr 40px;
            align-items: start;
            gap: 8px;
            font-weight: 900;
            font-size: 13px;
            line-height: 1.15;
            margin-bottom: 26px;
        }

        .branch-hs {
            color: #ef4444;
            white-space: nowrap;
        }

        .name {
            text-align: center;
            color: #111;
            text-transform: uppercase;
            letter-spacing: 0;
        }

        .age {
            color: #111;
            text-align: right;
        }

        .stars {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-content: start;
            gap: 6px 12px;
            margin-top: 8px;
            font-weight: 800;
            font-size: 12px;
            line-height: 1.18;
        }

        .star {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .stars .star-left {
            grid-column: 1;
            justify-self: stretch;
            text-align: left;
        }

        .stars .star-right {
            grid-column: 2;
            justify-self: stretch;
            text-align: right;
        }

        .star-major {
            grid-column: span 2;
            justify-self: center;
            text-align: center !important;
            font-size: 13px;
            line-height: 1.18;
            margin-bottom: 3px;
            text-transform: capitalize;
        }

        .star-cycle {
            opacity: .58;
            font-weight: 800;
        }

        .cycle-major-hs, .cycle-minor-hs {
            display: none;
            grid-column: span 2;
            justify-self: center;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 14px;
        }

        .show-major-cycle .cycle-major-hs {
            display: inline-block;
            background: rgba(255, 176, 142, .45);
            color: #c13d12;
        }

        .show-minor-cycle .cycle-minor-hs {
            display: inline-block;
            background: rgba(216, 241, 222, .7);
            color: #15803d;
        }

        .voids {
            position: absolute;
            left: 50%;
            top: 35px;
            transform: translateX(-50%);
            display: flex;
            gap: 6px;
            z-index: 1;
        }

        .void {
            border-radius: 16px;
            background: #3d3d3d;
            color: white;
            padding: 3px 15px;
            font-size: 15px;
            font-weight: 800;
        }

        .void-marker {
            position: absolute;
            left: var(--void-x);
            top: var(--void-y);
            z-index: 5;
            transform: translate(-50%, -50%);
            background: #2f2f2f;
            color: #fff;
            border-radius: 999px;
            padding: 4px 15px 5px;
            font-size: 13px;
            line-height: 1;
            /*font-weight: 900;*/
            /*box-shadow: 0 1px 2px rgba(0,0,0,.16); */
            pointer-events: none;
        }

        .foot {
            position: absolute;
            left: 10px;
            right: 10px;
            bottom: 18px;
            display: grid;
            /*grid-template-columns: 1fr 1fr 1fr;*/
            grid-template-columns: 40px 1fr 40px;
            align-items: end;
            gap: 8px;
            font-weight: 900;
            font-size: 12px;
            color: #15171d;
            padding-top: 10px;
        }

        .foot span:nth-child(2) {
            text-align: center;
        }

        .foot span:nth-child(3) {
            text-align: right;
        }

        .center-brand-hs {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #111;
        }

        .center-brand-hs p {
            margin: 0;
            font-size: 15px;
            line-height: 1.25;
            font-weight: 400;
        }

        .center-brand-hs a {
            display: inline-block;
            margin-top: 8px;
            color: #1a73e8;
            font-size: 17px;
            font-weight: 500;
            text-decoration: underline;
        }

        .center-rule-hs {
            position: relative;
            z-index: 2;
            width: 300px;
            height: 1px;
            background: #202020;
            margin: 8px auto 22px;
        }

        .center-hs h2 {
            margin: 0 0 22px;
            font-size: 17px;
            text-align: center;
            position: relative;
            z-index: 2;
            color: #20242c;
            line-height: 1.1;
            font-weight: 900;
        }

        .info-hs {
            display: grid;
            /*grid-template-columns: 75px 110px 52px minmax(132px, 1fr);*/
            grid-template-columns: 70px 30px 52px minmax(62px, 1fr);
            row-gap: 10px;
            column-gap: 10px;
            font-size: 13px;
            line-height: 1.16;
            position: relative;
            z-index: 2;
            color: #20242c;
            align-items: start;
        }

        .info-hs b {
            font-weight: 400;
            white-space: nowrap;
        }

        .info-hs span {
            font-weight: 900;
        }

        .info-hs .main-value-hs {
            text-align: left;
            white-space: nowrap;
        }

        .info-hs .paren-value-hs {
            text-align: center;
        }

        .info-hs .right-value-hs {
            text-align: left;
        }

        .info-hs .time-value-hs {
            line-height: 1.45;
        }

        .info-hs .wide-value-hs {
            grid-column: span 3;
        }

        .info-hs .with-sub-hs {
            display: grid;
            gap: 8px;
        }

        .info-hs .subvalue-hs {
            display: block;
            font-size: 14px;
            line-height: 1.18;
            white-space: normal;
        }

        .center-signature-hs {
            position: absolute;
            right: 6px;
            bottom: 6px;
            z-index: 1;
            width: 68px;
            pointer-events: none;
            opacity: .82;
        }

        .center-logo-mark-hs {
            width: 65px;
            height: auto;
            overflow: visible;
            filter: drop-shadow(0 1px 0 rgba(255, 252, 245, .92));
        }

        .logo-ink-hs text {
            fill: rgba(31, 36, 45, .72);
            font-size: 58px;
            font-weight: 700;
            text-anchor: middle;
            dominant-baseline: middle;
        }

        .logo-seal-hs rect {
            fill: none;
            stroke: rgba(211, 21, 21, .92);
            stroke-width: 7;
        }

        .logo-seal-hs text {
            fill: rgba(211, 21, 21, .95);
            font-size: 39px;
            font-weight: 800;
            text-anchor: middle;
            dominant-baseline: middle;
        }

        .sheet-dongson-bg-hs, .dongson-bg-hs {
            display: none;
        }

        .center-static-lines-hs {
            position: absolute;
            left: 25%;
            top: 25%;
            width: 50%;
            height: 50%;
            z-index: 1;
            pointer-events: none;
            overflow: visible;
        }

        .center-static-lines-hs line {
            stroke: rgba(90, 90, 90, .32);
            stroke-width: 1.15;
            stroke-linecap: square;
            vector-effect: non-scaling-stroke;
            transition: x1 .18s ease, y1 .18s ease, x2 .18s ease, y2 .18s ease;
        }

        .center-static-lines-hs line:nth-child(1), .center-static-lines-hs line:nth-child(2) {
            stroke: rgba(90, 90, 90, .32);
        }

        .center-static-lines-hs .line-accent {
            stroke: rgba(239, 68, 68, .26);
            stroke-width: 1;
        }

        .center-note-hs {
            text-align: center;
            margin-top: 24px;
            font-size: 22px;
            font-weight: 900;
            position: relative;
            z-index: 2;
            color: #20242c;
        }

        .legend-hs {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 14px;
            border-top: 0;
            border-left: 1px solid #d6d6d6;
            border-bottom: 1px solid #d6d6d6;
            font-size: 11px;
            /*min-width: 1120px;*/
            background: #fffcf5;
            /*position: relative;*/
            z-index: 3;
            color: #20242c;
        }

        .legend-row-hs {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .legend-states-hs {
            justify-content: flex-end;
        }

        .swatch-hs {
            display: inline-block;
            width: 24px;
            height: 10px;
            margin-right: 6px;
            vertical-align: middle;
        }

        .below-output-hs {
            width: min(1220px, 100%);
            margin: 0 auto 0 auto;
            display: grid;
            gap: 12px;
        }

        .forecast-tools-hs {
            display: grid;
            gap: 34px;
            padding: 18px 0 0;
        }

        .year-control-hs {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
            font-size: 28px;
            color: #151515;
        }

        .year-control-hs select {
            height: 52px;
            min-width: 96px;
            border: 1px solid #dedede;
            border-radius: 6px;
            background: #fff;
            padding: 0 14px;
            font-size: 28px;
            font-weight: 800;
            color: #171a21;
        }

        .check-hs {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 27px;
        }

        .check-hs input {
            width: 24px;
            height: 24px;
            accent-color: #c44912;
        }

        .action-row-hs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .action-row-hs button {
            min-width: 0;
            height: 64px;
            border: 0;
            border-radius: 0;
            background: #fff9e8;
            color: #be430d;
            font-size: 25px;
            font-weight: 800;
            cursor: pointer;
        }

        .radar-panel-hs {
            display: grid;
            justify-items: center;
            padding: 45px 0 36px;
        }

        .chart-title-hs {
            background: #ffb08e;
            color: #000;
            border-radius: 7px;
            padding: 10px 20px;
            font-size: 15px;
            margin-bottom: 50px;
        }

        .score-method-note-hs {
            width: min(760px, 100%);
            margin: -18px auto 28px;
            background: #fffaf0;
            border: 1px solid #eadfce;
            border-left: 4px solid #c44912;
            padding: 11px 14px;
            font-size: 15px;
            line-height: 1.55;
            color: #333;
            text-align: left;
        }

        .period-overview-hs .score-method-note-hs {
            width: auto;
            margin: 0 0 12px;
        }

        .radar-chart-hs {
            width: min(330px, 100%);
            height: auto;
            overflow: visible;
        }

        .radar-chart-hs polygon {
            fill: none;
            stroke: #e3e0db;
            stroke-width: 1.5;
        }

        .radar-chart-hs .spokes-hs line {
            stroke: #e3e0db;
            stroke-width: 1.3;
        }

        .radar-chart-hs text {
            fill: #873113;
            font-size: 14px;
            dominant-baseline: middle;
            text-anchor: middle;
        }

        .radar-chart-hs .radar-menh-axis {
            stroke: #873113;
            stroke-width: 2;
        }

        .radar-chart-hs .radar-menh-label {
            fill: #873113;
            font-weight: 900;
            font-size: 16px;
        }

        .radar-chart-hs .radar-focus-axis {
            stroke: #c44912;
            stroke-width: 2;
        }

        .radar-chart-hs .radar-focus-label {
            fill: #ef3e3a;
            font-weight: 900;
            font-size: 16px;
        }

        .radar-chart-hs .radar-score-labels-hs text {
            fill: #686868;
            font-size: 10px;
            text-anchor: start;
        }

        .radar-zero-hs {
            fill: #9ca3af;
        }

        .radar-area-hs {
            fill: rgba(255, 126, 87, .58) !important;
            stroke: rgba(244, 98, 53, .75) !important;
            stroke-width: 2 !important;
        }

        .radar-dots-hs circle {
            fill: #d9d9d9;
            stroke: #d9d9d9;
        }

        .radar-focus-hs {
            fill: #c91e1e;
        }

        .long-reading-hs {
            background: #fff;
            padding: 12px 12px;
            font-size: 15px;
            line-height: 1.55;
            color: #111;
            text-align: justify;
        }

        .long-reading-hs p {
            margin: 0 0 15px 0;
        }

        .long-reading-hs b {
            font-weight: 900;
        }

        .age-reading-title-hs {
            margin: 28px 0 14px;
            border: 2px solid #c44912;
            background: #fff9e8;
            color: #9a350e;
            padding: 6px 12px;
            font-size: 15px;
            line-height: 1.25;
            font-weight: 900;
            display: table;
        }

        .analysis-tab-hs {
            width: min(1220px, 100%);
            margin: 0 auto 38px;
            display: grid;
            gap: 18px;
        }

        .analysis-tab-head-hs {
            background: #fffaf0;
            border: 1px solid #eadfce;
            padding: 20px 22px;
        }

        .analysis-tab-head-hs h2 {
            margin: 0 0 8px;
            font-size: 30px;
            color: #4a1640;
        }

        .analysis-tab-head-hs p {
            margin: 0;
            font-size: 18px;
            line-height: 1.55;
            color: #333;
        }

        .period-list-hs {
            display: grid;
            gap: 14px;
        }

        .period-card-hs {
            background: #fff;
            border: 1px solid #dfdfdf;
            border-left: 5px solid #8b155d;
            padding: 16px 18px;
            box-shadow: 0 1px 0 rgba(0, 0, 0, .03);
        }

        .period-card-head-hs {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: start;
        }

        .period-card-hs h3 {
            margin: 0 0 6px;
            font-size: 23px;
            color: #171a21;
        }

        .period-card-head-hs p {
            margin: 0;
            color: #7a3b12;
            font-size: 15px;
            font-weight: 800;
        }

        .period-score-hs {
            flex: 0 0 auto;
            min-width: 52px;
            height: 52px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff3e0;
            border: 2px solid #f39a13;
            color: #8a3a0e;
            font-size: 20px;
            font-weight: 900;
        }

        .period-metrics-hs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
        }

        .period-metrics-hs span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #eadfce;
            background: #fffaf0;
            padding: 7px 10px;
            font-size: 15px;
            color: #333;
        }

        .period-metrics-hs i {
            font-style: normal;
            min-width: 34px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            background: #4a1640;
            color: #fff;
            font-weight: 900;
        }

        .period-overview-hs {
            background: #fffdfa;
            border: 1px solid #eadfce;
            border-left: 5px solid #c44912;
            padding: 16px 18px;
        }

        .period-overview-hs h3 {
            margin: 0 0 8px;
            font-size: 22px;
            color: #4a1640;
        }

        .period-overview-hs p {
            margin: 0 0 14px;
            font-size: 17px;
            line-height: 1.55;
        }

        .period-overview-hs ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            max-height: 360px;
            overflow: auto;
        }

        .period-overview-hs li {
            display: grid;
            grid-template-columns: 110px minmax(0, 1fr) auto auto;
            gap: 8px;
            align-items: center;
            border: 1px solid #eee3d8;
            padding: 8px 10px;
            font-size: 14px;
            background: #fff;
        }

        .period-overview-hs li span {
            font-weight: 900;
            color: #171a21;
        }

        .period-overview-hs li small {
            color: #6b6b6b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .period-overview-hs li b {
            color: #8a3a0e;
            white-space: nowrap;
        }

        .period-summary-hs {
            margin: 14px 0 0;
            font-size: 18px;
            line-height: 1.55;
            color: #20242c;
        }

        .period-detail-hs {
            margin-top: 12px;
            border-top: 1px solid #ece1d4;
            padding-top: 10px;
        }

        .period-detail-hs summary {
            cursor: pointer;
            user-select: none;
            color: #be430d;
            font-size: 17px;
            font-weight: 900;
        }

        .period-detail-hs div {
            display: grid;
            gap: 12px;
            margin-top: 12px;
        }

        .period-detail-hs p {
            margin: 0;
            font-size: 18px;
            line-height: 1.65;
            color: #151515;
        }

        .reading-blocks-hs {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .reading-blocks-hs section {
            background: #fffaf0;
            border: 1px solid #eadfce;
            border-left: 4px solid #d09b21;
            padding: 12px 14px;
        }

        .reading-blocks-hs h4 {
            margin: 0 0 7px;
            font-size: 17px;
            color: #4a1640;
        }

        .reading-blocks-hs p {
            font-size: 16px;
            line-height: 1.6;
        }

        .daily-card-hs {
            border-left-color: #c44912;
        }

        .daily-grid-hs {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .daily-block-hs {
            background: #fffdfa;
            border: 1px solid #eadfce;
            border-top: 4px solid #f39a13;
            padding: 13px 14px;
        }

        .daily-block-hs h4 {
            margin: 0 0 8px;
            font-size: 18px;
            color: #4a1640;
        }

        .daily-block-hs p {
            margin: 0;
            font-size: 17px;
            line-height: 1.6;
            color: #151515;
        }

        .period-sources-hs {
            background: #fffaf0;
            border: 1px solid #eadfce;
            padding: 12px 14px;
            font-size: 15px;
            line-height: 1.55;
        }

        .period-sources-hs ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .period-sources-hs li {
            margin: 6px 0;
        }

        .period-sources-hs span {
            color: #6a6a6a;
        }

        .premium-note-hs {
            border-left: 4px solid #be430d;
            padding-left: 18px;
            color: #2a2a2a;
        }

        .summary-grid-hs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .summary-panel-hs, .palace-detail-hs, .table-panel-hs {
            background: #fff;
            border: 1px solid #d8d8d8;
            border-radius: 6px;
            padding: 16px;
        }

        .summary-panel-hs h3, .table-panel-hs h3 {
            margin: 0 0 12px;
            font-size: 15px;
            text-transform: capitalize;
        }

        .summary-panel-hs dl {
            margin: 0;
            display: grid;
            gap: 8px;
            font-size: 14px;
        }

        .summary-panel-hs dl div {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #eee;
            padding-bottom: 7px;
        }

        .summary-panel-hs dt {
            color: #000;
        }

        .summary-panel-hs dd {
            margin: 0;
            font-weight: 800;
            text-align: right;
        }

        .palace-detail-hs {
            border-left: 5px solid #8b155d;
        }

        .detail-head-hs {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: start;
            margin-bottom: 10px;
        }

        .detail-kicker-hs {
            color: #8b155d;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
        }

        .detail-head-hs h3 {
            margin: 2px 0 0;
            font-size: 25px;
        }

        .detail-branch-hs {
            font-weight: 800;
            color: #d09b21;
        }

        .detail-tags-hs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .detail-tags-hs span {
            background: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 999px;
            padding: 5px 10px;
            font-weight: 700;
        }

        .palace-detail-hs p {
            margin: 8px 0;
            line-height: 1.55;
        }

        .palace-focus-hs {
            font-size: 18px;
            background: #fff8ef;
            border-left: 4px solid #d09b21;
            padding: 12px 14px;
        }

        .star-reading-section-hs {
            margin-top: 18px;
        }

        .star-reading-section-hs h4 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #4a1640;
        }

        .star-reading-grid-hs {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .star-reading-card-hs {
            border: 1px solid #e3e0db;
            background: #fffdfa;
            border-radius: 6px;
            padding: 13px 14px;
        }

        .star-reading-title-hs {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 8px;
            font-size: 19px;
            font-weight: 900;
        }

        .star-reading-title-hs small {
            color: #777;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .star-reading-card-hs p {
            margin: 7px 0;
            font-size: 16px;
            line-height: 1.55;
        }

        .star-reading-card-hs h5 {
            margin: 0 0 6px;
            font-size: 15px;
            color: #8b155d;
        }

        .star-more-hs {
            margin-top: 10px;
            border-top: 1px solid #eee3d8;
            padding-top: 9px;
        }

        .star-more-hs summary {
            cursor: pointer;
            color: #be430d;
            font-weight: 900;
            user-select: none;
        }

        .star-more-hs summary:hover {
            text-decoration: underline;
        }

        .star-more-hs section {
            margin-top: 12px;
        }

        .star-meta-hs {
            width: 100%;
            min-width: 0;
            margin: 10px 0 12px;
            border-collapse: collapse;
            font-size: 14px;
            background: #fff;
        }

        .star-meta-hs th, .star-meta-hs td {
            border: 1px solid #eadfd4;
            padding: 7px 9px;
            vertical-align: top;
            text-align: left;
        }

        .star-meta-hs th {
            width: 118px;
            background: #fff7ea;
            color: #8a3a0e;
        }

        .empty-reading-hs {
            color: #555;
            background: #fafafa;
            border: 1px dashed #d8d8d8;
            border-radius: 6px;
            padding: 12px;
        }

        .table-wrap-hs {
            overflow-x: auto;
        }

        .page-horoscope table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
            font-size: 14px;
        }

        .page-horoscope table th, .page-horoscope table td {
            border: 1px solid #e2e2e2;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        .page-horoscope table th {
            background: #f5f1f4;
            color: #4a1640;
        }

        .page-horoscope table tr[data-palace-row] {
            cursor: pointer;
        }

        .page-horoscope table tr[data-palace-row]:hover {
            background: #fff6fa;
        }

        @media (max-width: 860px) {
            .summary-grid-hs {
                grid-template-columns: 1fr;
            }

            .reading-blocks-hs {
                grid-template-columns: 1fr;
            }

            .daily-grid-hs {
                grid-template-columns: 1fr;
            }

            .period-overview-hs ul {
                grid-template-columns: 1fr;
            }

            .period-overview-hs li {
                grid-template-columns: 1fr;
            }

            .star-reading-grid-hs {
                grid-template-columns: 1fr;
            }

            .grid-hs {
                /*min-width: 1040px;*/
            }

            .legend-hs {
                min-width: 800px;
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
@endsection
@section('scripts')
    <script>
        (function () {
            const form = document.getElementById('horoscopeForm');

            if (!form) {
                return;
            }

            const nameEl = document.getElementById('horoscopeFormName');

            const dateEl = document.getElementById('horoscopeFormDate');
            const datePickerEl = dateEl ? dateEl.closest('.horoscope-form__date-picker') : null;
            const dateDropdownEl = document.getElementById('horoscopeFormDateDropdown');

            const daySelectEl = document.getElementById('horoscopeFormDaySelect');
            const monthSelectEl = document.getElementById('horoscopeFormMonthSelect');
            const yearSelectEl = document.getElementById('horoscopeFormYearSelect');

            const calendarTypeEl = document.getElementById('horoscopeFormCalendarType');
            const calendarLabelEl = document.getElementById('horoscopeCalendarLabel');

            const timeOfBirthEl = document.getElementById('horoscopeFormTimeOfBirth');
            const genderEl = document.getElementById('horoscopeFormGender');
            const viewYearEl = document.getElementById('horoscopeFormViewYear');

            const dayHiddenEl = document.getElementById('horoscopeFormDay');
            const monthHiddenEl = document.getElementById('horoscopeFormMonth');
            const yearHiddenEl = document.getElementById('horoscopeFormYear');

            const submitBtn = form.querySelector('.horoscope-form__button');

            const minYear = 1900;
            const maxYear = new Date().getFullYear();

            const defaultDate = {
                day: Number(dayHiddenEl ? dayHiddenEl.value : 1) || 1,
                month: Number(monthHiddenEl ? monthHiddenEl.value : 1) || 1,
                year: Number(yearHiddenEl ? yearHiddenEl.value : 1995) || 1995
            };

            function normalizeText(value) {
                return String(value || '').replace(/\s+/g, ' ').trim();
            }

            function padNumber(value) {
                return String(value).padStart(2, '0');
            }

            function createOption(value, text, selected) {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = text;

                if (selected) {
                    option.selected = true;
                }

                return option;
            }

            function getDaysInMonth(month, year) {
                return new Date(Number(year), Number(month), 0).getDate();
            }

            function parseDate(value) {
                const match = normalizeText(value).match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);

                if (!match) {
                    return null;
                }

                return {
                    day: Number(match[1]),
                    month: Number(match[2]),
                    year: Number(match[3])
                };
            }

            function formatDate(date) {
                return padNumber(date.day) + '/' + padNumber(date.month) + '/' + date.year;
            }

            function syncHiddenDateFields(date) {
                if (!dayHiddenEl || !monthHiddenEl || !yearHiddenEl) {
                    return;
                }

                dayHiddenEl.value = date.day;
                monthHiddenEl.value = date.month;
                yearHiddenEl.value = date.year;
            }

            function syncInputDate(date) {
                if (!dateEl) {
                    return;
                }

                dateEl.value = formatDate(date);
                syncHiddenDateFields(date);
            }

            function renderYearSelect(selectedYear) {
                if (!yearSelectEl) {
                    return;
                }

                yearSelectEl.innerHTML = '';

                for (let year = maxYear; year >= minYear; year--) {
                    yearSelectEl.appendChild(
                        createOption(year, year, Number(selectedYear) === year)
                    );
                }
            }

            function renderMonthSelect(selectedMonth) {
                if (!monthSelectEl) {
                    return;
                }

                monthSelectEl.innerHTML = '';

                for (let month = 1; month <= 12; month++) {
                    monthSelectEl.appendChild(
                        createOption(month, padNumber(month), Number(selectedMonth) === month)
                    );
                }
            }

            function renderDaySelect(selectedDay) {
                if (!daySelectEl || !monthSelectEl || !yearSelectEl) {
                    return;
                }

                const month = Number(monthSelectEl.value) || defaultDate.month;
                const year = Number(yearSelectEl.value) || defaultDate.year;
                const maxDay = getDaysInMonth(month, year);

                let day = Number(selectedDay || daySelectEl.value || defaultDate.day);

                if (day > maxDay) {
                    day = maxDay;
                }

                if (day < 1) {
                    day = 1;
                }

                daySelectEl.innerHTML = '';

                for (let i = 1; i <= maxDay; i++) {
                    daySelectEl.appendChild(
                        createOption(i, padNumber(i), i === day)
                    );
                }
            }

            function getSelectedPickerDate() {
                return {
                    day: Number(daySelectEl.value),
                    month: Number(monthSelectEl.value),
                    year: Number(yearSelectEl.value)
                };
            }

            function updateDateFromPicker() {
                renderDaySelect(daySelectEl.value);

                const date = getSelectedPickerDate();

                syncInputDate(date);
                clearError('birthDate');
                clearError('viewYear');
            }

            function openDatePicker() {
                if (!datePickerEl) {
                    return;
                }

                datePickerEl.classList.add('is-open');
            }

            function closeDatePicker() {
                if (!datePickerEl) {
                    return;
                }

                datePickerEl.classList.remove('is-open');
            }

            function initDatePicker() {
                const parsed = parseDate(dateEl.value) || defaultDate;

                renderMonthSelect(parsed.month);
                renderYearSelect(parsed.year);
                renderDaySelect(parsed.day);
                syncInputDate(parsed);
            }

            function updateCalendarLabel() {
                if (!calendarLabelEl || !calendarTypeEl) {
                    return;
                }

                calendarLabelEl.textContent = calendarTypeEl.value === 'lunar' ? '(Âm lịch)' : '(Dương lịch)';
            }

            function getFieldByName(name) {
                if (name === 'birthDate') {
                    return datePickerEl;
                }

                const el = form.querySelector('[name="' + name + '"]');

                return el ? el.closest('.horoscope-form__field') : null;
            }

            function setError(name, message) {
                const errorEl = form.querySelector('[data-error-for="' + name + '"]');
                const fieldEl = getFieldByName(name);

                if (errorEl) {
                    errorEl.textContent = message;
                    errorEl.classList.add('is-show');
                }

                if (fieldEl) {
                    fieldEl.classList.add('is-error');
                }
            }

            function clearError(name) {
                const errorEl = form.querySelector('[data-error-for="' + name + '"]');
                const fieldEl = getFieldByName(name);

                if (errorEl) {
                    errorEl.textContent = '';
                    errorEl.classList.remove('is-show');
                }

                if (fieldEl) {
                    fieldEl.classList.remove('is-error');
                }
            }

            function clearAllErrors() {
                form.querySelectorAll('.horoscope-form__error').forEach(function (el) {
                    el.textContent = '';
                    el.classList.remove('is-show');
                });

                form.querySelectorAll('.is-error').forEach(function (el) {
                    el.classList.remove('is-error');
                });
            }

            function validateBirthDate() {
                const parsed = parseDate(dateEl.value);

                if (!parsed) {
                    setError('birthDate', 'Vui lòng chọn ngày sinh.');
                    return null;
                }

                if (parsed.year < minYear || parsed.year > maxYear) {
                    setError('birthDate', 'Năm sinh phải từ ' + minYear + ' đến ' + maxYear + '.');
                    return null;
                }

                if (parsed.month < 1 || parsed.month > 12) {
                    setError('birthDate', 'Tháng sinh không hợp lệ.');
                    return null;
                }

                const maxDay = getDaysInMonth(parsed.month, parsed.year);

                if (parsed.day < 1 || parsed.day > maxDay) {
                    setError('birthDate', 'Ngày sinh không hợp lệ với tháng/năm đã chọn.');
                    return null;
                }

                syncHiddenDateFields(parsed);

                return parsed;
            }

            function validateForm() {
                clearAllErrors();

                let isValid = true;

                const name = normalizeText(nameEl.value);
                const calendarType = calendarTypeEl.value;
                const timeOfBirth = timeOfBirthEl.value;
                const gender = genderEl.value;
                const viewYear = Number(viewYearEl.value);
                const birthDate = validateBirthDate();

                if (!birthDate) {
                    isValid = false;
                }

                if (!name) {
                    setError('name', 'Vui lòng nhập họ và tên.');
                    isValid = false;
                } else if (name.length < 2) {
                    setError('name', 'Họ và tên quá ngắn.');
                    isValid = false;
                } else {
                    nameEl.value = name;
                }

                if (!['solar', 'lunar'].includes(calendarType)) {
                    setError('calendarType', 'Vui lòng chọn loại lịch.');
                    isValid = false;
                }

                if (!timeOfBirth) {
                    setError('timeOfBirth', 'Vui lòng chọn giờ sinh.');
                    isValid = false;
                }

                if (!['male', 'female'].includes(gender)) {
                    setError('gender', 'Vui lòng chọn giới tính.');
                    isValid = false;
                }

                if (birthDate && viewYear && viewYear < birthDate.year) {
                    setError('viewYear', 'Năm xem không được nhỏ hơn năm sinh.');
                    isValid = false;
                }

                return isValid;
            }

            function lockButton() {
                if (!submitBtn) {
                    return;
                }

                submitBtn.dataset.originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Đang xử lý...';
            }

            function unlockButton() {
                if (!submitBtn) {
                    return;
                }

                submitBtn.disabled = false;

                if (submitBtn.dataset.originalText) {
                    submitBtn.innerHTML = submitBtn.dataset.originalText;
                }
            }

            initDatePicker();
            updateCalendarLabel();

            dateEl.addEventListener('focus', openDatePicker);
            dateEl.addEventListener('click', openDatePicker);

            if (dateDropdownEl) {
                dateDropdownEl.addEventListener('mousedown', function (event) {
                    event.stopPropagation();
                });
            }

            document.addEventListener('mousedown', function (event) {
                if (!datePickerEl) {
                    return;
                }

                if (!datePickerEl.contains(event.target)) {
                    closeDatePicker();
                }
            });

            daySelectEl.addEventListener('change', updateDateFromPicker);
            monthSelectEl.addEventListener('change', updateDateFromPicker);
            yearSelectEl.addEventListener('change', updateDateFromPicker);

            nameEl.addEventListener('input', function () {
                clearError('name');
            });

            calendarTypeEl.addEventListener('change', function () {
                clearError('calendarType');
                updateCalendarLabel();
            });

            timeOfBirthEl.addEventListener('change', function () {
                clearError('timeOfBirth');
            });

            genderEl.addEventListener('change', function () {
                clearError('gender');
            });

            viewYearEl.addEventListener('change', function () {
                clearError('viewYear');
            });

            form.addEventListener('submit', function (event) {
                if (!validateForm()) {
                    event.preventDefault();
                    unlockButton();

                    const firstError = form.querySelector('.is-error input, .is-error select');

                    if (firstError) {
                        firstError.focus({
                            preventScroll: true
                        });

                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return;
                }

                closeDatePicker();
                lockButton();
            });

            window.addEventListener('pageshow', function () {
                unlockButton();
            });
        })();
    </script>
@endsection
