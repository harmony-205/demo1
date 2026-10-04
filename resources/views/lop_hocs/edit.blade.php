@extends('layout1')

@section('title', 'Chỉnh sửa lớp học')
@section('description', 'Cập nhật thông tin cho lớp học #' . $lopHoc->id . ' - ' . $lopHoc->ten_lop)

@push('styles')
<style>
    .form-box {
        max-width: 720px;
        background: #fff;
    }
</style>
@endpush

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Đã xảy ra lỗi, vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-box">
        <form action="{{ route('lophocs.update', $lopHoc->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ma_lop" class="form-label font-weight-bold">Mã lớp <span class="text-danger">*</span>:</label>
                    <input type="text" class="form-control" id="ma_lop" name="ma_lop" value="{{ old('ma_lop', $lopHoc->ma_lop) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="ten_lop" class="form-label font-weight-bold">Tên lớp <span class="text-danger">*</span>:</label>
                    <input type="text" class="form-control" id="ten_lop" name="ten_lop" value="{{ old('ten_lop', $lopHoc->ten_lop) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="giao_vien" class="form-label">Giáo viên chủ nhiệm <span class="text-danger">*</span>:</label>
                    <input type="text" class="form-control" id="giao_vien" name="giao_vien" value="{{ old('giao_vien', $lopHoc->giao_vien) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="so_dien_thoai_gvcn" class="form-label">Số điện thoại GVCN:</label>
                    <input type="text" class="form-control" id="so_dien_thoai_gvcn" name="so_dien_thoai_gvcn" value="{{ old('so_dien_thoai_gvcn', $lopHoc->so_dien_thoai_gvcn) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="si_so" class="form-label">Sĩ số <span class="text-danger">*</span>:</label>
                    <input type="number" class="form-control" id="si_so" name="si_so" value="{{ old('si_so', $lopHoc->si_so) }}" min="1" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="trang_thai" class="form-label">Trạng thái:</label>
                    <select id="trang_thai" name="trang_thai" class="form-select" required>
                        <option value="1" {{ old('trang_thai', (string)$lopHoc->trang_thai) === '1' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="0" {{ old('trang_thai', (string)$lopHoc->trang_thai) === '0' ? 'selected' : '' }}>Đã khóa</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="ghi_chu" class="form-label">Ghi chú:</label>
                <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="3">{{ old('ghi_chu', $lopHoc->ghi_chu) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" style="background-color: var(--mau-chinh); border-color: var(--mau-chinh);">
                    Cập nhật lớp học
                </button>
                <a href="{{ route('lophocs.index') }}" class="btn btn-outline-secondary">
                    Quay lại danh sách
                </a>
            </div>
        </form>
    </div>
@endsection
