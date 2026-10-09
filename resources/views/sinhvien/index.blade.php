@extends('layouts.layoutmaster')
@section('title', 'Danh sách sinh viên')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Danh sách sinh viên</h1>
        <a href="{{ route('sinhvien.create') }}" class="btn btn-primary">
            + Thêm sinh viên
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Tên sinh viên</th>
                    <th style="width: 80px;">Tuổi</th>
                    <th>Địa chỉ</th>
                    <th style="width: 200px;" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sinhviens as $sinhvien)
                    <tr>
                        <td>{{ $sinhvien['id'] }}</td>
                        <td>
                            <a href="{{ route('sinhvien.show', $sinhvien['id']) }}" class="fw-semibold text-decoration-none">
                                {{ $sinhvien['ten'] ?? $sinhvien['name'] }}
                            </a>
                        </td>
                        <td>{{ $sinhvien['tuoi'] ?? $sinhvien['age'] }}</td>
                        <td>{{ $sinhvien['diachi'] ?: '—' }}</td>
                        <td class="text-center">
                            <a href="{{ route('sinhvien.show', $sinhvien['id']) }}"
                               class="btn btn-sm btn-outline-info me-1" title="Xem chi tiết">Xem</a>
                            <a href="{{ route('sinhvien.edit', $sinhvien['id']) }}"
                               class="btn btn-sm btn-outline-primary me-1" title="Chỉnh sửa">Sửa</a>
                            @php $tenRow = addslashes($sinhvien['ten'] ?? $sinhvien['name']); @endphp
                            <form action="{{ route('sinhvien.destroy', $sinhvien['id']) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Xóa sinh viên {{ $tenRow }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Chưa có sinh viên nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 text-muted small">
        Tổng: <strong>{{ count($sinhviens) }}</strong> sinh viên
    </div>

    {{-- Nút reset session về dữ liệu mẫu --}}
    <div class="mt-4">
        <form action="{{ route('sinhvien.reset') }}" method="POST"
              onsubmit="return confirm('Reset về dữ liệu mẫu ban đầu?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary">↺ Reset dữ liệu mẫu</button>
        </form>
    </div>
@endsection