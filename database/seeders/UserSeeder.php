<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tài khoản Admin theo yêu cầu
        User::updateOrCreate(
            ['email' => 'admin@st.huce.edu.vn'],
            [
                'name' => 'Quản trị viên HUCE',
                'password' => Hash::make('123456'),
                'role' => User::ROLE_ADMIN,
                'so_dien_thoai' => '0912345678',
                'trang_thai' => true,
                'email_verified_at' => now(),
            ]
        );

        // Tài khoản Giảng viên theo yêu cầu
        User::updateOrCreate(
            ['email' => 'teacher@st.huce.edu.vn'],
            [
                'name' => 'Giảng viên HUCE',
                'password' => Hash::make('123456'),
                'role' => User::ROLE_TEACHER,
                'so_dien_thoai' => '0987654321',
                'trang_thai' => true,
                'email_verified_at' => now(),
            ]
        );

        // Thêm một số giảng viên & admin mẫu
        User::updateOrCreate(
            ['email' => 'gv.nguyenvanan@st.huce.edu.vn'],
            [
                'name' => 'TS. Nguyễn Văn An',
                'password' => Hash::make('123456'),
                'role' => User::ROLE_TEACHER,
                'so_dien_thoai' => '0901234567',
                'trang_thai' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'gv.tranthib@st.huce.edu.vn'],
            [
                'name' => 'ThS. Trần Thị Bích',
                'password' => Hash::make('123456'),
                'role' => User::ROLE_TEACHER,
                'so_dien_thoai' => '0934567890',
                'trang_thai' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
