@extends('pages::layouts.news')

@section('content')
    @push('schema')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'CollectionPage',
                        '@id' => $data['seo']['canonical'] . '#webpage',
                        'url' => $data['seo']['canonical'],
                        'name' => $data['hashTag']['title'],
                        'description' => $data['seo']['meta_des'],
                        'inLanguage' => 'vi-VN',
                        'breadcrumb' => [
                            '@id' => $data['seo']['canonical'] . '#breadcrumb',
                        ],
                        'about' => [
                            '@type' => 'Thing',
                            'name' => $data['seo']['name'],
                        ],
                    ],
                    [
                        '@type' => 'BreadcrumbList',
                        '@id' => $data['seo']['canonical'] . '#breadcrumb',
                        'itemListElement' => [
                            [
                                '@type' => 'ListItem',
                                'position' => 1,
                                'name' => 'Trang chủ',
                                'item' => route('page.home'),
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 2,
                                'name' => "Bài viết",
                                'item' => route('page.cate.index', ['slug' => 'bai-viet']),
                            ],
                            [
                                '@type' => 'ListItem',
                                'position' => 3,
                                'name' => $data['hashTag']['title'],
                                'item' => route('page.post.tags', ['slug' => $data['hashTag']['slug']]),
                            ]
                        ],
                    ],
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
    <header class="card editorial-hero">
        <span class="section-label">THƯ VIỆN KIẾN THỨC</span>
        <h1>{{ $data['hashTag']['title'] }}</h1>
        <p>
            {{ \App\Helpers\Helpers::shortDesc(strip_tags($data['hashTag']['description']), 150) }}
        </p>
    </header>

    <nav class="topic-filter" aria-label="Lọc bài viết theo chủ đề">
        <a href="{{ route('page.cate.index', ['slug' => 'bai-viet']) }}" data-topic="all">Tất cả</a>
        @foreach($hashTags as $row)
            <a @if($row['id'] == $data['hashTag']['id']) class="active" @endif href="{{ route('page.post.tags', ['slug' => $row['slug']]) }}"
               data-topic="{{ $row['slug'] }}" title="{{ $row['title'] }}"># {{ $row['title'] }}</a>
        @endforeach
    </nav>

    <section class="article-index" aria-labelledby="article-list-title">
        <div class="article-grid">
            @php
                $hashtags = [];
                foreach ($hashTags as $row) {
                    $hashtags[$row['id']] = $row;
                }
            @endphp
            @foreach($data['lists']['data'] as $row)
                @php
                    $hashtagId = !empty($row['post_hashtags'][0]['hashtag_id']) ? $row['post_hashtags'][0]['hashtag_id'] : '';
                    $hashtag = !empty($hashtags[$hashtagId]) ? $hashtags[$hashtagId] : [];
                @endphp
                <article class="article-item" data-article-card data-category="tu-vi">
                    <a class="article-thumb" href="{{ route('page.post.show', ['slug' => $row['slug']]) }}"
                       title="{{ $row['title'] }}"
                       aria-label="{{ $row['title'] }}">
                        <img
                            src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 335 189'%3E%3C/svg%3E"
                            loading="lazy"
                            class="lazy"
                            data-src="{{ Helpers::renderThumb($row['thumbnail']) }}"
                            title="{{ $row['title'] }}"
                            alt="{{ $row['title'] }}"
                            width="335"
                            height="189"
                            fetchpriority="high"
                            style="aspect-ratio: 1.77;"
                        >
                    </a>
                    <span class="article-category">{{ !empty($hashtag['title']) ? $hashtag['title'] : 'Bài viết' }}</span>
                    <h3>
                        <a href="{{ route('page.post.show', ['slug' => $row['slug']]) }}" title="{{ $row['title'] }}">{{ $row['title'] }}</a>
                    </h3>
                    <p>{!! \App\Helpers\Helpers::shortDesc(mb_trim(strip_tags($row['description'])), 245) !!}</p>
                    <div class="article-meta">
                        <span>{{ date('d/m/Y', strtotime($row['created_at'])) }} · {{ \App\Helpers\Helpers::timeAgo($row['created_at']) }}</span>
                        <a href="{{ route('page.post.show', ['slug' => $row['slug']]) }}" title="Xem chi tiết">
                            Xem chi tiết →
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        @include('pages::elements.extend.paginate', ['data' => $data])
    </section>
@endsection

@section('scripts')

@endsection
