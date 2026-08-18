@extends('layout1')
@section('title',"Danh sách sinh viên")
@push('styles')
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        tr:hover { background-color: #f5f5f5; }
    </style>   
@endpush
@section('content')
    <table>
        <tr>
            <th>ID</th>
            <th>Tên sinh viên</th>
            <th>Tuổi</th>
        </tr>
        @foreach($sinhviens as $sinhvien)
            <tr>
                <td>{{ $sinhvien['id'] }}</td>
                <td>{{ $sinhvien['name'] }}</td>
                <td>{{ $sinhvien['age'] }}</td>
            </tr>
        @endforeach
    </table>
@endsection