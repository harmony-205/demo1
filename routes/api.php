<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LopHocController;
use App\Http\Controllers\Api\SinhVienController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Quản lý lớp học
Route::apiResource('lop-hoc', LopHocController::class);
Route::apiResource('lophoc', LopHocController::class);

// API Quản lý sinh viên
Route::apiResource('sinh-vien', SinhVienController::class);
Route::apiResource('sinhvien', SinhVienController::class);
