@extends('layouts.layoutmaster')
@section('title', 'Danh sách sinh viên')

@push('styles')
    <style>
        .filter-box {
            background: #fafcff;
            border: 1px solid #e1e8f0;
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 20px;
        }
        th a {
            color: inherit;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        th a:hover {
            color: var(--c-accent);
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Quản lý sinh viên</h1>
            <p class="text-muted mb-0">Danh sách sinh viên các khoa và lớp học</p>
        </div>
        <a href="{{ route('sinhvien.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Thêm sinh viên
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Bộ lọc & Tìm kiếm --}}
    <form action="{{ route('sinhvien.index') }}" method="GET" class="filter-box shadow-sm">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="keyword" class="form-label fw-semibold small">Từ khóa</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="keyword" name="keyword" class="form-control"
                           placeholder="Tên, mã SV, email, địa chỉ..." value="{{ $filters['keyword'] ?? '' }}">
                </div>
            </div>

            <div class="col-md-3">
                <label for="lop_hoc_id" class="form-label fw-semibold small">Lớp học</label>
                <select id="lop_hoc_id" name="lop_hoc_id" class="form-select">
                    <option value="">-- Tất cả lớp học --</option>
                    @foreach ($lopHocs as $lop)
                        <option value="{{ $lop->id }}" @selected(($filters['lop_hoc_id'] ?? '') == $lop->id)>
                            {{ $lop->ten_lop }} ({{ $lop->ma_lop }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="gioi_tinh" class="form-label fw-semibold small">Giới tính</label>
                <select id="gioi_tinh" name="gioi_tinh" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="Nam" @selected(($filters['gioi_tinh'] ?? '') === 'Nam')>Nam</option>
                    <option value="Nữ" @selected(($filters['gioi_tinh'] ?? '') === 'Nữ')>Nữ</option>
                    <option value="Khác" @selected(($filters['gioi_tinh'] ?? '') === 'Khác')>Khác</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="trang_thai" class="form-label fw-semibold small">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="1" @selected(($filters['trang_thai'] ?? '') === '1')>Đang học</option>
                    <option value="0" @selected(($filters['trang_thai'] ?? '') === '0')>Nghỉ học</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel-fill me-1"></i> Lọc
                </button>
                <a href="{{ route('sinhvien.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>

            <div class="col-md-2">
                <label for="tuoi_min" class="form-label fw-semibold small">Tuổi từ</label>
                <input type="number" id="tuoi_min" name="tuoi_min" class="form-control"
                       placeholder="Min" value="{{ $filters['tuoi_min'] ?? '' }}">
            </div>

            <div class="col-md-2">
                <label for="tuoi_max" class="form-label fw-semibold small">Tuổi đến</label>
                <input type="number" id="tuoi_max" name="tuoi_max" class="form-control"
                       placeholder="Max" value="{{ $filters['tuoi_max'] ?? '' }}">
            </div>

            <div class="col-md-2">
                <label for="perPage" class="form-label fw-semibold small">Hiển thị / trang</label>
                <select id="perPage" name="perPage" class="form-select">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }} dòng</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    {{-- Bảng danh sách sinh viên --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">
                            <a href="{{ route('sinhvien.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                ID
                                @if ($sort === 'id')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('sinhvien.index', array_merge(request()->query(), ['sort' => 'ma_sv', 'direction' => ($sort === 'ma_sv' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Mã SV
                                @if ($sort === 'ma_sv')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('sinhvien.index', array_merge(request()->query(), ['sort' => 'ten', 'direction' => ($sort === 'ten' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Họ và tên
                                @if ($sort === 'ten')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('sinhvien.index', array_merge(request()->query(), ['sort' => 'tuoi', 'direction' => ($sort === 'tuoi' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Tuổi
                                @if ($sort === 'tuoi')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Giới tính</th>
                        <th>
                            <a href="{{ route('sinhvien.index', array_merge(request()->query(), ['sort' => 'lop_hoc_id', 'direction' => ($sort === 'lop_hoc_id' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Lớp học
                                @if ($sort === 'lop_hoc_id')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Địa chỉ</th>
                        <th>
                            <a href="{{ route('sinhvien.index', array_merge(request()->query(), ['sort' => 'trang_thai', 'direction' => ($sort === 'trang_thai' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Trạng thái
                                @if ($sort === 'trang_thai')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th style="width: 170px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sinhviens as $sv)
                        <tr>
                            <td>#{{ $sv->id }}</td>
                            <td><span class="badge bg-light text-dark border"><code>{{ $sv->ma_sv }}</code></span></td>
                            <td>
                                <a href="{{ route('sinhvien.show', $sv->id) }}" class="fw-bold text-decoration-none">
                                    {{ $sv->ten }}
                                </a>
                                @if ($sv->email)
                                    <small class="text-muted d-block">{{ $sv->email }}</small>
                                @endif
                            </td>
                            <td>{{ $sv->tuoi }}</td>
                            <td>
                                @if ($sv->gioi_tinh === 'Nam')
                                    <span class="text-primary"><i class="bi bi-gender-male"></i> Nam</span>
                                @elseif ($sv->gioi_tinh === 'Nữ')
                                    <span class="text-danger"><i class="bi bi-gender-female"></i> Nữ</span>
                                @else
                                    <span class="text-secondary">{{ $sv->gioi_tinh }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($sv->lopHoc)
                                    <a href="{{ route('lophoc.show', $sv->lopHoc) }}" class="badge bg-primary-subtle text-primary border border-primary text-decoration-none">
                                        {{ $sv->lopHoc->ten_lop }}
                                    </a>
                                @else
                                    <span class="text-muted small">Chưa xếp lớp</span>
                                @endif
                            </td>
                            <td><small>{{ $sv->dia_chi ?: '—' }}</small></td>
                            <td>
                                @if ($sv->trang_thai)
                                    <span class="badge bg-success">Đang học</span>
                                @else
                                    <span class="badge bg-secondary">Nghỉ học</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('sinhvien.show', $sv->id) }}" class="btn btn-sm btn-outline-info me-1" title="Chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('sinhvien.edit', $sv->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Chỉnh sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('sinhvien.destroy', $sv->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa sinh viên {{ addslashes($sv->ten) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                Không tìm thấy sinh viên nào phù hợp với điều kiện tìm kiếm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sinhviens->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                <div class="small text-muted">
                    Hiển thị <strong>{{ $sinhviens->firstItem() }}</strong> đến <strong>{{ $sinhviens->lastItem() }}</strong> trong tổng số <strong>{{ $sinhviens->total() }}</strong> sinh viên
                </div>
                <div>
                    {{ $sinhviens->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection