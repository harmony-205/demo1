@extends('layouts.layoutmaster')
@section('title', 'Chi tiết sinh viên')

@section('content')
    <div style="max-width: 560px;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sinhvien.index') }}">Sinh viên</a></li>
                <li class="breadcrumb-item active">{{ $sinhvien['ten'] ?? $sinhvien['name'] }}</li>
            </ol>
        </nav>

        <h1 class="mb-4">Chi tiết sinh viên</h1>

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th class="table-light" style="width: 140px;">ID</th>
                    <td>{{ $sinhvien['id'] }}</td>
                </tr>
                <tr>
                    <th class="table-light">Tên sinh viên</th>
                    <td>{{ $sinhvien['ten'] ?? $sinhvien['name'] }}</td>
                </tr>
                <tr>
                    <th class="table-light">Tuổi</th>
                    <td>{{ $sinhvien['tuoi'] ?? $sinhvien['age'] }}</td>
                </tr>
                <tr>
                    <th class="table-light">Địa chỉ</th>
                    <td>{{ $sinhvien['diachi'] ?: '—' }}</td>
                </tr>
            </tbody>
        </table>

        @php $tenSV = addslashes($sinhvien['ten'] ?? $sinhvien['name']); @endphp
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('sinhvien.edit', $sinhvien['id']) }}" class="btn btn-primary">Sửa</a>
            <form action="{{ route('sinhvien.destroy', $sinhvien['id']) }}" method="POST"
                  onsubmit="return confirm('Xóa sinh viên {{ $tenSV }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Xóa</button>
            </form>
            <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary ms-auto">← Danh sách</a>
        </div>
    </div>
@endsection
