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
    public function show($id="")
    {
        return "Đây là sinh viên có ID = $id";
    }
}

// php artisan make: controller SinhVienController
