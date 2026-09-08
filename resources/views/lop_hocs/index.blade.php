@extends('layout1')

@section('title', 'Danh sách lớp học')
	

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
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Mã lớp</th>
				<th>Tên lớp</th>
				<th>Giáo viên</th>
				<th>Sĩ số</th>
				<th>Trạng thái</th>
				<th>Thao tác</th>
			</tr>
		</thead>
		<tbody>
			@forelse ($lopHocs as $lopHoc)
				<tr>
					<td>{{ $lopHoc->id }}</td>
					<td>{{ $lopHoc->ma_lop }}</td>
					<td>{{ $lopHoc->ten_lop }}</td>
					<td>{{ $lopHoc->giao_vien }}</td>
					<td>{{ $lopHoc->si_so }}</td>
					<td>{{ $lopHoc->trang_thai ? 'Đang hoạt động' : 'Đã khóa' }}</td>
					<td>
						<a href="{{ route('lophocs.edit', $lopHoc->id) }}" class="btn btn-sm btn-primary">Sửa</a>
						<form action="{{ route('lophocs.destroy', $lopHoc->id) }}" method="POST" style="display: inline;">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này không?')">Xóa</button>
						</form>
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="6">Chưa có lớp học nào.</td>
				</tr>
			@endforelse
		</tbody>

	</table>
    <select id="perPage" onchange="if (this.value) window.location.href='{{ url()->current() }}?perPage=' + this.value;">
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>
    {{ $lopHocs->links()  }}
@endsection
