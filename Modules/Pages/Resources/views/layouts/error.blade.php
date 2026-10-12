<!DOCTYPE html>
<html lang="vi">
<head>
    @include('pages::elements.extend.meta')
    @include('pages::elements.extend.style')
</head>
<body>
@include('pages::elements.header')
<main class="error-main">
    @yield('content')
</main>
@include('pages::elements.footer')
@include('pages::elements.extend.script')
</body>
</html>
