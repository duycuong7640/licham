@php
    $cate = \App\Helpers\Helpers::getSlugByType('VIETLOTT');
    $cate2 = \App\Helpers\Helpers::getSlugByFieldChildType('KENO');
    $now = date('d-m-Y H:i:s');
    $thu = \Carbon\Carbon::now()->locale('vi')->isoFormat('dddd');
    $firstRecord = $row;
    $codes = !empty($firstRecord['options'][0]['code']) ? @json_decode($firstRecord['options'][0]['code'], true) : [];
    $calEvenOdd = \App\Helpers\Helpers::analyzeNumbers($codes);
    $ky = !empty($firstRecord['ky']) ? str_replace('#', '', $firstRecord['ky']) + 1 : '';
@endphp
@if(isset($firstItem) && $firstItem)
    <input type="hidden" value="{{ $row['day'] }}" id="ip_{{dataKey::TYPE_VL_KENO}}"/>
@endif
<div class="kn-res-container">
    <div class="res-h">
        @if(isset($row['spin']) && $row['spin'])
            <h2>Quay thử xổ số {{ $cate2['title'] }} ngày {{ date('d-m-Y') }}</h2>
        @else
            @if(isset($h1) && $h1)
                <h1>{{ $cate['title'] }} - Xổ số Keno {{ $thu }}, KQ {{ $cate2['title'] }} hôm nay</h1>
            @else
                <h2>{{ $cate['title'] }} - Xổ số Keno {{ $thu }}, KQ {{ $cate2['title'] }} hôm nay</h2>
            @endif
        @endif
        <p>{{ \App\Helpers\Helpers::formatVietnameseDate($now) }}, Kỳ: #{{ $ky }}</p>
        <div class="res-site-link">
            <a title="{{ $cate2['title'] }}"
               href="{{ route('page.cate.index', ['slug' => $cate['slug'].'/'.$cate2['slug']]) }}">{{ $cate2['title'] }}</a>
            <a title="{{ $cate2['title'] }} {{ $thu }}"
               href="{{ route('page.cate.vietlott.thu', ['slug' => $cate['slug'], 'slug2' => $cate2['slug'], 'thu' => \App\Helpers\Helpers::renderSlug($thu)]) }}">
                {{ $cate2['title'] }} {{ $thu }}
            </a>
            <a title="{{ $cate2['title'] }} {{ \App\Helpers\Helpers::formatDate($now) }}"
               href="{{ route('page.cate.vietlott.ngay', ['slug' => $cate['slug'], 'slug2' => $cate2['slug'], 'ngay' => str_replace('/', '-', \App\Helpers\Helpers::formatDate($now))]) }}">
                {{ $cate2['title'] }} {{ \App\Helpers\Helpers::formatDate($now) }}
            </a>
            @if(!isset($row['spin']))
                <div class="kn-res-countdown"
                     data-time="{{ !empty($firstRecord['day']) ? $firstRecord['day'] : '' }}">
                    Kỳ vé tiếp theo:
                    <span class="kn-res-time-box minute"></span>
                    <span class="kn-res-time-box second"></span>
                </div>
            @endif
        </div>
    </div>

    @if(isset($row['spin']) && $row['spin'])
        <div class="tryspin-guide-wrapper">
            <div class="guide-text">
                <i class="fas fa-info-circle"></i>
                <span>Hệ thống giả lập quay số điện tử chính xác theo thuật toán lồng cầu. Bấm nút dưới đây để bắt đầu thử vận may!</span>
            </div>

            <div class="wrap-btn-spin">
                <button class="btn-vietlott-spin btn-spin-style" data-type="keno">
                    <span class="icon-spin">🔄</span>
                    <span class="btn-text">QUAY THỬ KẾT QUẢ</span>
                </button>
            </div>
        </div>
    @endif

    <div class="kn-res-main-board" @if(isset($row['spin']) && $row['spin']) id="area-keno" @endif>
        <div class="kn-res-stats-container">
            @if(isset($row['spin']) && $row['spin'])
                <div class="kn-res-stat-box">
                    <div class="kn-res-stat-item"><span
                            class="kn-res-circle kn-res-blue">C</span><strong>Chẵn</strong><span
                            class="kn-res-val val-chan">-</span></div>
                    <div class="kn-res-stat-item"><span
                            class="kn-res-circle kn-res-blue">L</span><strong>Lẻ</strong><span
                            class="kn-res-val val-le">-</span></div>
                    <div class="kn-res-divider"></div>
                    <div class="kn-res-stat-item"><strong>Tổng:</strong><span
                            class="kn-res-val val-tong">-</span></div>
                </div>
                <div class="kn-res-stat-box">
                    <div class="kn-res-stat-item"><span
                            class="kn-res-circle kn-res-orange">&gt;</span><strong>Lớn</strong><span
                            class="kn-res-val val-lon">-</span></div>
                    <div class="kn-res-stat-item"><span
                            class="kn-res-circle kn-res-orange">&lt;</span><strong>Nhỏ</strong><span
                            class="kn-res-val val-nho">-</span></div>
                    <div class="kn-res-divider"></div>
                </div>
            @else
                <div class="kn-res-stat-box">
                    <div class="kn-res-stat-item"><span class="kn-res-circle kn-res-blue">C</span>
                        <strong>Chẵn</strong>
                        <span class="kn-res-val">{{ $calEvenOdd['chan'] }}</span></div>
                    <div class="kn-res-stat-item"><span class="kn-res-circle kn-res-blue">L</span>
                        <strong>Lẻ</strong>
                        <span class="kn-res-val">{{ $calEvenOdd['le'] }}</span></div>
                    <div class="kn-res-divider"></div>
                    <div class="kn-res-stat-item"><strong>Tổng:</strong> <span
                            class="kn-res-val">{{ !empty($codes) ? array_sum($codes) : 0 }}</span></div>
                </div>
                <div class="kn-res-stat-box">
                    <div class="kn-res-stat-item"><span class="kn-res-circle kn-res-orange">></span>
                        <strong>Lớn</strong> <span class="kn-res-val">{{ $calEvenOdd['lon'] }}</span></div>
                    <div class="kn-res-stat-item"><span class="kn-res-circle kn-res-orange"><</span>
                        <strong>Nhỏ</strong> <span class="kn-res-val">{{ $calEvenOdd['nho'] }}</span></div>
                    <div class="kn-res-divider"></div>
                    @if(!isset($row['spin']))
                        <div class="kn-res-stat-item"><strong>Kỳ sau:</strong> <span
                                class="kn-res-val kn-res-red-text">{{ date('H') >= 22 ? '06:00' : (!empty($firstRecord['day']) ? \App\Helpers\Helpers::addMinutesToTime($firstRecord['day']) : '') }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        <div class="kn-res-ball-grid @if(isset($row['spin']) && $row['spin']) target-balls @endif">
            @if(!empty($codes))
                @foreach($codes as $code)
                    <span class="kn-res-ball" @if(!isset($row['spin'])) @if($code) data-loading="1"
                          @else data-loading="0"
                          @endif @endif @if(isset($row['spin']) && $row['spin']) style="width: 37px;" @endif>
                        @if(isset($row['spin']) && $row['spin'])
                            --
                        @else
                            {{ $code }}
                        @endif
                    </span>
                @endforeach
            @endif
        </div>
    </div>
</div>
