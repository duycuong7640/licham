@extends('pages::layouts.news')

@section('content')
    <div class="day-detail-page">
        <div class="day-page-heading">
            @include('pages::elements.breadcrumb')
            <p class="eyebrow">Tử vi 12 cung hoàng đạo hôm nay</p>
            <h1>Tử vi 12 cung hoàng đạo ngày {{ $data['detail']['targetDate'] }}</h1>
            <p>Tử vi 12 cung hoàng đạo ngày {{ $data['detail']['targetDate'] }} cập nhật dự báo về sự nghiệp, tình cảm
                và tài chính của Bạch Dương, Kim Ngưu, Song Tử, Cự Giải, Sư Tử, Xử Nữ, Thiên Bình, Hổ Cáp, Nhân Mã, Ma
                Kết, Bảo Bình và Song Ngư. Khám phá vận trình ngày mới, cơ hội nổi bật và những điều mỗi cung nên lưu
                ý.</p>
        </div>
    </div>
    @if(!empty($data['detail']))
        @php
            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $data['detail']['targetDate']);
            $preUrl = route('page.cate.index', ['slug' => 'tu-vi-12-cung-hoang-dao-hang-ngay-' . $date->copy()->subDay()->format('d-m-Y')]);
            $currentUrl = route('page.cate.index', ['slug' => 'tu-vi-12-cung-hoang-dao-hang-ngay-' . $date->format('d-m-Y')]);
            $nextUrl = route('page.cate.index', ['slug' => 'tu-vi-12-cung-hoang-dao-hang-ngay-' . $date->copy()->addDay()->format('d-m-Y')]);

            if (!\App\Helpers\Helpers::checkDateWithinDays($date->copy()->addDay()->format('d-m-Y'), 15)) {
                $nextUrl = '#';
            }

            $urls = [
                'pre' => $preUrl,
                'current' => $currentUrl,
                'next' => $nextUrl,
            ];
        @endphp
        <div class="tuvi-chd-ed">
            <div class="tuvi-chd-ed-tabs">
                <a href="{{ $urls['pre'] }}" class="tuvi-chd-ed-tab">
                    <span>← Ngày trước</span>
                </a>

                <a href="{{ $urls['current'] }}" class="tuvi-chd-ed-tab tuvi-chd-ed-tab-active">
                    <span>{{ $data['detail']['targetDate'] }}</span>
                </a>

                <a href="{{ $urls['next'] }}" class="tuvi-chd-ed-tab">
                    <span>Ngày sau →</span>
                </a>
            </div>

            <div class="tuvi-chd-ed-content">
                <div class="tuvi-chd-ed-heading">
                    <h2>Tử vi hàng ngày 12 cung hoàng đạo</h2>
                    <p>
                        Khám phá tử vi hôm nay, hôm qua và ngày mai của 12 cung hoàng đạo
                        về tình yêu, công việc, tài chính và vận may.
                    </p>
                </div>

                <div class="tuvi-chd-ed-label">
                    Xem tử vi theo cung
                </div>

                <div class="tuvi-chd-ed-grid">
                    @foreach(dataKey::CUNG_HOANG_DAO as $k=>$row)
                        <a href="#hl-{{ $k }}" class="tuvi-chd-ed-item">
                            <span class="tuvi-chd-ed-icon">{{ $row['icon'] }}</span>
                            <span class="tuvi-chd-ed-name">{{ $row['name'] }}</span>
                            <span class="tuvi-chd-ed-arrow">›</span>
                        </a>
                    @endforeach
                </div>

                <div class="lists-chd">
                    @if(!empty($data['detail']['article']))
                        <p style="padding: 0 0 10px 0; margin-top: 0;">{{ $data['detail']['article']['lead'] }}</p>
                    @endif
                    @if(!empty($data['detail']['signs']))
                        @foreach($data['detail']['signs'] as $k=>$row)
                            @php
                                $slug = \App\Helpers\Helpers::renderSlug($row['sign']['name']);
                            @endphp
                            <article class="tuvi-chd-ed-article" id="hl-{{ $slug }}">
                                <div class="tuvi-chd-ed-article-head">
                                    <div class="tuvi-chd-ed-zodiac-icon">{{ !empty(dataKey::CUNG_HOANG_DAO[$slug]['icon']) ? dataKey::CUNG_HOANG_DAO[$slug]['icon'] : '' }}</div>
                                    <div class="tuvi-chd-ed-zodiac-title">
                                        <h2>{{ $row['sign']['name'] }} <span>({{ $row['sign']['dateRange'] }})</span></h2>
                                        <p>Tử vi {{ $row['sign']['name'] }} ngày {{ $data['detail']['targetDate'] }}</p>
                                    </div>
                                </div>
                                <div class="tuvi-chd-ed-article-image">
                                    @include('components.zodiac-inline-card', [
                                        'spec' => $row['image']['renderSpec'],
                                        'layout' => 'desktop',
                                    ])
                                </div>
                                <div class="tuvi-chd-ed-article-content">
                                    <p class="tuvi-chd-ed-summary">{{ $row['sections']['overview'] }}</p>
                                    <div class="tuvi-chd-ed-section">
                                        <h3>
                                            <span>01</span>
                                            Sự nghiệp
                                        </h3>
                                        <p>{{ $row['sections']['career'] }}</p>
                                    </div>
                                    <div class="tuvi-chd-ed-section">
                                        <h3>
                                            <span>02</span>
                                            Tiền bạc
                                        </h3>
                                        <p>{{ $row['sections']['money'] }}</p>
                                    </div>
                                    <div class="tuvi-chd-ed-section">
                                        <h3>
                                            <span>03</span>
                                            Tình cảm
                                        </h3>
                                        <p>{{ $row['sections']['love'] }}</p>
                                    </div>
                                    <div class="tuvi-chd-ed-section">
                                        <h3>
                                            <span>04</span>
                                            Sức khoẻ
                                        </h3>
                                        <p>{{ $row['sections']['health'] }}</p>
                                    </div>
                                    <div class="tuvi-chd-ed-section">
                                        <h3>
                                            <span>05</span>
                                            Lời khuyên
                                        </h3>
                                        <p>{{ $row['sections']['advice'] }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
