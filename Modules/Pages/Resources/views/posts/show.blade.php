@extends('pages::layouts.app')

@section('content')
    @push('schema')
        @include('pages::elements.extend.article-schema')
    @endpush
    <div class="result-container panel-side shadow">
        <div class="res-h res-h-new">
            <h1>{{ $data['detail']['title'] }}</h1>
        </div>
        <div class="content-news">
            {!! $data['detail']['tableOfContent'] !!}
            <div class="article-meta">
                @if(!empty($data['detail']['author']['name']))
                    <span>
                        Tác giả: {{ $data['detail']['author']['name'] }}
                    </span>
                @endif

                @if(!empty($data['detail']['created_at']))
                    <time datetime="{{ \Carbon\Carbon::parse($data['detail']['created_at'])->toIso8601String() }}">
                        Đăng ngày
                        {{ \Carbon\Carbon::parse($data['detail']['created_at'])->format('d/m/Y') }}
                    </time>
                @endif

                @if(
                    !empty($data['detail']['updated_at'])
                    && $data['detail']['updated_at'] !== $data['detail']['created_at']
                )
                    <time datetime="{{ \Carbon\Carbon::parse($data['detail']['updated_at'])->toIso8601String() }}">
                        Cập nhật
                        {{ \Carbon\Carbon::parse($data['detail']['updated_at'])->format('d/m/Y') }}
                    </time>
                @endif
            </div>
        </div>
        <div class="rnews-wrapper">
            <h2 class="rnews-heading">Tin liên quan</h2>

            <div class="rnews-grid">
                @if(!empty($data['detail']['related']))
                    @foreach($data['detail']['related'] as $k=>$row)
                        @if($k < 3)
                            <article class="rnews-item">
                                <div class="rnews-thumb-box">
                                    <a href="{{ route('page.post.show', ['slug' => $row['slug']]) }}" class="rnews-thumb-link" title="{{ $row['title'] }}">
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 193 128'%3E%3C/svg%3E"
                                             loading="lazy" decoding="async"
                                             data-src="{{ Helpers::renderThumb($row['thumbnail']) }}" title="{{ $row['title'] }}"
                                             alt="{{ $row['title'] }}"
                                             class="rnews-thumb-img lazy"
                                             width="193"
                                             height="128"
                                             style="aspect-ratio: 1.5;">
                                    </a>
                                </div>
                                <div class="rnews-content-box">
                                    <h3 class="rnews-title">
                                        <a href="{{ route('page.post.show', ['slug' => $row['slug']]) }}" class="rnews-title-link" title="{{ $row['title'] }}">
                                            {{ $row['title'] }}
                                        </a>
                                    </h3>
                                </div>
                            </article>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style type="text/css">
        .article-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px 16px;
            margin: 10px 0 22px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e5e7eb;
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
        }

        .article-meta span,
        .article-meta time {
            display: inline-flex;
            align-items: center;
        }

        .article-meta span + time::before,
        .article-meta time + time::before {
            width: 4px;
            height: 4px;
            margin-right: 16px;
            border-radius: 50%;
            background: #94a3b8;
            content: "";
            flex: 0 0 auto;
        }

        @media (max-width: 575px) {
            .article-meta {
                align-items: flex-start;
                flex-direction: column;
                gap: 5px;
                margin-bottom: 18px;
            }

            .article-meta span + time::before,
            .article-meta time + time::before {
                display: none;
            }
        }

        .toc-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 10px;
            margin: 0px 0 20px 0;
            max-width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .toc-title {
            font-weight: 700;
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .toc-title::before {
            content: "☰";
            margin-right: 10px;
            color: #ff5722;
            font-size: 22px;
            margin-top: -5px;
        }

        ul.toc {
            list-style: none;
            padding: 0 0 0 20px;
            margin: 0;
        }

        li.toc-parent {
            margin-bottom: 10px;
            padding-left: 0px;
            transition: all 0.3s ease;
        }

        li.toc-parent:last-child {
            margin-bottom: 0;
        }

        li.toc-parent a {
            text-decoration: none;
            color: #444;
            font-weight: 500;
            font-size: 15px;
            line-height: 1.5;
            display: block;
            transition: color 0.2s ease;
        }

        li.toc-child {
            padding-left: 15px;
        }

        li.toc-child a {
            font-size: 14px;
        }

        li.toc-parent a:hover {
            color: #ff5722;
            /*padding-left: 5px;*/
        }

        /* Khung bao bọc toàn bộ khối tin liên quan */
        .rnews-wrapper {
            margin: 10px auto 10px auto; /* Tạo khoảng cách rộng với nội dung bài viết phía trên */
        }

        /* Tiêu đề "Tin liên quan" */
        .rnews-heading {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
            position: relative;
        }

        /* Đường gạch đỏ nhỏ dưới chữ giống phong cách báo chí */
        .rnews-heading::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background-color: #d32f2f;
        }

        /* Chia lưới Grid - Mặc định trên PC hiển thị 3 cột */
        .rnews-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Từng item bài viết */
        .rnews-item {
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
        }

        /* Khối ảnh */
        .rnews-thumb-box {
            width: 100%;
            margin-bottom: 10px;
        }

        .rnews-thumb-link {
            display: block;
            overflow: hidden;
            border-radius: 4px;
            aspect-ratio: 3 / 2;
            background-color: #f1f5f9;
        }

        .rnews-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        /* Hiệu ứng phóng to ảnh khi hover vào toàn item */
        .rnews-item:hover .rnews-thumb-img {
            transform: scale(1.05);
        }

        /* Khối chữ bên dưới ảnh */
        .rnews-content-box {
            display: flex;
            flex-direction: column;
        }

        /* Danh mục (Category) */
        .rnews-category {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
            letter-spacing: 0.3px;
        }

        /* Tiêu đề bài viết liên quan */
        .rnews-title {
            margin: 0;
            font-size: 14px;
            line-height: 1.4;
            font-weight: 600;
        }

        .rnews-title-link {
            color: #1e293b;
            text-decoration: none;
            /* Giới hạn tiêu đề tối đa 3 dòng để các khung bằng nhau */
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }

        .rnews-title-link:hover {
            color: #d32f2f;
        }

        /* ==========================================================================
           Responsive cho Mobile và Tablet
           ========================================================================== */
        @media screen and (max-width: 768px) {
            /* Màn hình máy tính bảng: Giảm xuống còn 2 cột */
            .rnews-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }

        @media screen and (max-width: 480px) {
            /* Màn hình điện thoại nhỏ: Chuyển hẳn về cấu trúc danh bạ hàng dọc lnews cho đỡ tốn diện tích cuộn */
            .rnews-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .rnews-item {
                flex-direction: row; /* Xếp ảnh trái, chữ phải trên mobile */
                align-items: center;
                gap: 12px;
                padding-bottom: 12px;
                border-bottom: 1px solid #f1f5f9;
            }

            .rnews-item:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .rnews-thumb-box {
                flex: 0 0 100px; /* Khống chế ảnh nhỏ gọn trên mobile */
                margin-bottom: 0;
            }

            .rnews-title {
                font-size: 15px;
            }

            .rnews-title-link {
                -webkit-line-clamp: 2; /* Mobile thu gọn còn 2 dòng tiêu đề */
            }
        }
    </style>
@endsection

@section('scripts')

@endsection
