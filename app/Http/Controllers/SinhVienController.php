<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinhVienController extends Controller
{
    //
    private $sinhviens = [
        ['id' => 1, 'name' => 'Nguyen Van A', 'age' => 20],
        ['id' => 2, 'name' => 'Nguyen Van B', 'age' => 21],
        ['id' => 3, 'name' => 'Nguyen Van C', 'age' => 22],
    ];
    public function index()
    {
        return view('sinhvien.index', [
            'title' => 'Danh sách sinh viên',
            'sinhviens' => $this->sinhviens
        ]);
    }
    public function getID($id)
    {
        $sinhvien = collect($this->sinhviens)->firstWhere('id', $id);
        return "Thông tin sinh viên có ID = $id: Tên = {$sinhvien['name']}, Tuổi = {$sinhvien['age']}";
    }
    public function show($hoten="Chưa có tên", $tuoi=0)
    {
        return "Đây là sinh viên có tên = $hoten, tuổi = $tuoi";
    }
    public function add()
    {
        return view('sinhvien.add',[
            'title' => 'Thêm sinh viên'
        ]);
    }
    public function store(Request $request)
    {
        dd($request->all());
    }
}

// php artisan make:controller SinhVienController
