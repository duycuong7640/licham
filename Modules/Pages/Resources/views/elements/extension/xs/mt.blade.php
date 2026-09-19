@php
    $cate = \App\Helpers\Helpers::getSlugByType('MT');
@endphp
@if(isset($firstItem) && $firstItem)
    <input type="hidden" value="{{ $row['day'] }}" id="ip_{{dataKey::TYPE_XS_MT}}"/>
@endif
@php
    $loto = \App\Helpers\Helpers::buildLoto($row['options']);
@endphp
<div class="result-container panel-side shadow mb-15">
    <div class="res-h">
        @if(isset($h1) && $h1)
            <h1>{{ $cate['type'] }} - Kết quả Xổ
                số {{ $cate['title'] }} - {{ $cate['type'] }} hôm nay</h1>
        @else
            <h2>{{ $cate['type'] }} - Kết quả Xổ
                số {{ $cate['title'] }} - {{ $cate['type'] }} hôm nay</h2>
        @endif
        <p>{{ \App\Helpers\Helpers::formatVietnameseDate($row['day']) }}</p>
        <div class="res-site-link">
            <a title="{{ $row['type'] }}"
               href="{{ route('page.cate.index', ['slug' => $cate['slug']]) }}">{{ $cate['type'] }}</a>
            <a title="{{ $row['type'] }} {{ $row['thu'] }}"
               href="{{ route('page.cate.index', ['slug' => $cate['slug'].'/'.\App\Helpers\Helpers::renderSlug($row['thu'])]) }}">{{ $cate['type'] }} {{ $row['thu'] }}</a>
            <a title="{{ $row['type'] }} {{ \App\Helpers\Helpers::formatDate($row['day']) }}"
               href="{{ route('page.cate.ngay', ['slug' => $cate['slug'], 'ngay' => str_replace('/', '-', \App\Helpers\Helpers::formatDate($row['day']))]) }}">{{ $cate['type'] }} {{ \App\Helpers\Helpers::formatDate($row['day']) }}</a>
            @if(!empty($row['province'])) ({{ $row['province'] }}) @endif
        </div>
    </div>
    @if(!empty($row['children']))
        <div class="mb-15">
            <div class="row-xs header-row">
                <div class="col-xs label-col">G</div>
                @foreach($row['children'] as $child)
                    <div class="col-xs data-col highlight-blue text-kqxs">
                        <a href="{{ route('page.cate.region', ['slug' => $cate['slug'], 'region' => \App\Helpers\Helpers::renderSlug(trim($child['province']))]) }}"
                           title="{{ $child['province'] }}">
                            {{ $child['province'] }}
                        </a>
                    </div>
                @endforeach
            </div>

            @php
                $levels = ['8','7','6','5','4','3','2','1','ĐB'];
                $childCounters = [];
            @endphp
            @foreach($levels as $level)
                <div class="row-xs {{ $loop->index % 2 == 1 ? 'bg-gray' : '' }}">
                    <div class="col-xs label-col">{{ $level }}</div>
                    @foreach($row['children'] as $childIndex => $child)
                        @php
                            if (!isset($childCounters[$childIndex])) {
                                $childCounters[$childIndex] = false;
                            }

                            $option = collect($child['options'])->firstWhere('level', $level);
                            $codes = $option ? json_decode($option['code'], true) : [];
                        @endphp

                        <div class="col-xs data-col {{ in_array($level, ['8','ĐB']) ? 'prize-red' : '' }}">
                            @if(!empty($codes))
                                @foreach($codes as $code)
                                    @php $code = trim((string)$code); @endphp
                                    <div>
                                        @if(is_numeric($code))
                                            <div class="random">{{ $code }}</div>
                                        @else
                                            @if(!$childCounters[$childIndex])
                                                @php $childCounters[$childIndex] = true; @endphp
                                                <div class="counter-container">
                                                    @for($i = 0; $i < 5; $i++)
                                                        <div class="digit-box">0</div>
                                                    @endfor
                                                </div>
                                            @else
                                                <img src="{{ asset('static/web/images/long-quay.gif') }}"
                                                     style="height:20px;">
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <img src="{{ asset('static/web/images/long-quay.gif') }}"
                                     style="height:20px;">
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
    @include('pages::elements.extension.loto-tab', ['row' => $row])
</div>
