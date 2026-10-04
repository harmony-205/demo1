@extends('layout1')

@section('title', 'Danh sách Menu')
@section('description', 'Quản lý thông tin menu hệ thống, tìm kiếm, lọc và sắp xếp')

@push('styles')
<style>
    .filter-box {
        background-color: #fcfbfe;
        border: 1px solid #e7e2ee;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .table-custom {
        border-collapse: collapse;
        width: 100%;
        background-color: #fff;
    }
    .table-custom th,
    .table-custom td {
        padding: 12px 14px;
        text-align: left;
        border: 1px solid #e9e5f0;
        vertical-align: middle;
    }
    .table-custom th {
        background-color: #f0ebf5;
        color: #3d2b56;
        font-weight: 600;
        white-space: nowrap;
    }
    .table-custom tbody tr:hover {
        background-color: #fbf9fd;
    }
    .sort-link {
        color: #3d2b56;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .sort-link:hover {
        color: #7b4fb5;
        text-decoration: underline;
    }
    .sort-icon {
        font-size: 11px;
        opacity: 0.6;
    }
    .sort-icon.active {
        opacity: 1;
        color: #d97706;
        font-weight: bold;
    }
    .badge-active {
        background-color: #d1fae5;
        color: #065f46;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-locked {
        background-color: #f3f4f6;
        color: #4b5563;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

    {{-- Thông báo flash session --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Thành công!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Lỗi!</strong> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Thanh công cụ trên cùng --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h3 class="h5 mb-0 text-secondary">
            Tổng số: <strong>{{ $menus->total() }}</strong> menu
        </h3>
        <a href="{{ route('menus.create') }}" class="btn btn-primary" style="background-color: var(--mau-chinh); border-color: var(--mau-chinh);">
            + Thêm menu mới
        </a>
    </div>

    {{-- Khung tìm kiếm và bộ lọc --}}
    <div class="filter-box">
        <form action="{{ route('menus.index') }}" method="GET" class="row g-2 align-items-end">
            {{-- Giữ nguyên sắp xếp khi lọc --}}
            <input type="hidden" name="sort_by" value="{{ request('sort_by', $sortBy) }}">
            <input type="hidden" name="sort_direction" value="{{ request('sort_direction', $sortDirection) }}">

            <div class="col-md-5 col-sm-6">
                <label for="keyword" class="form-label mb-1" style="font-size: 13px; font-weight: 600;">Từ khóa tìm kiếm:</label>
                <input type="text" 
                       id="keyword" 
                       name="keyword" 
                       class="form-control form-control-sm" 
                       placeholder="Nhập tên hiển thị, slug..."
                       value="{{ request('keyword') }}">
            </div>

            <div class="col-md-3 col-sm-6">
                <label for="trangthai" class="form-label mb-1" style="font-size: 13px; font-weight: 600;">Trạng thái:</label>
                <select id="trangthai" name="trangthai" class="form-select form-select-sm">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="1" {{ request('trangthai') === '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('trangthai') === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-6">
                <label for="perPage" class="form-label mb-1" style="font-size: 13px; font-weight: 600;">Hiển thị / trang:</label>
                <select id="perPage" name="perPage" class="form-select form-select-sm">
                    <option value="5" {{ request('perPage', 10) == 5 ? 'selected' : '' }}>5 dòng</option>
                    <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10 dòng</option>
                    <option value="20" {{ request('perPage', 10) == 20 ? 'selected' : '' }}>20 dòng</option>
                    <option value="50" {{ request('perPage', 10) == 50 ? 'selected' : '' }}>50 dòng</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-6 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-dark flex-fill" style="background-color: var(--mau-phu); border-color: var(--mau-phu);">
                    Lọc dữ liệu
                </button>
                <a href="{{ route('menus.index') }}" class="btn btn-sm btn-outline-secondary">
                    Đặt lại
                </a>
            </div>
        </form>
    </div>

    @php
        $getSortUrl = function($col) use ($sortBy, $sortDirection) {
            $nextDirection = ($sortBy === $col && $sortDirection === 'asc') ? 'desc' : 'asc';
            return route('menus.index', array_merge(request()->query(), [
                'sort_by' => $col,
                'sort_direction' => $nextDirection
            ]));
        };
    @endphp

    {{-- Bảng hiển thị danh sách menu --}}
    <div class="table-responsive mb-3">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 80px;">
                        <a href="{{ $getSortUrl('id') }}" class="sort-link">
                            ID 
                            @if($sortBy === 'id')
                                <span class="sort-icon active">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @else
                                <span class="sort-icon">⇅</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ $getSortUrl('tenhienthi') }}" class="sort-link">
                            Tên hiển thị
                            @if($sortBy === 'tenhienthi')
                                <span class="sort-icon active">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @else
                                <span class="sort-icon">⇅</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ $getSortUrl('slug') }}" class="sort-link">
                            Slug (Đường dẫn tĩnh)
                            @if($sortBy === 'slug')
                                <span class="sort-icon active">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @else
                                <span class="sort-icon">⇅</span>
                            @endif
                        </a>
                    </th>
                    <th style="width: 160px;">
                        <a href="{{ $getSortUrl('trangthai') }}" class="sort-link">
                            Trạng thái
                            @if($sortBy === 'trangthai')
                                <span class="sort-icon active">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @else
                                <span class="sort-icon">⇅</span>
                            @endif
                        </a>
                    </th>
                    <th style="width: 140px; text-align: center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menus as $menu)
                    <tr>
                        <td><strong>#{{ $menu->id }}</strong></td>
                        <td>
                            <strong>{{ $menu->tenhienthi }}</strong>
                        </td>
                        <td>
                            <code>{{ $menu->slug }}</code>
                        </td>
                        <td>
                            @if($menu->trangthai)
                                <span class="badge-active">Hiển thị</span>
                            @else
                                <span class="badge-locked">Ẩn</span>
                            @endif
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 13px;">
                                Sửa
                            </a>
                            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-danger py-0 px-2" 
                                        style="font-size: 13px;"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa menu [{{ $menu->tenhienthi }}] không?')">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <em>Không tìm thấy menu nào phù hợp với điều kiện tìm kiếm.</em>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang và thống kê --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <div class="text-muted" style="font-size: 13px;">
            @if($menus->total() > 0)
                Hiển thị từ <strong>{{ $menus->firstItem() }}</strong> đến <strong>{{ $menus->lastItem() }}</strong> trên tổng số <strong>{{ $menus->total() }}</strong> menu
            @else
                Hiển thị 0 kết quả
            @endif
        </div>
        <div>
            {{ $menus->links() }}
        </div>
    </div>

@endsection
