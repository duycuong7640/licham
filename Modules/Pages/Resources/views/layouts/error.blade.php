<!DOCTYPE html>
<html lang="vi">
<head>
    @include('pages::elements.extend.meta')
    @include('pages::elements.extend.style')
</head>
<body>
@include('pages::elements.header')
<main>
    <section class="hero">
        <div class="container">
            <div class="article-layout page-layout">
                @yield('content')
            </div>
        </div>
    </section>
</main>
@include('pages::elements.footer')
@include('pages::elements.extend.script')
</body>
</html>
