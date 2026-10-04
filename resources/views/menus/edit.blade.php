@extends('layout1')

@section('title', 'Chỉnh sửa menu')
@section('description', 'Cập nhật thông tin cho menu #' . $menu->id . ' - ' . $menu->tenhienthi)

@push('styles')
<style>
    .form-box {
        max-width: 640px;
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
        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="tenhienthi" class="form-label font-weight-bold">Tên hiển thị <span class="text-danger">*</span>:</label>
                <input type="text" 
                       class="form-control" 
                       id="tenhienthi" 
                       name="tenhienthi" 
                       value="{{ old('tenhienthi', $menu->tenhienthi) }}" 
                       required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label font-weight-bold">Slug (Đường dẫn tĩnh) <span class="text-danger">*</span>:</label>
                <input type="text" 
                       class="form-control" 
                       id="slug" 
                       name="slug" 
                       value="{{ old('slug', $menu->slug) }}" 
                       required>
            </div>

            <div class="mb-3">
                <label for="trangthai" class="form-label">Trạng thái:</label>
                <select id="trangthai" name="trangthai" class="form-select" required>
                    <option value="1" {{ old('trangthai', (string)$menu->trangthai) === '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('trangthai', (string)$menu->trangthai) === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" style="background-color: var(--mau-chinh); border-color: var(--mau-chinh);">
                    Cập nhật menu
                </button>
                <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary">
                    Quay lại danh sách
                </a>
            </div>
        </form>
    </div>
@endsection
