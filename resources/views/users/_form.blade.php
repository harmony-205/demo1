@php($user = $user ?? null)

<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name ?? '') }}"
               placeholder="Nguyễn Văn A" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
        <input type="email" id="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email ?? '') }}"
               placeholder="example@st.huce.edu.vn" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label fw-semibold">
            Mật khẩu {{ $user ? '(Để trống nếu không muốn đổi)' : '' }} <span class="{{ $user ? '' : 'text-danger' }}">{{ $user ? '' : '*' }}</span>
        </label>
        <input type="password" id="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="{{ $user ? 'Nhập mật khẩu mới...' : 'Nhập mật khẩu (tối thiểu 6 ký tự)' }}"
               {{ $user ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="role" class="form-label fw-semibold">Vai trò người dùng <span class="text-danger">*</span></label>
        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">-- Chọn vai trò --</option>
            @foreach ($roles as $key => $label)
                <option value="{{ $key }}" @selected(old('role', $user->role ?? '') === $key)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="so_dien_thoai" class="form-label fw-semibold">Số điện thoại</label>
        <input type="text" id="so_dien_thoai" name="so_dien_thoai"
               class="form-control @error('so_dien_thoai') is-invalid @enderror"
               value="{{ old('so_dien_thoai', $user->so_dien_thoai ?? '') }}"
               placeholder="0912345678">
        @error('so_dien_thoai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 d-flex align-items-center">
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai" value="1"
                   @checked(old('trang_thai', $user->trang_thai ?? true))>
            <label class="form-check-label fw-semibold" for="trang_thai">
                Kích hoạt tài khoản (Được phép đăng nhập)
            </label>
        </div>
    </div>
</div>
