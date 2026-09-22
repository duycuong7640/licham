<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <a class="footer-logo" href="{{ route('page.home') }}" title="Trang chủ">
                <span aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false"><rect x="3" y="4.5" width="18" height="16" rx="2.5"></rect>
                        <path d="M7 2.5v4M17 2.5v4M3 9h18"></path>
                        <path class="footer-logo-date" d="M8 12h3v3H8zM14 12h3v3h-3zM8 17h3v2H8z"></path>
                    </svg>
                </span>
                <b>Lịch Âm Tốt</b>
            </a>
            <p>Tra cứu lịch âm, lịch vạn niên và kiến thức lịch Việt rõ ràng, thuận tiện trên mọi thiết bị.</p>
            <small class="footer-note">Lịch Việt · Múi giờ GMT+7</small>
        </div>
        <nav class="footer-column" aria-label="Tra cứu lịch">
            <h3>Tra cứu</h3>
            <a href="{{ route('page.home') }}" title="Âm lịch hôm nay">Âm lịch hôm nay</a>
            <a href="{{ route('page.cope.show.month', ['month' => \App\Helpers\Helpers::checkNumber(date('m')), 'year' => date('Y')]) }}" title="Lịch theo tháng">Lịch theo tháng</a>
            <a href="{{ route('page.cope.show.year', ['year' => date('Y')]) }}" title="Lịch theo năm">Lịch theo năm</a>
            <a href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">Đổi ngày âm dương</a>
        </nav>
        <nav class="footer-column" aria-label="Kiến thức lịch Việt">
            <h3>Khám phá</h3>
            <a href="{{ route('page.cate.index', ['slug' => 'bai-viet']) }}" title="Bài viết">Bài viết</a>
            @foreach($hashTags as $row)
                <a href="{{ route('page.post.tags', ['slug' => $row['slug']]) }}" data-topic="{{ $row['slug'] }}" title="{{ $row['title'] }}">{{ $row['title'] }}</a>
            @endforeach
        </nav>
    </div>
    <div class="footer-bottom">
        <span>© 2026 Lịch Âm Tốt</span>
        <span>Dữ liệu lịch pháp mang tính tham khảo</span>
    </div>
    <div id="global-loading">
        <div class="global-loading-content">
            <span class="global-loading-spinner"></span>
            <span>Đang xử lý...</span>
        </div>
        <div class="global-loading-progress"></div>
    </div>
</footer>
