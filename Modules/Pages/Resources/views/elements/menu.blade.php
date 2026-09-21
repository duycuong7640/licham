<nav class="main-nav" aria-label="Điều hướng chính">
    <a class="active nav-link btn-loading" href="{{ route('page.home') }}" title="Âm lịch hôm nay">Âm lịch hôm nay</a>
    <div class="nav-item">
        <button class="nav-trigger" type="button" aria-expanded="false">
            Lịch tháng <span aria-hidden="true">⌄</span>
        </button>
        <div class="nav-dropdown month-dropdown">
            <div class="nav-dropdown-title">Xem lịch âm theo tháng</div>
            <div class="nav-dropdown-grid month-grid" data-month-menu="">
                @for($i = 1; $i <= 12; $i ++)
                    <a class="btn-loading" href="{{ route('page.cope.show.month', ['month' => \App\Helpers\Helpers::checkNumber($i), 'year' => date('Y')]) }}">Tháng {{ $i }}</a>
                @endfor
            </div>
        </div>
    </div>
    <div class="nav-item">
        <button class="nav-trigger" type="button" aria-expanded="false">
            Lịch năm <span aria-hidden="true">⌄</span>
        </button>
        <div class="nav-dropdown year-dropdown">
            <div class="nav-dropdown-title">Lịch âm từ năm nay đến 2050</div>
            <div class="nav-dropdown-grid year-grid" data-year-menu="">
                @php
                    $year = date('Y');
                    $start = $year - 15;
                    $end = $year + 9;
                @endphp
                @for($i = $start; $i <= $end; $i ++)
                    <a class="btn-loading" href="{{ route('page.cope.show.year', ['year' => $i]) }}">{{ $i }}</a>
                @endfor
            </div>
        </div>
    </div>
    <a class="nav-link btn-loading" href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}">Đổi ngày âm dương</a>
    <a class="nav-link btn-loading" href="{{ route('page.cate.index', ['slug' => 'bai-viet']) }}">Bài viết</a>
</nav>
