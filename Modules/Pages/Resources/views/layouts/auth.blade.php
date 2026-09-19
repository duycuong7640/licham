<!DOCTYPE html>
<html lang="vi">
<head>
    @include('pages::elements.extend.meta')
    @include('pages::elements.extend.style')
</head>
<body>
@include('pages::elements.header')
<section class="main-width">
    @yield('content')
</section>
@include('pages::elements.footer')
@include('pages::elements.extend.script')
<script type="text/javascript" src="{{ url('/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
</body>
</html>
