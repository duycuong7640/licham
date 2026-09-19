<!DOCTYPE html>
<html lang="vi">
<head>
    @include('pages::elements.extend.meta')
    @include('pages::elements.extend.style')
</head>
<body>
@include('pages::elements.header')
<main>
    <section class="day-detail-page">
        <div class="container">
            @if(!empty($data['hashTags']))
                @include('pages::elements.12congiap')
            @endif
            @if(!empty($data['12CungHoangDao']))
                @include('pages::elements.12cunghoangdao')
            @endif
            <div class="article-layout page-layout day-layout page-layout-custom">
                <div class="content content-left">
                    @yield('content')
                </div>
                <aside class="content-right sidebar">
                    @include('pages::elements.side-right')
                </aside>
            </div>
        </div>
    </section>
</main>
@include('pages::elements.footer')
@include('pages::elements.extend.script')
</body>
</html>
