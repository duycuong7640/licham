@extends('pages::layouts.app')

@section('content')
    @php
        $type = $data['category']['type'];
        $week = $data['wData'];
    @endphp
    @push('schema')
        @include('pages::elements.extend.page-schema', [
            'schemaType' => 'CollectionPage',
            'sectionName' => 'Xem ngày tốt trong tuần',
            'sectionUrl' => url('/xem-ngay-tot-trong-tuan'),
            'currentName' => 'Tuần ' . $week['week'] . ' năm ' . $week['year'],
            'aboutName' => 'Ngày tốt xấu tuần ' . $week['week'] . ' năm ' . $week['year'],
        ])
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Tra cứu ngày trong tuần tốt xấu</p>
            <h1>Tuần {{ $week['week'] }} năm {{ $week['year'] }} có ngày nào tốt?</h1>
            <p>
                Tuần này kéo dài từ
                {{ \Carbon\Carbon::createFromFormat('d-m-Y', $week['dates'][0])->format('d/m') }}
                đến
                {{ \Carbon\Carbon::createFromFormat('d-m-Y', $week['dates'][6])->format('d/m/Y') }}.
                Trong 7 ngày có {{ $data['goodDayCount'] }} ngày Hoàng đạo
                và {{ $data['badDayCount'] }} ngày Hắc đạo.
                Mỗi ngày bên dưới đều có giờ tốt, ngày âm lịch
                và những điều cần lưu ý.
            </p>
        </div>

        @include('pages::copes.elements.tab')

        <form class="date-lookup date-lookup-custom" action="" method="get" aria-label="Chọn tuần cần xem">
            <label>
                <span>Tuần</span>
                <select aria-label="Tuần" id="weekSelect" data-current-week="{{ $week['week'] }}">
                    @foreach($week['weeks'] as $w)
                        <option value="{{ $w['week'] }}" @if($week['week'] == $w['week']) selected @endif>{{ $w['week'] }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span>Năm</span>
                <select aria-label="Năm" id="yearSelect" data-current-year="{{ $week['year'] }}">
                    @for($i = 1900; $i <= 2050; $i ++)
                        <option value="{{ $i }}" @if($week['year'] == $i) selected @endif>{{ $i }}</option>
                    @endfor
                </select>
            </label>
            <button type="button" id="btnViewDate">Xem tuần</button>
        </form>

        <div class="code-week-container">
            <div class="code-week-header">
                <a href="{{ route('page.cope.show.week', ['week' => $week['prev']['week'].'-nam-'.$week['prev']['year']]) }}"
                   class="code-week-btn code-week-prev"
                   title="Tuần trước (Tuần {{ $week['prev']['week'] }} năm {{ $week['prev']['year'] }})">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                    </svg>
                    Tuần trước
                </a>
                <h2 class="code-week-title text-center">
                    Tuần {{ $week['week'] }} / {{ $week['year'] }}
                    <div style="font-size: 12px; font-weight: normal; color: #666;">
                        Từ ({{ $week['dates'][0] }} - {{ $week['dates'][6] }})
                    </div>
                </h2>
                <a href="{{ route('page.cope.show.week', ['week' => $week['next']['week'].'-nam-'.$week['next']['year']]) }}"
                   class="code-week-btn code-week-prev"
                   title="Tuần sau (Tuần {{ $week['next']['week'] }} năm {{ $week['next']['year'] }})">
                    Tuần sau
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </div>

            @if(count($data['lists']))
                <div class="code-week-list">
                    @foreach($data['lists'] as $k=>$values)
                        @php
                            $formatDay = strtotime($values['day']);
                            $day = date('d', $formatDay);
                            $month = date('m', $formatDay);
                            $year = date('Y', $formatDay);
                            $thu = \App\Helpers\Helpers::formatVietnameseDateNumber($values['day']);

                            $lDay = strtotime($values['lunarDay']);
                            $lunarDay = date('d', $lDay);
                            $lunarMonth = date('m', $lDay);
                            $lunarYear = date('Y', $lDay);
                        @endphp
                        <a href="{{ route('page.cope.show.day', ['day' => $day.'-'.$month.'-'.$year]) }}"
                           title="Xem ngày tốt xấu {{ $day }}/{{ $month }}/{{ $year }}">
                            <div class="code-week-item @if($values['isDay']) code-week-good @else code-week-bad @endif">
                                <div class="code-week-left">
                                    <div class="code-week-day-name">{{ $thu }}</div>
                                    <div class="code-week-day-num">{{ $day }}</div>
                                    <div class="code-week-month">Tháng {{ $month }}</div>
                                    <div class="code-week-badge">@if($values['isDay']) Tốt @else Xấu @endif</div>
                                </div>
                                <div class="code-week-right">
                                    <ul class="code-week-info-list">
                                        <li>
                                            <span class="code-week-label">{{ $thu }}:</span>
                                            <strong class="code-week-highlight">Ngày {{ $day }}/{{ $month }}/{{ $year }}
                                                - {{ $lunarDay }}/{{ $lunarMonth }}/{{ $lunarYear }} Âm lịch.</strong>
                                        </li>
                                        <li>
                                            <span class="code-week-label">Bát tự:</span>
                                            Ngày: <strong>{{ $values['strDay'] }}</strong>
                                            Tháng:<strong>{{ $values['strMonth'] }}</strong>
                                            Năm: <strong>{{ $values['strYear'] }}</strong>
                                        </li>
                                        @if(!empty($values['options']['TABOO_DAY']) && !$values['isDay'])
                                            <li>
                                                <span class="code-week-label">Ngày kỵ:</span>
                                                <span class="code-week-alert-bad">
                                                    @foreach($values['options']['TABOO_DAY'] as $value)
                                                        {{ strip_tags($value['value']) }}
                                                    @endforeach
                                                </span>
                                            </li>
                                        @endif
                                        <li>
                                            <span class="code-week-label">Là ngày:</span>
                                            <strong>
                                                @if($values['isDay']) <span
                                                    style="color: #9f2734;">Hoàng đạo</span> @else Hắc đạo @endif
                                            </strong>
                                        </li>
                                        @if(!empty($values['options']['DAY_OFFICER']))
                                            <li>
                                                <span class="code-week-label">Trực:</span>
                                                <strong>
                                                    @foreach($values['options']['DAY_OFFICER'] as $value)
                                                        {{ strip_tags($value['value']) }}
                                                    @endforeach
                                                </strong>
                                            </li>
                                        @endif
                                        @if(!empty($values['options']['AUSPICIOUS_HOUR']))
                                            <li>
                                                <span class="code-week-label">Giờ Hoàng Đạo:</span>
                                                <strong>
                                                    @foreach($values['options']['AUSPICIOUS_HOUR'] as $value)
                                                        @php $time = \App\Helpers\Helpers::matchHour($value['value']); @endphp
                                                        {{ !empty($time['title']) ? $time['title'] : '' }}
                                                        ({{ !empty($time['hour']) ? $time['hour'] : '' }}),
                                                    @endforeach
                                                </strong>
                                            </li>
                                        @endif
                                        <li>
                                            <span class="code-week-label">Tiết khí:</span>
                                            <strong>
                                                {{ !empty($values['options']['LUNAR']) ? \App\Helpers\Helpers::getCopeValueByOption($values['options']['LUNAR'], 'SOLAR_SEASONS') : '' }}
                                            </strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        const btnViewDate = document.getElementById('btnViewDate');

        document.addEventListener('DOMContentLoaded', function () {
            const links = document.querySelectorAll('.code-week-header a.code-week-btn');

            links.forEach(link => {
                link.addEventListener('click', function (e) {
                    if (link.classList.contains('is-loading')) {
                        e.preventDefault();
                        return;
                    }

                    link.classList.add('is-loading');
                    link.setAttribute('aria-disabled', 'true');
                    link.style.pointerEvents = 'none';

                    const oldHtml = link.innerHTML;

                    link.innerHTML = `
                <span class="code-week-spinner"></span>
                Đang tải...
            `;
                    window.addEventListener('pageshow', function () {
                        link.classList.remove('is-loading');
                        link.removeAttribute('aria-disabled');
                        link.style.pointerEvents = '';
                        link.innerHTML = oldHtml;
                    }, {once: true});
                });
            });
        });

        btnViewDate.addEventListener('click', function () {

            if (this.dataset.loading === '1') {
                return;
            }

            const week = weekSelect.value;
            const year = yearSelect.value;

            if (!week || !year) {
                return;
            }

            this.dataset.loading = '1';
            this.disabled = true;

            const oldHtml = this.innerHTML;

            this.innerHTML = `
        <span class="spinner"></span>
        Đang tải...
    `;

            const urlTemplate = "{{ route('page.cope.show.week', ['week' => '__WEEK__']) }}";

            window.location.href = urlTemplate.replace(
                '__WEEK__',
                `${week}-nam-${year}`
            );

            setTimeout(() => {
                this.dataset.loading = '0';
                this.disabled = false;
                this.innerHTML = oldHtml;
            }, 5000);
        });
    </script>
@endsection
