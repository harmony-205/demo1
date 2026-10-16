@extends('layouts.layoutmaster')
@section('title', 'Chi tiết lớp học: ' . $lopHoc->ten_lop)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Chi tiết lớp học</h1>
            <p class="text-muted mb-0">Mã lớp: <strong>{{ $lopHoc->ma_lop }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('lophoc.edit', $lopHoc) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Chỉnh sửa
            </a>
            <a href="{{ route('lophoc.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-info-circle text-primary me-2"></i>Thông tin lớp học</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr><th style="width: 180px" class="bg-light">Tên lớp</th><td><strong class="text-primary">{{ $lopHoc->ten_lop }}</strong></td></tr>
                        <tr><th class="bg-light">Mã lớp</th><td><code>{{ $lopHoc->ma_lop }}</code></td></tr>
                        <tr><th class="bg-light">Giảng viên / GVCN</th><td>{{ $lopHoc->giao_vien }}</td></tr>
                        <tr><th class="bg-light">SĐT GVCN</th><td>{{ $lopHoc->so_dien_thoai_gvcn ?: '—' }}</td></tr>
                        <tr><th class="bg-light">Sĩ số quy định</th><td>{{ $lopHoc->si_so }} sinh viên</td></tr>
                        <tr><th class="bg-light">Số lượng thực tế</th><td><span class="badge bg-info text-dark">{{ $lopHoc->sinhViens->count() }} sinh viên</span></td></tr>
                        <tr><th class="bg-light">Trạng thái</th><td>
                            @if ($lopHoc->trang_thai)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-secondary">Không hoạt động</span>
                            @endif
                        </td></tr>
                        <tr><th class="bg-light">Ghi chú</th><td>{{ $lopHoc->ghi_chu ?: 'Không có' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-people text-success me-2"></i>Sinh viên trong lớp ({{ $lopHoc->sinhViens->count() }})</h5>
                    <a href="{{ route('sinhvien.create') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus"></i> Thêm SV
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Mã SV</th>
                                    <th>Họ và tên</th>
                                    <th>Giới tính</th>
                                    <th>Tuổi</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lopHoc->sinhViens as $sv)
                                    <tr>
                                        <td><code>{{ $sv->ma_sv }}</code></td>
                                        <td>
                                            <a href="{{ route('sinhvien.show', $sv->id) }}" class="fw-semibold text-decoration-none">
                                                {{ $sv->ten }}
                                            </a>
                                        </td>
                                        <td>{{ $sv->gioi_tinh }}</td>
                                        <td>{{ $sv->tuoi }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('sinhvien.show', $sv->id) }}" class="btn btn-xs btn-outline-info" title="Xem">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Chưa có sinh viên nào trong lớp này.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
