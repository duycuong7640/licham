@extends('pages::layouts.news')

@section('content')
    @push('schema')
        @include('pages::elements.extend.post-list-schema')
    @endpush
    <section class="category-page-head reveal mb-4">
        @include('pages::elements.breadcrumb')
        <h1>{{ !empty($data['hashTag']) ? $data['hashTag']['h1'] : $data['category']['h1'] }}</h1>
        <p>{!! !empty($data['hashTag']) ? $data['hashTag']['description'] : $data['category']['description'] !!}</p>
    </section>

    <section class="category-content-frame reveal">
        <div class="category-list-page">
            @if(!empty($data['lists']['data']))
                @foreach($data['lists']['data'] as $k=>$row)
                    <article class="category-list-item">
                        <a class="image-frame" href="{{ route('page.post.show', ['slug' => $row['slug']]) }}"
                           title="{{ $row['title'] }}" aria-label="{{ $row['title'] }}">
                            <img
                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 220 168'%3E%3C/svg%3E"
                                loading="lazy" decoding="async"
                                data-src="{{ \App\Helpers\Helpers::renderThumb($row['thumbnail']) }}"
                                title="{{ $row['title'] }}" alt="{{ $row['title'] }}" width="220" height="168"
                                style="aspect-ratio: 1.3;">
                        </a>
                        <div>
                            <h2>
                                <a href="{{ route('page.post.show', ['slug' => $row['slug']]) }}"
                                   title="{{ $row['title'] }}">
                                    {{ $row['title'] }}
                                </a>
                            </h2>
                            <p class="lnews-description">
                                {!! mb_trim(strip_tags($row['description'])) !!}
                            </p>
                            <div class="category-item-meta">
                                <span>{{ !empty($data['typeCalendar']['keyTitle'][$row['type']]) ? $data['typeCalendar']['keyTitle'][$row['type']] : '' }}</span>
                                <span>•</span>
                                <span>{{ \App\Helpers\Helpers::timeAgo($row['created_at']) }}</span>
                                <span>•</span>
                                <span>{{ \App\Helpers\Helpers::formatViews($row['view']) }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            @endif
        </div>

        @include('pages::elements.extension.paginate', ['data' => $data])
    </section>
@endsection

@section('scripts')

@endsection
