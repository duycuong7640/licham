@if(!empty($menu))
    @php
        $type = $data['category']['type'];
    @endphp
    <nav class="day-tabs" aria-label="Chọn kiểu lịch">
        @foreach($menu as $k=>$value)
            @if(!empty($value['parent']) && $value['parent'] == dataKey::COPE && in_array($value['type'], [dataKey::COPE_DAY, dataKey::COPE_WEEK, dataKey::COPE_MONTH, dataKey::COPE_YEAR, dataKey::COPE_LUNAR]))
                @php
                    $slug = $value['slug'];
                    if($value['type'] == dataKey::COPE_YEAR) $slug = $slug.'-'.date('Y');
                @endphp
                <a @if($value['type'] == $type) class="active"
                   @endif href="{{ route('page.cate.index', ['slug' => $slug]) }}">
                    {{ dataKey::COPE_TEXT_KEYS[$value['type']] }}
                </a>
            @endif
        @endforeach
    </nav>
@endif
