@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.age-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Xem tuổi làm ăn hợp khắc theo năm sinh</p>
            <h1>{{ !empty($data['hashTag']) ? $data['hashTag']['h1'] : $data['category']['h1'] }}</h1>
            <p>{!! !empty($data['hashTag']) ? $data['hashTag']['description'] : $data['category']['description'] !!}</p>
        </div>

        @php
            $birthYear = request('birthYear', '2000');
            $weddingYear = request('weddingYear', date('Y'));
            $gender = request('gender', 'nam');

            $row = $data['detail'];
        @endphp
        <form class="fortune-date-lookup-row" action="" method="get" aria-label="Chọn ngày cần xem">
            <div class="fortune-picker-container">
                <label class="fortune-label">Năm</label>
                <div class="fortune-input-wrapper">
                    <select aria-label="Năm" name="lunarYear" id="lunarYear" class="fortune-select-year-view">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option
                                value="{{ $i }}" {{ request('lunarYear', 2000) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <button type="submit" class="fortune-btn-submit-inline btn-sm-form">Xem kết quả</button>
        </form>
        @if(!empty($row['reading']))
            <div class="wrap-content-love pd-0">
                @php
                    $reading = $row['reading'] ?? [];
                @endphp
                <div class="love-result-box pd-0 mb-0">
                    <h2>Danh sách tuổi hợp với {{ $row['owner']['canChi'] }} {{ $row['owner']['lunarYear'] }}</h2>
                    @foreach($row['reading']['sections'] as $value)
                        @if(in_array($value['key'], ['overview']))
                            <p class="love-summary mb-6">{{ $value['content'] }}</p>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="check-age-card check-age-result">
                <div class="check-age-result-head">
                    <div>
                        <span class="check-age-eyebrow">
                            Danh sách tuổi hợp
                        </span>
                        <h2>
                            Tuổi {{ $row['owner']['canChi'] }} {{ $row['owner']['lunarYear'] }} hợp với tuổi nào?
                        </h2>
                        <p>
                            Xem danh sách các tuổi hợp với tuổi {{ $row['owner']['canChi'] }} {{ $row['owner']['lunarYear'] }} theo Can Chi,
                            Ngũ hành, Nạp âm và cung mệnh, giúp tham khảo mức độ hòa hợp
                            trong tình cảm, hôn nhân, công việc và các mối quan hệ.
                        </p>
                    </div>
                </div>
                <div class="check-age-ranking mb-6">
                    <div class="check-age-ranking-head">
                        <span>Hạng</span>
                        <span>Tuổi</span>
                        <span>Mệnh</span>
                        <span>Điểm</span>
                        <span>Đánh giá</span>
                    </div>
                    @if(!empty($row['summary']['bestAges']))
                        @php
                            $arr = array_merge($row['summary']['bestAges'], $row['summary']['goodAges'])
                        @endphp
                        @foreach($arr as $k=>$value)
                            <div class="check-age-ranking-row @if($k <= 2) check-age-top @endif">
                                <span class="check-age-rank">{{ $k + 1 }}</span>
                                <div class="check-age-person">
                                    <span>{{ implode(', ', $value['sampleYears']) }}</span>
                                </div>
                                <div class="check-age-element">
                                    <strong>{{ $value['napAm']['name'] }}</strong>
                                    <span>Mệnh {{ $value['napAm']['elementLabel'] }}</span>
                                </div>
                                <div class="check-age-score">
                                    <strong>{{ $value['score'] }}</strong>
                                    <span>/10</span>
                                </div>
                                <div class="check-age-status-wrap" tabindex="0">
                                        <span class="check-age-status check-age-status-excellent text-center">
                                            {{ $value['level'] }}
                                            <span class="check-age-status-help">?</span>
                                        </span>
                                    <div class="check-age-tooltip">
                                        <strong>Vì sao
                                            tuổi {{ $value['canChi'] }} {{ implode(', ', $value['sampleYears']) }}
                                            phù hợp?</strong>
                                        <p>{{ $value['advice'] }}</p>
                                        <div class="check-age-tooltip-score">
                                            Điểm tổng hợp:
                                            <b>{{ $value['score'] }}/10</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="check-age-result-head">
                    <div>
                        <span class="check-age-eyebrow">
                            Danh sách tuổi không hợp
                        </span>
                        <h2>
                            Tuổi {{ $row['owner']['canChi'] }} {{ $row['owner']['lunarYear'] }} không hợp với tuổi nào?
                        </h2>
                        <p>
                            Danh sách các tuổi không hợp với {{ $row['owner']['canChi'] }} {{ $row['owner']['lunarYear'] }} được tham khảo theo
                            Can Chi, Ngũ hành, Nạp âm và các mối quan hệ hợp – xung. Khi kết hợp
                            với những tuổi này, nên xem thêm từng yếu tố cụ thể để có đánh giá phù hợp.
                        </p>
                    </div>
                </div>
                <div class="check-age-ranking">
                    <div class="check-age-ranking-head">
                        <span>Hạng</span>
                        <span>Tuổi</span>
                        <span>Mệnh</span>
                        <span>Điểm</span>
                        <span>Đánh giá</span>
                    </div>
                    @if(!empty($row['summary']['neutralAges']))
                        @php
                            $arr = array_merge($row['summary']['neutralAges'], $row['summary']['incompatibleAges'], $row['summary']['worstAges'])
                        @endphp
                        @foreach($arr as $k=>$value)
                            <div class="check-age-ranking-row @if($k <= 2) check-age-top @endif">
                                <span class="check-age-rank">{{ $k + 1 }}</span>
                                <div class="check-age-person">
                                    <span>{{ implode(', ', $value['sampleYears']) }}</span>
                                </div>
                                <div class="check-age-element">
                                    <strong>{{ $value['napAm']['name'] }}</strong>
                                    <span>Mệnh {{ $value['napAm']['elementLabel'] }}</span>
                                </div>
                                <div class="check-age-score">
                                    <strong>{{ $value['score'] }}</strong>
                                    <span>/10</span>
                                </div>
                                <div class="check-age-status-wrap" tabindex="0">
                                        <span class="check-age-status check-age-status-excellent text-center">
                                            {{ $value['level'] }}
                                            <span class="check-age-status-help">?</span>
                                        </span>
                                    <div class="check-age-tooltip">
                                        <strong>Vì sao
                                            tuổi {{ $value['canChi'] }} {{ implode(', ', $value['sampleYears']) }}
                                            phù hợp?</strong>
                                        <p>{{ $value['advice'] }}</p>
                                        <div class="check-age-tooltip-score">
                                            Điểm tổng hợp:
                                            <b>{{ $value['score'] }}/10</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
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
                        Xem tuổi làm ăn hợp – khắc là hình thức tham khảo theo quan niệm dân gian,
                        dựa trên Can Chi, Ngũ hành, Nạp âm, cung mệnh và các mối quan hệ hợp – xung
                        giữa hai tuổi. Kết quả chỉ mang tính tham khảo; sự thành công trong hợp tác
                        thực tế còn phụ thuộc vào năng lực, uy tín, mục tiêu chung, cách làm việc
                        và điều kiện kinh doanh của mỗi bên.
                    </p>
                </div>
            </section>
        @endif
    </div>
@endsection

@section('scripts')

@endsection
