@extends('layouts.layoutmaster')
@section('title', 'Quản lý người dùng')

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
            <h1 class="h3 mb-1">Quản lý người dùng</h1>
            <p class="text-muted mb-0">Quản trị danh sách tài khoản giảng viên và quản trị viên</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Thêm người dùng
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
    <form action="{{ route('users.index') }}" method="GET" class="filter-box shadow-sm">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="keyword" class="form-label fw-semibold small">Từ khóa</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="keyword" name="keyword" class="form-control"
                           placeholder="Tên, email, số điện thoại..." value="{{ $filters['keyword'] ?? '' }}">
                </div>
            </div>

            <div class="col-md-3">
                <label for="role" class="form-label fw-semibold small">Vai trò</label>
                <select id="role" name="role" class="form-select">
                    <option value="">-- Tất cả vai trò --</option>
                    @foreach ($roles as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['role'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="trang_thai" class="form-label fw-semibold small">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="1" @selected(($filters['trang_thai'] ?? '') === '1')>Hoạt động</option>
                    <option value="0" @selected(($filters['trang_thai'] ?? '') === '0')>Đã khóa</option>
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
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </div>
    </form>

    {{-- Bảng danh sách người dùng --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">
                            <a href="{{ route('users.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                ID
                                @if ($sort === 'id')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('users.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Họ và tên
                                @if ($sort === 'name')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('users.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => ($sort === 'email' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Email
                                @if ($sort === 'email')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Số điện thoại</th>
                        <th>
                            <a href="{{ route('users.index', array_merge(request()->query(), ['sort' => 'role', 'direction' => ($sort === 'role' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Vai trò
                                @if ($sort === 'role')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Trạng thái</th>
                        <th>
                            <a href="{{ route('users.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
                                Ngày tạo
                                @if ($sort === 'created_at')
                                    <i class="bi bi-arrow-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th style="width: 170px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light border text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                    @if (auth()->id() === $user->id)
                                        <span class="badge bg-info text-dark" style="font-size: 10px;">Bạn</span>
                                    @endif
                                </div>
                            </td>
                            <td><code>{{ $user->email }}</code></td>
                            <td>{{ $user->so_dien_thoai ?: '—' }}</td>
                            <td>
                                @if ($user->isAdmin())
                                    <span class="badge bg-danger">Quản trị viên</span>
                                @else
                                    <span class="badge bg-success">Giảng viên</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->trang_thai)
                                    <span class="badge bg-success-subtle text-success border border-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary">Đã khóa</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info me-1" title="Chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1" title="Chỉnh sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if (auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                Không tìm thấy người dùng nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                <div class="small text-muted">
                    Hiển thị <strong>{{ $users->firstItem() }}</strong> đến <strong>{{ $users->lastItem() }}</strong> trong tổng số <strong>{{ $users->total() }}</strong> kết quả
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
