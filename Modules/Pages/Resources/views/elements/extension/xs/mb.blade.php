@php
    $cate = \App\Helpers\Helpers::getSlugByType('MB');
@endphp
@if(isset($firstItem) && $firstItem)
    <input type="hidden" value="{{ $row['day'] }}" id="ip_{{dataKey::TYPE_XS_MB}}"/>
@endif
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
    <div class="res-sub-info">
        {!! \App\Helpers\Helpers::formatTicket($row['label_ticket']) !!}
    </div>
    @if(!empty($row['options']))
        <div class="lottery-content mb-15">
            @foreach($row['options'] as $key=>$r)
                @php $codes = @json_decode($r['code']) @endphp
                @if($r['level'] == 'ĐB')
                    <div class="lottery-row row-db">
                        <div class="lottery-lbl">{{ $r['level'] }}</div>
                        <div class="lottery-val red-bold">{{ $codes[0] }}</div>
                    </div>
                @else
                    <div class="lottery-row">
                        <div class="lottery-lbl">{{ $r['level'] }}</div>
                        @if(count($codes) == 1)
                            <div class="lottery-val bold-20">{{ $codes[0] }}</div>
                        @else
                            <div
                                class="lottery-val @if(count($codes) == 4) val-grid-4 @elseif(count($codes) > 4) val-grid-3 @else val-split @endif">
                                @foreach($codes as $code)
                                    <span>{{ $code }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    @include('pages::elements.extension.loto', ['row' => $row, 'cate_title' => $cate['title']])
</div>
