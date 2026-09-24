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
        @elseif(!empty($data['isPage']) && $data['isPage'] == 'fixed')
            <span>/</span>
            <span>{{ $data['category']['title'] }}</span>
        @else
            <span>/</span>
            <span>{{ $data['category']['title'] }}</span>
        @endif
    @endif

    @if(!empty($data['hashTag']['id']))
        <span>/</span>
        <span>{{ $data['hashTag']['title'] }}</span>
    @endif

    @if(!empty($data['year']) && !empty($data['isPage']) && $data['isPage'] !== 'home')
        <span>/</span>
{{--        @if(!empty($data['isPage']) && $data['isPage'] == 'year')--}}
{{--            <span>Năm {{ $data['year'] }}</span>--}}
{{--        @else--}}
            <a href="{{ route('page.cope.show.year', ['year' => $data['year']]) }}">Năm {{ $data['year'] }}</a>
{{--        @endif--}}
    @endif

    @if(!empty($data['mData']) && !empty($data['isPage']) && $data['isPage'] !== 'home')
        <span>/</span>
        {{--        @if(!empty($data['isPage']) && $data['isPage'] == 'month')--}}
        {{--            <span>Tháng {{ \App\Helpers\Helpers::checkNumber($data['mData']['month']) }}</span>--}}
        {{--        @else--}}
        <a href="{{ route('page.cope.show.month', ['month' => \App\Helpers\Helpers::checkNumber($data['mData']['month']), 'year' => $data['mData']['year']]) }}">
            Tháng {{ \App\Helpers\Helpers::checkNumber($data['mData']['month']) }}
        </a>
        {{--        @endif--}}
    @endif

    @if(!empty($data['isPage']) && $data['isPage'] == 'day' && !empty($data['day']['id']))
        <span>/</span>
        {{--        <span>Ngày {{ \App\Helpers\Helpers::checkNumber($data['day']['d']) }}-{{ \App\Helpers\Helpers::checkNumber($data['day']['m']) }}-{{ \App\Helpers\Helpers::checkNumber($data['day']['y']) }}</span>--}}
        <a href="{{ route('page.cope.show.day', ['day' => \App\Helpers\Helpers::checkNumber($data['day']['d']), 'month' => \App\Helpers\Helpers::checkNumber($data['day']['m']), 'year' => $data['day']['y']]) }}">
            Ngày {{ \App\Helpers\Helpers::checkNumber($data['day']['d']) }}-{{ \App\Helpers\Helpers::checkNumber($data['day']['m']) }}-{{ \App\Helpers\Helpers::checkNumber($data['day']['y']) }}
        </a>
    @endif
</nav>
