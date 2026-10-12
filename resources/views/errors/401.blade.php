@extends('pages::layouts.error')

@section('content')
    <section class="error-card" aria-labelledby="error-title">
        <p class="error-code" aria-hidden="true">401</p>
        <span class="error-label">Cần xác thực</span>
        <h1 id="error-title">Phiên truy cập chưa được xác thực</h1>
        <p>Cần đăng nhập hoặc xác thực lại để tiếp tục xem nội dung được bảo vệ.</p>
        <div class="error-actions">
            <a class="error-home-link" href="{{ route('page.home') }}">Về trang chủ</a>
            <button class="error-back-link" type="button" onclick="history.back()">Quay lại trang trước</button>
        </div>
    </section>
@endsection
