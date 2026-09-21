@extends('pages::layouts.app')

@section('content')
    <div class="article-detail-layout">
        <article class="card article-detail">
            <header class="article-detail-head">
                <span class="article-category">LỊCH VIỆT</span>
                <h1>Lịch âm hôm nay có ý nghĩa gì trong đời sống người Việt?</h1>
                <p class="article-lead">
                    Lịch âm không chỉ trả lời hôm nay là ngày bao nhiêu. Đây còn là hệ
                    thống giúp người Việt theo dõi mùa vụ, ngày lễ và những dấu mốc
                    văn hóa truyền thống.
                </p>
                <div class="article-byline">
                    <span>Cập nhật: 07/09/2026</span><span>8 phút đọc</span>
                </div>
            </header>
            <nav class="article-toc" aria-label="Mục lục bài viết">
                <strong>Nội dung chính</strong>
                <a href="#lich-am-la-gi">1. Lịch âm là gì?</a>
                <a href="#thong-tin">2. Những thông tin cần đọc</a>
                <a href="#ung-dung">3. Ứng dụng trong đời sống</a>
            </nav>
            <div class="article-body">
                <p>
                    Lịch âm Việt Nam được xây dựng dựa trên chu kỳ vận động của Mặt
                    Trăng, đồng thời có sự điều chỉnh để phù hợp với chu kỳ mùa của
                    năm Mặt Trời. Vì vậy, lịch Việt thường được gọi chính xác hơn là
                    âm dương lịch.
                </p>
                <h2 id="lich-am-la-gi">Lịch âm là gì?</h2>
                <p>
                    Một tháng âm lịch bắt đầu gần thời điểm trăng non. Mỗi tháng
                    thường có 29 hoặc 30 ngày. Để năm âm lịch không lệch quá xa so với
                    thời tiết và mùa vụ, một số năm được bổ sung tháng nhuận.
                </p>
                <p>
                    Trong đời sống, ngày âm được dùng để xác định Tết Nguyên đán, ngày
                    giỗ, lễ tiết và nhiều sinh hoạt văn hóa gia đình.
                </p>
                <h2 id="thong-tin">Những thông tin nên đọc trên lịch âm hôm nay</h2>
                <p>
                    Người xem nên bắt đầu bằng việc đối chiếu ngày dương, ngày âm và
                    thứ trong tuần. Sau đó có thể đọc Can Chi của ngày, tiết khí, trực
                    ngày, giờ hoàng đạo và tuổi xung nếu cần tham khảo cho một công
                    việc cụ thể.
                </p>
                <blockquote>
                    Ngày hoàng đạo không có nghĩa là mọi công việc đều phù hợp. Mỗi
                    mục đích vẫn cần được đối chiếu với nhóm thông tin liên quan.
                </blockquote>
                <h2 id="ung-dung">Ứng dụng lịch âm trong đời sống hiện đại</h2>
                <p>
                    Lịch âm vẫn có giá trị trong việc gìn giữ phong tục, ghi nhớ các
                    ngày lễ truyền thống và hỗ trợ gia đình sắp xếp công việc. Tuy
                    nhiên, các nhận định tốt xấu nên được sử dụng như nguồn tham khảo
                    văn hóa.
                </p>
                <p>
                    Khi đưa ra quyết định quan trọng, cần kết hợp thêm điều kiện thực
                    tế như sức khỏe, thời tiết, thời gian, tài chính và ý kiến của
                    những người liên quan.
                </p>
            </div>
            <footer class="article-tags">
                <strong>Chủ đề:</strong
                ><a href="bai-viet.html?category=lich-viet"># Lịch Việt</a
                ><a href="bai-viet.html?category=xem-ngay"># Xem ngày</a
                ><a href="bai-viet.html?category=van-hoa"># Văn hóa</a>
            </footer>
        </article>
        <aside class="article-sidebar">
            <section class="card sidebar-panel">
                <span class="section-label">BÀI LIÊN QUAN</span>
                <h2>Đọc tiếp</h2>
                <a href="chi-tiet-bai-viet.html"
                ><strong>Vì sao âm lịch có tháng nhuận?</strong
                    ><small>5 phút đọc</small></a
                >
                <a href="chi-tiet-bai-viet.html"
                ><strong>Cách xem giờ hoàng đạo trong ngày</strong
                    ><small>6 phút đọc</small></a
                >
                <a href="chi-tiet-bai-viet.html"
                ><strong>Can Chi được tính như thế nào?</strong
                    ><small>8 phút đọc</small></a
                >
            </section>
            <section class="card sidebar-action">
                <strong>Tra cứu lịch hôm nay</strong>
                <p>Xem nhanh ngày âm, giờ tốt và thông tin xuất hành.</p>
                <a href="index.html">Mở lịch hôm nay →</a>
            </section>
        </aside>
    </div>
@endsection
