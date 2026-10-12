@extends('pages::layouts.error')

@section('content')
    <section class="error-card" aria-labelledby="error-title">
        <p class="error-code" aria-hidden="true">403</p>
        <span class="error-label">Truy cập bị từ chối</span>
        <h1 id="error-title">Không có quyền xem nội dung này</h1>
        <p>Trang đang được giới hạn quyền truy cập hoặc liên kết này không dành cho tài khoản hiện tại.</p>
        <div class="error-actions">
            <a class="error-home-link" href="{{ route('page.home') }}">Về trang chủ</a>
            <button class="error-back-link" type="button" onclick="history.back()">Quay lại trang trước</button>
        </div>
    </section>
@endsection
