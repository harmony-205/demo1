@extends('layouts.layoutmaster')
@section('title', 'Danh sách lớp học')

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
            <h1 class="h3 mb-1">Quản lý lớp học</h1>
            <p class="text-muted mb-0">Danh sách các lớp học đang phụ trách và đào tạo</p>
        </div>
        <a href="{{ route('lophoc.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Thêm lớp học
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
    <form action="{{ route('lophoc.index') }}" method="GET" class="filter-box shadow-sm">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="keyword" class="form-label fw-semibold small">Từ khóa</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="keyword" name="keyword" class="form-control"
                           placeholder="Tên lớp, mã lớp, giáo viên..." value="{{ $filters['keyword'] ?? '' }}">
                </div>
            </div>

            <div class="col-md-2">
                <label for="trang_thai" class="form-label fw-semibold small">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="1" @selected(($filters['trang_thai'] ?? '') === '1')>Hoạt động</option>
                    <option value="0" @selected(($filters['trang_thai'] ?? '') === '0')>Không hoạt động</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="si_so_min" class="form-label fw-semibold small">Sĩ số từ</label>
                <input type="number" id="si_so_min" name="si_so_min" min="0" class="form-control"
                       placeholder="Min" value="{{ $filters['si_so_min'] ?? '' }}">
            </div>

            <div class="col-md-2">
                <label for="si_so_max" class="form-label fw-semibold small">Sĩ số đến</label>
                <input type="number" id="si_so_max" name="si_so_max" min="0" class="form-control"
                       placeholder="Max" value="{{ $filters['si_so_max'] ?? '' }}">
            </div>

            <div class="col-md-1">
                <label for="perPage" class="form-label fw-semibold small">Hiển thị</label>
                <select id="perPage" name="perPage" class="form-select">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel-fill me-1"></i> Lọc
                </button>
                <a href="{{ route('lophoc.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Bảng danh sách lớp học --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                ID
                                @if ($sort === 'id')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'ten_lop', 'direction' => ($sort === 'ten_lop' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Tên lớp
                                @if ($sort === 'ten_lop')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'ma_lop', 'direction' => ($sort === 'ma_lop' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Mã lớp
                                @if ($sort === 'ma_lop')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'giao_vien', 'direction' => ($sort === 'giao_vien' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Giảng viên
                                @if ($sort === 'giao_vien')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>SĐT GVCN</th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'si_so', 'direction' => ($sort === 'si_so' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Sĩ số
                                @if ($sort === 'si_so')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'trang_thai', 'direction' => ($sort === 'trang_thai' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
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
                    @forelse ($lopHocs as $lopHoc)
                        <tr>
                            <td>#{{ $lopHoc->id }}</td>
                            <td>
                                <a href="{{ route('lophoc.show', $lopHoc) }}" class="fw-bold text-decoration-none">
                                    {{ $lopHoc->ten_lop }}
                                </a>
                                @if ($lopHoc->ghi_chu)
                                    <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $lopHoc->ghi_chu }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border"><code>{{ $lopHoc->ma_lop }}</code></span></td>
                            <td>{{ $lopHoc->giao_vien }}</td>
                            <td>{{ $lopHoc->so_dien_thoai_gvcn ?: '—' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $lopHoc->si_so }}</span></td>
                            <td>
                                @if ($lopHoc->trang_thai)
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('lophoc.show', $lopHoc) }}" class="btn btn-sm btn-outline-info me-1" title="Chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('lophoc.edit', $lopHoc) }}" class="btn btn-sm btn-outline-primary me-1" title="Chỉnh sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('lophoc.destroy', $lopHoc) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa lớp học {{ addslashes($lopHoc->ten_lop) }}?')">
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
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                Không tìm thấy lớp học nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($lopHocs->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                <div class="small text-muted">
                    Hiển thị <strong>{{ $lopHocs->firstItem() }}</strong> đến <strong>{{ $lopHocs->lastItem() }}</strong> trong tổng số <strong>{{ $lopHocs->total() }}</strong> lớp học
                </div>
                <div>
                    {{ $lopHocs->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
