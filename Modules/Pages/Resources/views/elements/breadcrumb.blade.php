<nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('page.home') }}" title="Trang chủ">Trang chủ</a>

    @if(!empty($data['page']) && $data['page'] == 'home')
        <span>/</span>
        <span>Lịch âm hôm nay</span>
    @endif

    @if(!empty($data['categoryParent']))

        @php
            $slugP = $data['categoryParent']['slug'];
            if($data['categoryParent']['type'] == dataKey::COPE) {
                $childText = \App\Helpers\Helpers::findByType(dataKey::COPE_DAY);
                $slugP = $childText['slug'];
            }
        @endphp
        <span>/</span>
        <a href="{{ route('page.cate.index', ['slug' => $slugP]) }}">{{ $data['categoryParent']['title'] }}</a>
    @endif

    @if(!empty($data['category']))
        @if(!empty($data['category']['type']))
            @php
                $slug = $data['category']['slug'];
                if($data['category']['type'] == dataKey::COPE_YEAR) $slug = $slug.'-'.date('Y');
            @endphp
            <span>/</span>
            <a href="{{ route('page.cate.index', ['slug' => $slug]) }}">{{ $data['category']['title'] }}</a>
        @else
            <span>/</span>
            <a href="{{ route('page.policy.index', ['slug' => $data['category']['slug']]) }}">{{ $data['category']['title'] }}</a>
        @endif
    @endif

    @if(!empty($data['hashTag']['id']))
        <span>/</span>
        <span>{{ $data['hashTag']['title'] }}</span>
    @endif

    @if(!empty($data['detail']['id']) && $data['detail']['type'] == dataKey::COPE_DAY)
        <span>/</span>
        <span>Ngày {{ date('d-m-Y', strtotime($data['detail']['day'])) }}</span>
    @endif

    @if(!empty($data['wData']))
        <span>/</span>
        <span>Tuần {{ $data['wData']['week'] }} năm {{ $data['wData']['year'] }}</span>
    @endif

    @if(!empty($data['mData']))
        <span>/</span>
        <span>Tháng {{ $data['mData']['month'] }} năm {{ $data['mData']['year'] }}</span>
    @endif

    @if(!empty($data['year']))
        <span>/</span>
        <span>Năm {{ $data['year'] }}</span>
    @endif
</nav>
