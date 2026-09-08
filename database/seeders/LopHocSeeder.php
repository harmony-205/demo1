<?php

namespace Database\Seeders;

use App\Models\LopHoc;
use Illuminate\Database\Seeder;


class LopHocSeeder extends Seeder
{
    /**
     * Seed the lop_hocs table.
     */
    public function run(): void
    {
        LopHoc::factory()->count(20)->create();
        LopHoc::factory()->create([
            'ma_lop' => 'WEB1',
            'ten_lop' => 'Lập trình Web 1',
            'giao_vien' => 'Nguyễn Văn A',
            'so_dien_thoai_gvcn' => '0901234567',
            'ghi_chu' => 'Lớp thực hành HTML, CSS và Laravel.',
            'si_so' => 35,
            'trang_thai' => true,
            ]);

        

    
    }
}
