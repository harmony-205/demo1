@extends('layouts.layoutmaster')
@section('title', 'Quản lý menu')

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
            <h1 class="h3 mb-1">Quản lý menu</h1>
            <p class="text-muted mb-0">Cấu hình các liên kết điều hướng trên Header và Sidebar</p>
        </div>
        <a href="{{ route('menu.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Thêm menu
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
    <form action="{{ route('menu.index') }}" method="GET" class="filter-box shadow-sm">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="keyword" class="form-label fw-semibold small">Từ khóa</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="keyword" name="keyword" class="form-control"
                           placeholder="Tên menu, URL, nhóm..." value="{{ $filters['keyword'] ?? '' }}">
                </div>
            </div>

            <div class="col-md-3">
                <label for="vi_tri" class="form-label fw-semibold small">Vị trí hiển thị</label>
                <select id="vi_tri" name="vi_tri" class="form-select">
                    <option value="">-- Tất cả vị trí --</option>
                    @foreach (\App\Models\Menu::VI_TRI as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['vi_tri'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="trang_thai" class="form-label fw-semibold small">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="1" @selected(($filters['trang_thai'] ?? '') === '1')>Hiển thị</option>
                    <option value="0" @selected(($filters['trang_thai'] ?? '') === '0')>Ẩn</option>
                </select>
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
                <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Bảng danh sách menu --}}
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
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'ten', 'direction' => ($sort === 'ten' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Tên menu
                                @if ($sort === 'ten')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'url', 'direction' => ($sort === 'url' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Đường dẫn (URL)
                                @if ($sort === 'url')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'vi_tri', 'direction' => ($sort === 'vi_tri' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Vị trí
                                @if ($sort === 'vi_tri')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nhom', 'direction' => ($sort === 'nhom' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Nhóm
                                @if ($sort === 'nhom')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'thu_tu', 'direction' => ($sort === 'thu_tu' && $direction === 'asc') ? 'desc' : 'asc', 'page' => null]) }}">
                                Thứ tự
                                @if ($sort === 'thu_tu')
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
                        <th style="width: 150px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menus as $m)
                        <tr>
                            <td>#{{ $m->id }}</td>
                            <td><strong class="text-primary">{{ $m->ten }}</strong></td>
                            <td><code>{{ $m->url }}</code></td>
                            <td>
                                @if ($m->vi_tri === 'header')
                                    <span class="badge bg-primary">Header</span>
                                @else
                                    <span class="badge bg-secondary">Sidebar</span>
                                @endif
                            </td>
                            <td>{{ $m->nhom ?: '—' }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $m->thu_tu }}</span></td>
                            <td>
                                @if ($m->trang_thai)
                                    <span class="badge bg-success">Hiển thị</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('menu.edit', $m) }}" class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('menu.destroy', $m) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa menu {{ addslashes($m->ten) }}?')">
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
                                Không tìm thấy menu nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($menus->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                <div class="small text-muted">
                    Hiển thị <strong>{{ $menus->firstItem() }}</strong> đến <strong>{{ $menus->lastItem() }}</strong> trong tổng số <strong>{{ $menus->total() }}</strong> menu
                </div>
                <div>
                    {{ $menus->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
