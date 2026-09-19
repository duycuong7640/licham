<div class="topbar">
    <div class="container topbar__inner">
        @php
            $mnLaso = \App\Helpers\Helpers::findByType('HOROSCOPE_LAS_SO');
            $mnThanSoHoc = \App\Helpers\Helpers::findByType('NUMEROLOGY_SEARCH');
        @endphp
        <div class="topbar__links">
            <a href="{{ route('page.cate.index', ['slug' => $mnLaso['slug']]) }}" title="{{ $mnLaso['title'] }}" style="text-transform: capitalize;">{{ $mnLaso['title'] }}</a>
            <a href="{{ route('page.cate.index', ['slug' => $mnThanSoHoc['slug']]) }}" title="{{ $mnThanSoHoc['title'] }}" style="text-transform: capitalize;">Thần số học</a>
        </div>
        <div class="topbar__tools">
            <button class="date-pill" id="birthButton" type="button">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <span id="birthButtonText">Cài đặt ngày sinh</span>
            </button>
            <input class="search" type="search" placeholder="Tìm kiếm...">
            <button class="login" id="loginButton" type="button">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                Đăng nhập
            </button>
        </div>
    </div>
</div>

<div class="popover" id="birthPopover" aria-hidden="true">
    <div class="popover__panel" role="dialog" aria-labelledby="birthTitle">
        <button class="popover-close" type="button" id="birthClose" aria-label="Đóng">×</button>
        <div class="modal-title" id="birthTitle">Cài đặt ngày sinh</div>
        <p>Chọn ngày sinh để cá nhân hóa tử vi, ngày tốt và gợi ý phong thủy.</p>
        <form class="birth-form" id="birthForm">
            <div class="birth-field">
                <label for="birthDateInput">Ngày sinh dương lịch</label>
                <div class="birth-date-control">
                    <input id="birthDateInput" type="text" inputmode="numeric" maxlength="10" pattern="\d{2}-\d{2}-\d{4}" data-date-dmy value="04-05-1996" title="Nhập ngày theo định dạng dd-mm-yyyy, năm 1900-2050" autocomplete="off">
                    <button class="birth-date-toggle" id="birthDateToggle" type="button" aria-label="Mở lịch chọn ngày" aria-expanded="false" aria-controls="birthDatePicker">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </button>
                </div>
                <div class="birth-date-picker" id="birthDatePicker" aria-label="Chọn ngày sinh"></div>
            </div>
            <div class="birth-grid">
                <div class="birth-field">
                    <label for="birthTimeInput">Giờ sinh</label>
                    <select id="birthTimeInput">
                        <option>Không rõ</option>
                        <option>Tý 23:00 - 01:00</option>
                        <option>Sửu 01:00 - 03:00</option>
                        <option>Dần 03:00 - 05:00</option>
                        <option>Mão 05:00 - 07:00</option>
                        <option>Thìn 07:00 - 09:00</option>
                        <option>Tỵ 09:00 - 11:00</option>
                        <option>Ngọ 11:00 - 13:00</option>
                        <option>Mùi 13:00 - 15:00</option>
                        <option>Thân 15:00 - 17:00</option>
                        <option>Dậu 17:00 - 19:00</option>
                        <option>Tuất 19:00 - 21:00</option>
                        <option>Hợi 21:00 - 23:00</option>
                    </select>
                </div>
                <div class="birth-field">
                    <label for="birthGenderInput">Giới tính</label>
                    <select id="birthGenderInput">
                        <option>Không chọn</option>
                        <option>Nam</option>
                        <option>Nữ</option>
                    </select>
                </div>
            </div>
            <div class="birth-actions">
                <button class="ghost-btn" type="button" id="birthCancel">Hủy</button>
                <button class="primary-btn" type="submit">Lưu ngày sinh</button>
            </div>
        </form>
    </div>
</div>

<div class="auth-modal" id="authModal" aria-hidden="true">
    <div class="auth-card" role="dialog" aria-labelledby="authTitle">
        <button class="auth-close" id="authClose" type="button" aria-label="Đóng">×</button>
        <div class="modal-title" id="authTitle">Chào mừng trở lại</div>
        <p>Đăng nhập để lưu ngày sinh, theo dõi tử vi và nhận gợi ý cá nhân hóa.</p>
        <div class="auth-tabs">
            <button class="active" type="button" data-auth-tab="login">Đăng nhập</button>
            <button type="button" data-auth-tab="register">Đăng ký</button>
        </div>
        <form class="auth-form" id="authForm">
            <input type="text" name="username" placeholder="Tên đăng nhập hoặc email" autocomplete="username">
            <input type="password" name="password" placeholder="Mật khẩu" autocomplete="current-password">
            <input class="register-only" type="password" name="confirm" placeholder="Nhập lại mật khẩu" autocomplete="new-password" hidden>
            <div class="auth-extra">
                <label><input class="auth-remember-input" type="checkbox"> Ghi nhớ</label>
                <a href="#">Quên mật khẩu?</a>
            </div>
            <button class="primary-btn" type="submit" id="authSubmit">Đăng nhập</button>
        </form>
    </div>
</div>
