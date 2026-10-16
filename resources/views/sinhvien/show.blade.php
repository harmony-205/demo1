@extends('layouts.layoutmaster')
@section('title', 'Hồ sơ sinh viên: ' . $sinhvien->ten)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Hồ sơ sinh viên</h1>
            <p class="text-muted mb-0">Mã sinh viên: <strong>{{ $sinhvien->ma_sv }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sinhvien.edit', $sinhvien->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Chỉnh sửa
            </a>
            <a href="{{ route('sinhvien.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-3 text-center border-end">
                    <div class="d-inline-flex align-items-center justify-content-center bg-info text-white rounded-circle shadow mb-3" style="width: 90px; height: 90px; font-size: 36px; font-weight: bold;">
                        {{ strtoupper(substr($sinhvien->ten, strrpos($sinhvien->ten, ' ') !== false ? strrpos($sinhvien->ten, ' ') + 1 : 0, 1)) }}
                    </div>
                    <h4>{{ $sinhvien->ten }}</h4>
                    <p class="text-muted mb-2"><code>{{ $sinhvien->ma_sv }}</code></p>
                    @if ($sinhvien->trang_thai)
                        <span class="badge bg-success fs-6 px-3 py-1">Đang theo học</span>
                    @else
                        <span class="badge bg-secondary fs-6 px-3 py-1">Đã nghỉ học</span>
                    @endif
                </div>

                <div class="col-md-9 ps-md-4">
                    <h5 class="border-bottom pb-2 mb-3">Thông tin cá nhân & học tập</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Lớp học hiện tại:</span>
                            @if ($sinhvien->lopHoc)
                                <a href="{{ route('lophoc.show', $sinhvien->lopHoc) }}" class="fw-bold text-decoration-none">
                                    {{ $sinhvien->lopHoc->ten_lop }} ({{ $sinhvien->lopHoc->ma_lop }})
                                </a>
                            @else
                                <span class="text-muted">Chưa phân lớp</span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Giới tính:</span>
                            <span class="fw-semibold">{{ $sinhvien->gioi_tinh }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Tuổi:</span>
                            <span class="fw-semibold">{{ $sinhvien->tuoi }} tuổi</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Ngày sinh:</span>
                            <span>{{ $sinhvien->ngay_sinh ? $sinhvien->ngay_sinh->format('d/m/Y') : '—' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Email:</span>
                            <span>{{ $sinhvien->email ?: '—' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Số điện thoại:</span>
                            <span>{{ $sinhvien->so_dien_thoai ?: '—' }}</span>
                        </div>
                        <div class="col-sm-12">
                            <span class="text-muted d-block small">Địa chỉ / Quê quán:</span>
                            <span>{{ $sinhvien->dia_chi ?: '—' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Ngày tạo hồ sơ:</span>
                            <small class="text-muted">{{ $sinhvien->created_at ? $sinhvien->created_at->format('d/m/Y H:i') : '—' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
