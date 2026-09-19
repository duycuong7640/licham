<aside class="col-side-left">
    <div class="panel-side">
        <div class="panel-h yellow-h">Xổ số miền bắc</div>
        <div class="panel-b">
            @php
                $cate = \App\Helpers\Helpers::getSlugByType('MB');
            @endphp
            <a href="{{ route('page.cate.index', ['slug' => $cate['slug']]) }}" title="{{ $cate['title'] }}"
               class="side-link"><i class="fa-solid fa-caret-right"></i> {{ $cate['title'] }}</a>
        </div>
    </div>

    <div class="panel-side mt-15">
        <div class="panel-h blue-h">Xổ số miền nam</div>
        <div class="panel-b">
            @php
                $cate = \App\Helpers\Helpers::getSlugByFieldType(dataMenu::mn['type']);
            @endphp
            @foreach(dataMenu::mn['list'] as $k=>$row)
                <a href="{{ route('page.cate.region', ['slug' => $cate['slug'], 'region' => $k]) }}" title="{{ $row }}"
                   class="side-link"><i class="fa-solid fa-caret-right"></i> {{ $row }}</a>
            @endforeach
        </div>
    </div>

    <div class="panel-side mt-15">
        <div class="panel-h blue-h">Xổ số miền trung</div>
        <div class="panel-b">
            @php
                $cate = \App\Helpers\Helpers::getSlugByFieldType(dataMenu::mt['type']);
            @endphp
            @foreach(dataMenu::mt['list'] as $k=>$row)
                <a href="{{ route('page.cate.region', ['slug' => $cate['slug'], 'region' => $k]) }}" title="{{ $row }}"
                   class="side-link"><i class="fa-solid fa-caret-right"></i> {{ $row }}</a>
            @endforeach
        </div>
    </div>

    <div class="panel-side mt-15">
        <div class="panel-h blue-h">Vietlott</div>
        <div class="panel-b">
            @foreach(dataMenu::menus() as $k=>$row)
                @if($row['level'] == 2 && !empty($row['type']) &&  !empty($row['parent']) && $row['parent'] == 'VIETLOTT')
                    <a href="{{ route('page.cate.index', ['slug' => strtolower($row['parent']).'/'.$row['slug']]) }}"
                       title="{{ $row['title'] }}"
                       class="side-link"><i class="fa-solid fa-caret-right"></i> {{ $row['title'] }}</a>
                @endif
            @endforeach
        </div>
    </div>
</aside>
