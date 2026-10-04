<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\LopHocController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return redirect()->route('lophocs.index');
});
// Route::get('/sinhvien', [SinhVienController::class, 'index']);
// Route::get('/sinhvien/add', [SinhVienController::class, 'add']);
// Route::get('/sinhvien/getID/{id}', [SinhVienController::class, 'getID'])->where('id', '[0-9]+');
// Route::post('/sinhvien/show/store', [SinhVienController::class, 'store'])->name('sinhvien.store');

// Route::prefix('sinhvien')->group(function () {
//     Route::get('/', [SinhVienController::class, 'index']);
//     Route::get('/add', [SinhVienController::class, 'add']);
//     Route::post('/store', [SinhVienController::class, 'store'])->name('sinhvien.store');
// });

Route::resource('users', SinhVienController::class);
Route::resource('lophocs', LopHocController::class);
Route::resource('menus', MenuController::class);