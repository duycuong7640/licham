@extends('pages::layouts.news')

@section('content')
    @php
      $schemaName = "Kiến thức lịch âm, tử vi, phong thủy và văn hóa Việt";
    @endphp
    @push('schema')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'CollectionPage',
                        '@id' => $data['seo']['canonical'] . '#webpage',
                        'url' => $data['seo']['canonical'],
                        'name' => $schemaName,
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
                                'name' => $data['category']['title'],
                                'item' => route('page.cate.index', ['slug' => $data['category']['slug']]),
                            ]
                        ],
                    ],
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
    <header class="card editorial-hero">
        <span class="section-label">THƯ VIỆN KIẾN THỨC</span>
        <h1>Kiến thức lịch âm, tử vi, phong thủy và văn hóa Việt</h1>
        <p>
            Tìm hiểu lịch âm, Can Chi, 12 con giáp,
            phong thủy và những phong tục quen thuộc
            trong đời sống người Việt qua các bài viết
            được trình bày rõ ràng, dễ tra cứu.
        </p>
    </header>

    @if(!empty($data['author']['id']))
        <section
        id="authorProfile"
        class="card author-profile"
        aria-labelledby="author-profile-name"
    >
        <div class="author-profile-main">
            <span class="author-profile-avatar" aria-hidden="true">BBT</span>
            <div class="author-profile-heading">
                <span class="author-profile-label">Hồ sơ tác giả</span>
                <h2 id="author-profile-name">
                    Biên tập viên: {{ $data['author']['title'] }}
                    <span title="Tác giả đã xác thực" aria-label="Đã xác thực">✓</span>
                </h2>
                <p>
                    {{ $data['author']['description'] }}
                </p>
            </div>
        </div>
        <dl class="author-profile-stats">
            <div>
                <dt>Vai trò</dt>
                <dd>Biên tập nội dung</dd>
            </div>
            <div>
                <dt>Bài viết</dt>
                <dd>{{ $data['author']['post_count'] }} bài</dd>
            </div>
            <div>
                <dt>Đánh giá</dt>
                <dd><span aria-hidden="true">★</span> {{ $data['author']['rank'] }}.0/5</dd>
            </div>
            <div>
                <dt>Chuyên mục</dt>
                <dd>Lịch Việt · Văn hóa</dd>
            </div>
        </dl>
    </section>
    @endif

    <nav class="topic-filter" aria-label="Lọc bài viết theo chủ đề">
        <a class="active" href="{{ route('page.cate.index', ['slug' => 'bai-viet']) }}" data-topic="all">Tất cả</a>
        @foreach($hashTags as $row)
            <a href="{{ route('page.post.tags', ['slug' => $row['slug']]) }}"
               data-topic="{{ $row['slug'] }}" title="{{ $row['title'] }}"># {{ $row['title'] }}</a>
        @endforeach
    </nav>

    <section class="article-index" aria-labelledby="article-list-title">
        <div class="article-index-head">
            <div>
                <span class="section-label">BÀI VIẾT MỚI</span>
                <h2 id="article-list-title">Kiến thức chọn lọc</h2>
            </div>
        </div>
        <div class="article-grid">
            @php
                $hashtags = [];
                foreach ($hashTags as $row) {
                    $hashtags[$row['id']] = $row;
                }
                $lists = !empty($data['lists']['data']) ? $data['lists']['data'] : $data['lists'];
            @endphp
            @foreach($lists as $row)
                @php
                    $hashtagId = !empty($row['post_hashtags'][0]['hashtag_id']) ? $row['post_hashtags'][0]['hashtag_id'] : '';
                    $hashtag = !empty($hashtags[$hashtagId]) ? $hashtags[$hashtagId] : [];
                @endphp
                @if(!empty($row['id']))
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
                @endif
            @endforeach
        </div>

        @include('pages::elements.extend.paginate', ['data' => $data])
    </section>
@endsection

@section('scripts')

@endsection
