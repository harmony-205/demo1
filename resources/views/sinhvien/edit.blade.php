@extends('layouts.layoutmaster')
@section('title', 'Sửa sinh viên')

@section('content')
    <div style="max-width: 560px;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sinhvien.index') }}">Sinh viên</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('sinhvien.show', $sinhvien['id']) }}">
                        {{ $sinhvien['ten'] ?? $sinhvien['name'] }}
                    </a>
                </li>
                <li class="breadcrumb-item active">Chỉnh sửa</li>
            </ol>
        </nav>

        <h1 class="mb-4">Sửa sinh viên</h1>

        <form action="{{ route('sinhvien.update', $sinhvien['id']) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            @include('sinhvien._form', ['sinhvien' => $sinhvien])

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('sinhvien.show', $sinhvien['id']) }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection
