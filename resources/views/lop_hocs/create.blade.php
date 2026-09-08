@extends('layout1')

@section('title', 'Tạo lớp học')

@push('styles')
	<style>
		table {
			border-collapse: collapse;
			width: 100%;
		}

		th,
		td {
			padding: 10px 12px;
			text-align: left;
			border: 1px solid #ddd;
		}

		th {
			background-color: #f0ebf5;
			color: #3d2b56;
		}

		tbody tr:hover {
			background-color: #f8f5fb;
		}
	</style>
@endpush

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{route('lophocs.store')}}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="ma_lop" class="form-label">Mã lớp:</label>
            <input type="text" class="form-control" id="ma_lop" name="ma_lop" required>
        </div>
        <div class="mb-3">
            <label for="ten_lop" class="form-label">Tên lớp:</label>
            <input type="text" class="form-control" id="ten_lop" name="ten_lop" required>
        </div>
        <div class="mb-3">
            <label for="giao_vien" class="form-label">Giáo viên:</label>
            <input type="text" class="form-control" id="giao_vien" name="giao_vien" required>
        </div>
        <div class="mb-3">
            <label for="so_dien_thoai_gvcn" class="from-label">Số điện thoại:</label>
            <input type="text" class="form-control" id="so_dien_thoai_gvcn" name="so_dien_thoai_gvcn" required>
        </div>
        <div class="mb-3">
            <label for="si_so" class="form-label">Sĩ số:</label>
            <input type="number" class="form-control" id="si_so" name="si_so" required>
        </div>
        <div class="mb-3">
            <label for="trang_thai" class="form-label">Trạng thái:</label>
            <select id="trang_thai" name="trang_thai" class="form-select" required>
                <option value="1">Đang hoạt động</option>
                <option value="0">Đã khóa</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Tạo lớp học</button>
    </form>
@endsection
