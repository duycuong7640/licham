@extends('pages::layouts.error')

@section('content')
    <section class="error-card" aria-labelledby="error-title">
        <p class="error-code" aria-hidden="true">404</p>
        <span class="error-label">Không tìm thấy trang</span>
        <h1 id="error-title">Trang cần tìm không tồn tại</h1>
        <p>Đường dẫn có thể đã thay đổi, bị nhập sai hoặc nội dung không còn được cung cấp.</p>
        <div class="error-actions">
            <a class="error-home-link" href="{{ route('page.home') }}">Về trang chủ</a>
            <button class="error-back-link" type="button" onclick="history.back()">Quay lại trang trước</button>
        </div>
    </section>
@endsection
