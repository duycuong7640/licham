<nav class="main-nav" aria-label="Điều hướng chính">
    <a class="@if(!empty($data['isPage']) && in_array($data['isPage'], ['home', 'day'])) active @endif nav-link btn-loading" href="{{ route('page.home') }}" title="Âm lịch hôm nay">Âm lịch hôm nay</a>
    <div class="nav-item @if(!empty($data['isPage']) && in_array($data['isPage'], ['month'])) active @endif">
        <button class="nav-trigger" type="button" aria-expanded="false">
            Lịch tháng
            <span aria-hidden="true">
                <svg class="ann-ui-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6"></path></svg>
            </span>
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
    <div class="nav-item @if(!empty($data['isPage']) && in_array($data['isPage'], ['year'])) active @endif">
        <button class="nav-trigger" type="button" aria-expanded="false">
            Lịch năm
            <span aria-hidden="true">
                <svg class="ann-ui-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6"></path></svg>
            </span>
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
    <a class="nav-link @if(!empty($data['isPage']) && in_array($data['isPage'], ['fixed'])) active @endif btn-loading" href="{{ route('page.cate.index', ['slug' => 'doi-ngay-am-duong']) }}" title="Đổi ngày âm dương">Đổi ngày âm dương</a>
    <a class="nav-link @if(!empty($data['isPage']) && in_array($data['isPage'], ['posts'])) active @endif btn-loading" href="{{ route('page.cate.index', ['slug' => 'bai-viet']) }}" title="Bài viết">Bài viết</a>
</nav>
