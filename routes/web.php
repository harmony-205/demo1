<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVienController;

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/sinhvien', [SinhVienController::class, 'index']);
Route::get('/sinhvien/add', [SinhVienController::class, 'add']);
Route::get('/sinhvien/show/{id?}', [SinhVienController::class, 'show']);