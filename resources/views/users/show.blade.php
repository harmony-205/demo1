@extends('layouts.layoutmaster')
@section('title', 'Chi tiết người dùng: ' . $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Chi tiết người dùng</h1>
            <p class="text-muted mb-0">Mã ID #{{ $user->id }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Chỉnh sửa
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-3 text-center border-end">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow mb-3" style="width: 90px; height: 90px; font-size: 36px; font-weight: bold;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    @if ($user->isAdmin())
                        <span class="badge bg-danger fs-6 px-3 py-1">👑 Quản trị viên</span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-1">🎓 Giảng viên</span>
                    @endif
                </div>

                <div class="col-md-9 ps-md-4">
                    <h5 class="border-bottom pb-2 mb-3">Thông tin chi tiết</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Số điện thoại:</span>
                            <span class="fw-semibold">{{ $user->so_dien_thoai ?: 'Chưa cập nhật' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Trạng thái:</span>
                            @if ($user->trang_thai)
                                <span class="badge bg-success">Đang hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Bị khóa</span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Ngày tạo tài khoản:</span>
                            <span>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i:s') : '—' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Lần cập nhật cuối:</span>
                            <span>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
