<div class="tab-parent">
    <div class="tabs">
        @foreach($row['children'] as $index => $r)
            <div class="tab {{ $index == 0 ? 'active' : '' }}" data-tab="tab-{{ $r['id'] }}">
                {{ $r['province'] }}
            </div>
        @endforeach
    </div>

    @foreach($row['children'] as $index => $r)
        <div class="tab-content {{ $index == 0 ? 'active' : '' }}" id="tab-{{ $r['id'] }}">
            @include('pages::elements.extension.loto', ['row' => $r, 'cate_title' => $r['province']])
        </div>
    @endforeach
</div>
