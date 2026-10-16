<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LopHoc;

class LopHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LopHoc::updateOrCreate(
            ['ma_lop' => 'LA001'],
            [
                'ten_lop' => 'Lớp 68PM12 - Công nghệ Thông tin',
                'giao_vien' => 'TS. Nguyễn Văn A',
                'so_dien_thoai_gvcn' => '0912345678',
                'ghi_chu' => 'Lớp học trọng điểm khoa CNTT',
                'si_so' => 45,
                'trang_thai' => true,
            ]
        );

        LopHoc::updateOrCreate(
            ['ma_lop' => 'LA002'],
            [
                'ten_lop' => 'Lớp 68XD01 - Xây dựng Dân dụng',
                'giao_vien' => 'ThS. Trần Văn B',
                'so_dien_thoai_gvcn' => '0987654321',
                'ghi_chu' => 'Lớp học khoa Xây dựng',
                'si_so' => 50,
                'trang_thai' => true,
            ]
        );

        LopHoc::updateOrCreate(
            ['ma_lop' => 'LA003'],
            [
                'ten_lop' => 'Lớp 68KT02 - Kinh tế Xây dựng',
                'giao_vien' => 'TS. Lê Thị C',
                'so_dien_thoai_gvcn' => '0901234567',
                'ghi_chu' => 'Lớp học khoa Kinh tế',
                'si_so' => 40,
                'trang_thai' => true,
            ]
        );

        if (LopHoc::count() < 15) {
            LopHoc::factory()->count(12)->create();
        }
    }
}
