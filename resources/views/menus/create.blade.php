@extends('layout1')

@section('title', 'Tạo menu mới')
@section('description', 'Thêm một liên kết menu mới vào hệ thống')

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
        <form action="{{ route('menus.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="tenhienthi" class="form-label font-weight-bold">Tên hiển thị <span class="text-danger">*</span>:</label>
                <input type="text" 
                       class="form-control" 
                       id="tenhienthi" 
                       name="tenhienthi" 
                       value="{{ old('tenhienthi') }}" 
                       placeholder="VD: Trang chủ, Tin tức, Giới thiệu..." 
                       required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label font-weight-bold">Slug (Đường dẫn tĩnh) <span class="text-danger">*</span>:</label>
                <input type="text" 
                       class="form-control" 
                       id="slug" 
                       name="slug" 
                       value="{{ old('slug') }}" 
                       placeholder="VD: trang-chu, tin-tuc, gioi-thieu..." 
                       required>
                <small class="text-muted">Slug được tự động tạo từ tên hiển thị, bạn có thể chỉnh sửa nếu cần.</small>
            </div>

            <div class="mb-3">
                <label for="trangthai" class="form-label">Trạng thái:</label>
                <select id="trangthai" name="trangthai" class="form-select" required>
                    <option value="1" {{ old('trangthai', '1') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('trangthai') === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" style="background-color: var(--mau-chinh); border-color: var(--mau-chinh);">
                    Lưu menu
                </button>
                <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary">
                    Quay lại danh sách
                </a>
            </div>
        </form>
    </div>

    <script>
        // Tự động chuyển tên hiển thị thành slug tiếng Việt
        const tenhienthiInput = document.getElementById('tenhienthi');
        const slugInput = document.getElementById('slug');
        let isSlugManuallyEdited = false;

        slugInput.addEventListener('input', function() {
            isSlugManuallyEdited = true;
        });

        tenhienthiInput.addEventListener('input', function() {
            if (!isSlugManuallyEdited) {
                slugInput.value = generateSlug(this.value);
            }
        });

        function generateSlug(str) {
            str = str.toLowerCase();
            // Xóa dấu tiếng Việt
            str = str.replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a');
            str = str.replace(/[èéẹẻẽêềếệểễ]/g, 'e');
            str = str.replace(/[ìíịỉĩ]/g, 'i');
            str = str.replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o');
            str = str.replace(/[ùúụủũưừứựửữ]/g, 'u');
            str = str.replace(/[ỳýỵỷỹ]/g, 'y');
            str = str.replace(/đ/g, 'd');
            // Xóa ký tự đặc biệt
            str = str.replace(/[^a-z0-9 -]/g, '');
            // Thay khoảng trắng thành dấu gạch ngang
            str = str.replace(/\s+/g, '-');
            // Thay nhiều gạch ngang thành 1 gạch ngang
            str = str.replace(/-+/g, '-');
            return str;
        }
    </script>
@endsection
