<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa menu cũ để seed lại chuẩn chỉnh
        Menu::truncate();

        $menus = [
            // Header
            ['ten' => 'Trang chủ', 'url' => '/', 'vi_tri' => 'header', 'nhom' => null, 'role' => 'all'],
            ['ten' => 'Lớp học', 'url' => '/lophoc', 'vi_tri' => 'header', 'nhom' => null, 'role' => 'all'],
            ['ten' => 'Sinh viên', 'url' => '/sinhvien', 'vi_tri' => 'header', 'nhom' => null, 'role' => 'all'],

            // Sidebar - Đào tạo (Cả Admin & Giảng viên)
            ['ten' => 'Quản lý lớp học', 'url' => '/lophoc', 'vi_tri' => 'sidebar', 'nhom' => 'Quản lý đào tạo', 'role' => 'all'],
            ['ten' => 'Quản lý sinh viên', 'url' => '/sinhvien', 'vi_tri' => 'sidebar', 'nhom' => 'Quản lý đào tạo', 'role' => 'all'],

            // Sidebar - Quản trị hệ thống (Chỉ Admin)
            ['ten' => 'Quản lý Menu', 'url' => '/menu', 'vi_tri' => 'sidebar', 'nhom' => 'Quản trị hệ thống', 'role' => 'admin'],
            ['ten' => 'Quản lý User', 'url' => '/users', 'vi_tri' => 'sidebar', 'nhom' => 'Quản trị hệ thống', 'role' => 'admin'],

            // Sidebar - Cá nhân (Cả Admin & Giảng viên)
            ['ten' => 'Thông tin cá nhân', 'url' => '/profile', 'vi_tri' => 'sidebar', 'nhom' => 'Tài khoản', 'role' => 'all'],
        ];

        foreach ($menus as $thuTu => $menu) {
            Menu::create($menu + [
                'slug' => \Illuminate\Support\Str::slug($menu['ten']) . '-' . ($thuTu + 1),
                'tenhienthi' => $menu['ten'],
                'trangthai' => true,
                'thu_tu' => $thuTu + 1,
                'trang_thai' => true,
            ]);
        }
    }
}
