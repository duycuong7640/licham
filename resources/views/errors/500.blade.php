@extends('pages::layouts.error')

@section('content')
    <section class="error-card" aria-labelledby="error-title">
        <p class="error-code" aria-hidden="true">500</p>
        <span class="error-label">Lỗi hệ thống</span>
        <h1 id="error-title">Hệ thống tạm thời chưa thể xử lý</h1>
        <p>Đã có sự cố trong quá trình tải dữ liệu. Vui lòng thử lại sau ít phút.</p>
        <div class="error-actions">
            <a class="error-home-link" href="{{ route('page.home') }}">Về trang chủ</a>
            <button class="error-back-link" type="button" onclick="location.reload()">Thử tải lại</button>
        </div>
    </section>
@endsection
