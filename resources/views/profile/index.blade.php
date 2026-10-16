@extends('layouts.layoutmaster')
@section('title', 'Thông tin tài khoản cá nhân')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Thông tin tài khoản</h1>
            <p class="text-muted mb-0">Quản lý và cập nhật thông tin cá nhân của bạn</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Thẻ tóm tắt thông tin tài khoản --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 text-center p-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow" style="width: 80px; height: 80px; font-size: 32px; font-weight: bold;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <div>
                    @if ($user->isAdmin())
                        <span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-shield-check me-1"></i> Quản trị viên</span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-person-workspace me-1"></i> Giảng viên</span>
                    @endif
                </div>

                <hr class="my-4">

                <div class="text-start">
                    <div class="mb-2">
                        <small class="text-muted d-block">Số điện thoại</small>
                        <span class="fw-semibold">{{ $user->so_dien_thoai ?: 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Trạng thái tài khoản</small>
                        <span class="badge bg-success">Đang hoạt động</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Ngày tham gia hệ thống</small>
                        <span>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Các form chỉnh sửa --}}
        <div class="col-lg-8">
            {{-- Form cập nhật thông tin chung --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Cập nhật thông tin</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="so_dien_thoai" class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" id="so_dien_thoai" name="so_dien_thoai"
                                   class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                   value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}"
                                   placeholder="0912345678">
                            @error('so_dien_thoai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Lưu thay đổi
                        </button>
                    </form>
                </div>
            </div>

            {{-- Form đổi mật khẩu --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-key-fill me-2 text-warning"></i>Đổi mật khẩu</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                            <input type="password" id="current_password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
