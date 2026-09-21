@extends('pages::layouts.news')

@section('content')
    <header class="card editorial-hero">
        <span class="section-label">THƯ VIỆN KIẾN THỨC</span>
        <h1>Hiểu lịch Việt, sống thuận nhịp mỗi ngày</h1>
        <p>
            Kiến thức được trình bày dễ hiểu về lịch âm, tử vi, phong thủy và các
            phong tục thường gặp trong đời sống người Việt.
        </p>
    </header>

    <nav class="topic-filter" aria-label="Lọc bài viết theo chủ đề">
        <a class="active" href="{{ route('page.cate.index', ['slug' => 'bai-viet']) }}" data-topic="all">Tất cả</a>
        @foreach($data['hashTags'] as $row)
            <a href="{{ route('page.post.tags', ['slug' => $row['slug']]) }}"
               data-topic="{{ $row['slug'] }}"># {{ $row['title'] }}</a>
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
                foreach ($data['hashTags'] as $row) {
                    $hashtags[$row['id']] = $row;
                }
            @endphp
            @foreach($data['lists'] as $row)
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
        <p class="article-empty" id="articleEmpty" hidden>
            Chưa có bài viết trong chủ đề này.
        </p>
        <nav
            class="article-pagination"
            id="articlePagination"
            aria-label="Phân trang bài viết"
            hidden
        ></nav>
    </section>
@endsection

@section('scripts')

@endsection
