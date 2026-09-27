@extends('pages::layouts.app')

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
                        'name' => $data['detail']['title'],
                        'description' => $data['detail']['description'],
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
                                'name' => $data['detail']['title'],
                                'item' => route('page.post.show', ['slug' => $data['detail']['slug']]),
                            ]
                        ],
                    ],
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
    <div class="article-detail-layout">
        <article class="card article-detail">
            <header class="article-detail-head">
                <span class="article-category">LỊCH VIỆT</span>
                <h1>{{ $data['detail']['title'] }}</h1>
                <p class="article-lead">
                    {{ $data['detail']['description'] }}
                </p>
                <div class="article-byline">
                    <span>Cập nhật: {{ date('d/m/Y', strtotime($data['detail']['created_at'])) }}</span>
                    <span>{{ \App\Helpers\Helpers::timeAgo($data['detail']['created_at']) }}</span>
                </div>
            </header>
            <div class="article-body mt-5">
                {!! $data['detail']['tableOfContent'] !!}
            </div>
            <footer class="article-tags">
                <strong>Chủ đề:</strong>
                @foreach($hashTags as $row)
                    <a href="{{ route('page.post.tags', ['slug' => $row['slug']]) }}"
                       data-topic="{{ $row['slug'] }}"># {{ $row['title'] }}</a>
                @endforeach
            </footer>
        </article>
        <aside class="article-sidebar">
            <section class="card sidebar-panel">
                <span class="section-label">BÀI LIÊN QUAN</span>
                <h2>Đọc tiếp</h2>
                @foreach($data['detail']['related'] as $row)
                    <a href="{{ route('page.post.show', ['slug' => $row['slug']]) }}"
                       title="{{ $row['title'] }}"
                       aria-label="{{ $row['title'] }}">
                        <strong>{{ $row['title'] }}</strong>
                        <small>{{ \App\Helpers\Helpers::timeAgo($row['created_at']) }}</small>
                    </a>
                @endforeach
            </section>
            <section class="card sidebar-action">
                <strong>Tra cứu lịch hôm nay</strong>
                <p>Xem nhanh ngày âm, giờ tốt và thông tin xuất hành.</p>
                <a
                    href="{{ route('page.home') }}"
                    aria-label="Lịch âm hôm nay"
                    title="Lịch âm hôm nay"
                >
                    Mở lịch hôm nay →
                </a>
            </section>
        </aside>
    </div>
@endsection
