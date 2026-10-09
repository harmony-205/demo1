@extends('layouts.layoutmaster')
@section('title', 'Thêm sinh viên')

@section('content')
    <div style="max-width: 560px;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('sinhvien.index') }}">Sinh viên</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>

        <h1 class="mb-4">Thêm sinh viên</h1>

        <form action="{{ route('sinhvien.store') }}" method="POST" novalidate>
            @csrf
            @include('sinhvien._form')

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Lưu sinh viên</button>
                <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection