@extends('layouts.layoutmaster')
@section('title', 'Chỉnh sửa thông tin sinh viên')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Chỉnh sửa thông tin sinh viên</h1>
            <p class="text-muted mb-0">Hồ sơ sinh viên: <strong>{{ $sinhvien->ten }}</strong> ({{ $sinhvien->ma_sv }})</p>
        </div>
        <a href="{{ route('sinhvien.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('sinhvien.update', $sinhvien->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('sinhvien._form', ['sinhvien' => $sinhvien])

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Cập nhật thay đổi
                    </button>
                    <a href="{{ route('sinhvien.index') }}" class="btn btn-light">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
@endsection
