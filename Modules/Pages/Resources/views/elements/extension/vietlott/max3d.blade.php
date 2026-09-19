@php
    $cate = \App\Helpers\Helpers::getSlugByType('VIETLOTT');
    $cate2 = \App\Helpers\Helpers::getSlugByFieldChildType($row['type']);
    $awards = @json_decode($row['awards'], true);
    $dataAwards = \App\Helpers\Helpers::parseMax3D($awards);
@endphp
@if(isset($firstItem) && $firstItem)
    <input type="hidden" value="{{ $row['day'] }}" id="ip_{{dataKey::TYPE_VL_MAX3D}}"/>
@endif
<div class="m3d-container mb-15">
    <div class="res-h">
        @if(isset($row['spin']) && $row['spin'])
            <h2>Quay thử xổ số {{ $cate2['title'] }} ngày {{ date('d-m-Y') }}</h2>
        @else
            @if(Route::currentRouteName() == 'page.home')
                @if(isset($h1) && $h1)
                    <h1>{{ $cate['title'] }} - Xổ số Max 3D {{ $row['thu'] }}
                        , KQ {{ $cate2['title'] }} hôm nay</h1>
                @else
                    <h2>Kết quả xổ số Max 3D {{ $row['thu'] }} ngày {{ \App\Helpers\Helpers::formatDate($row['day']) }}</h2>
                @endif
            @else
                @if(isset($h1) && $h1)
                    <h1>{{ $cate['title'] }} - Xổ số Max 3D {{ $row['thu'] }}
                        , KQ {{ $cate2['title'] }} hôm nay</h1>
                @else
                    <h2>Kết quả xổ số Max 3D {{ $row['thu'] }} ngày {{ \App\Helpers\Helpers::formatDate($row['day']) }}</h2>
                @endif
            @endif
        @endif
        <p>{{ \App\Helpers\Helpers::formatVietnameseDate($row['day']) }}</p>
        <div class="res-site-link">
            <a title="{{ $cate2['title'] }}"
               href="{{ route('page.cate.index', ['slug' => $cate['slug'].'/'.$cate2['slug']]) }}">{{ $cate2['title'] }}</a>
            <a title="{{ strtoupper($cate2['title']) }} {{ $row['thu'] }}"
               href="{{ route('page.cate.vietlott.thu', ['slug' => $cate['slug'], 'slug2' => $cate2['slug'], 'thu' => \App\Helpers\Helpers::renderSlug($row['thu'])]) }}">{{ strtoupper($cate2['title']) }} {{ $row['thu'] }}</a>
            <a title="{{ strtoupper($cate2['title']) }} {{ \App\Helpers\Helpers::formatDate($row['day']) }}"
               href="{{ route('page.cate.vietlott.ngay', ['slug' => $cate['slug'], 'slug2' => $cate2['slug'], 'ngay' => str_replace('/', '-', \App\Helpers\Helpers::formatDate($row['day']))]) }}">
                {{ strtoupper($cate2['title']) }} {{ \App\Helpers\Helpers::formatDate($row['day']) }}
            </a>
        </div>
    </div>

    @if(isset($row['spin']) && $row['spin'])
        <div class="tryspin-guide-wrapper">
            <div class="guide-text">
                <i class="fas fa-info-circle"></i>
                <span>Hệ thống giả lập quay số điện tử chính xác theo thuật toán lồng cầu. Bấm nút dưới đây để bắt đầu thử vận may!</span>
            </div>

            <div class="wrap-btn-spin">
                <button class="btn-vietlott-spin btn-spin-style" data-type="max3d">
                    <span class="icon-spin">🔄</span>
                    <span class="btn-text">QUAY THỬ KẾT QUẢ</span>
                </button>
            </div>
        </div>
    @endif

    @if(isset($row['spin']) && $row['spin'])
        <div class="m3d-result-board" id="area-max3d">
            <div class="m3d-res-row">
                <div class="m3d-res-label">G.1</div>
                <div class="m3d-res-content m3d-txt-red m3d-flex-center target-g1">
                    <span>---</span><span>---</span></div>
            </div>
            <div class="m3d-res-row">
                <div class="m3d-res-label">G.2</div>
                <div class="m3d-res-content m3d-grid-4 target-g2">
                    <span>---</span><span>---</span><span>---</span><span>---</span></div>
            </div>
            <div class="m3d-res-row">
                <div class="m3d-res-label">G.3</div>
                <div class="m3d-res-content m3d-grid-3 target-g3">
                    <span>---</span><span>---</span><span>---</span><span>---</span><span>---</span><span>---</span>
                </div>
            </div>
            <div class="m3d-res-row">
                <div class="m3d-res-label">KK</div>
                <div class="m3d-res-content m3d-grid-4 target-kk">
                    <span>---</span><span>---</span><span>---</span><span>---</span><span>---</span><span>---</span><span>---</span><span>---</span>
                </div>
            </div>
        </div>
    @else
        <div class="m3d-result-board">
            @foreach($row['options'] as $k=>$r)
                @php
                    $codes = !empty($r['code']) ? @json_decode($r['code'], true) : [];
                @endphp
                <div class="m3d-res-row">
                    <div class="m3d-res-label">{{ $r['level'] }}</div>
                    <div class="m3d-res-content
                    @if($r['level'] == 'G.1') m3d-txt-red m3d-flex-center @endif
                    @if($r['level'] == 'G.2') m3d-grid-4 @endif
                    @if($r['level'] == 'G.3') m3d-grid-3 @endif
                    @if($r['level'] == 'KK') m3d-grid-4 @endif
                        ">
                        @foreach($codes as $key=>$code)
                            <span>{{ $code }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @foreach($dataAwards as $k=>$award)
        <div class="m3d-info-section">
            <div class="m3d-section-bar">{{ $award['head'] }}</div>
            @if(!$k)
                <div class="m3d-info-header">
                    <div class="m3d-col-1">Giải thưởng</div>
                    <div class="m3d-col-2 m3d-col-22">Kết quả</div>
                    <div class="m3d-col-3">Số lượng giải</div>
                    <div class="m3d-col-4">Giá trị giải (đồng)</div>
                </div>
            @endif
            @php $count = 1; @endphp
            @foreach($award['items'] as $key=>$item)
                <div class="m3d-info-row">
                    <div class="m3d-col-1">{{ $item[0] }}</div>
                    <div class="m3d-col-2 @if(!$k) lbl-{{$count > 3 ? 'kk' : 'g'.$count}} @endif">
                        @if(isset($row['spin']) && $row['spin'] && !$k)
                            @foreach(explode(',', $item[1]) as $kn=>$n)
                                {{ !$kn ? '' : ', ' }}---
                            @endforeach
                        @else
                            {{ $item[1] }}
                        @endif
                    </div>
                    <div class="m3d-col-3">{{ $item[2] }}</div>
                    <div class="m3d-col-4">{{ $item[3] }}</div>
                </div>
                @php $count ++; @endphp
            @endforeach
        </div>
    @endforeach
</div>
