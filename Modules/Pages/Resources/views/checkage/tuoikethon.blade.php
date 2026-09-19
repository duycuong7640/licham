@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.age-schema')
    @endpush
    <div class="day-detail-page">
        <div class="day-page-heading">
            <p class="eyebrow">Tra cứu tuổi cưới và năm kết hôn phù hợp</p>
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
                <label class="fortune-label">Năm sinh</label>
                <div class="fortune-input-wrapper">
                    <select aria-label="Năm" name="birthYear" id="birthYear" class="fortune-select-year-view">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option
                                value="{{ $i }}" {{ request('birthYear', 2000) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="fortune-picker-container">
                <label class="fortune-label">Năm cưới</label>
                <div class="fortune-input-wrapper">
                    <select aria-label="Năm" name="weddingYear" id="weddingYear" class="fortune-select-year-view">
                        @for($i = 1900; $i <= 2050; $i++)
                            <option
                                value="{{ $i }}" {{ request('weddingYear', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="fortune-picker-container">
                <label class="fortune-label">Giới tính</label>
                <div class="fortune-input-wrapper">
                    <select aria-label="Năm" name="gender" id="gender" class="fortune-select-year-view">
                        <option value="nam" {{ request('gender', 'nam') == 'nam' ? 'selected' : '' }}>
                            Nam
                        </option>
                        <option value="nu" {{ request('gender', 'unknown') == 'nu' ? 'selected' : '' }}>
                            Nữ
                        </option>
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
                    <h2>Tuổi {{ $row['person']['canChi'] }} {{ $row['person']['birthYear'] }} nên kết hôn năm {{ $row['input']['weddingYear'] }} không?</h2>
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

@endsection
