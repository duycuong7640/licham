@extends('pages::layouts.app')

@section('content')
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
                    href="{{ route('page.cope.show.day', ['day' => date('d'), 'month' => date('m'), 'year' => date('Y')]) }}"
                    aria-label="Ngày {{ date('d') }} tháng {{ date('m') }} năm {{ date('Y') }}"
                    title="Ngày {{ date('d') }} tháng {{ date('m') }} năm {{ date('Y') }}"
                >
                    Mở lịch hôm nay →
                </a>
            </section>
        </aside>
    </div>
@endsection
