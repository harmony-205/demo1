<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\LopHocController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Chuyển hướng trang chủ về danh sách lớp học
Route::get('/', function () {
    return redirect()->route('lophoc.index');
});

// Xác thực tài khoản (Đăng nhập / Đăng xuất)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Các route yêu cầu người dùng phải đăng nhập (Dành cho cả Admin & Giảng viên)
Route::middleware(['auth'])->group(function () {
    // Quản lý thông tin tài khoản cá nhân
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Quản lý lớp học
    Route::resource('lophoc', LopHocController::class);

    // Quản lý sinh viên
    Route::resource('sinhvien', SinhVienController::class);
    Route::get('sinhvien/add', [SinhVienController::class, 'create'])->name('sinhvien.add');

    // Các route DÀNH RIÊNG CHO QUẢN TRỊ VIÊN (Admin)
    Route::middleware(['admin'])->group(function () {
        // Quản lý người dùng
        Route::resource('users', UserController::class);

        // Quản lý menu
        Route::resource('menu', MenuController::class)->except('show');
    });
});
