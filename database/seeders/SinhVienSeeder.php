<?php

namespace Database\Seeders;

use App\Models\LopHoc;
use App\Models\SinhVien;
use Illuminate\Database\Seeder;

class SinhVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lopHocIds = LopHoc::pluck('id')->toArray();

        // Tạo 5 sinh viên mẫu cụ thể
        $mau = [
            [
                'ma_sv' => 'SV680001',
                'ten' => 'Nguyễn Văn An',
                'email' => 'an.nv68@st.huce.edu.vn',
                'so_dien_thoai' => '0912111222',
                'ngay_sinh' => '2004-03-15',
                'gioi_tinh' => 'Nam',
                'tuoi' => 20,
                'dia_chi' => 'Hà Nội',
                'lop_hoc_id' => !empty($lopHocIds) ? $lopHocIds[0] : null,
                'trang_thai' => true,
            ],
            [
                'ma_sv' => 'SV680002',
                'ten' => 'Trần Thị Bình',
                'email' => 'binh.tt68@st.huce.edu.vn',
                'so_dien_thoai' => '0912222333',
                'ngay_sinh' => '2003-08-20',
                'gioi_tinh' => 'Nữ',
                'tuoi' => 21,
                'dia_chi' => 'Hải Phòng',
                'lop_hoc_id' => !empty($lopHocIds) ? $lopHocIds[0] : null,
                'trang_thai' => true,
            ],
            [
                'ma_sv' => 'SV680003',
                'ten' => 'Lê Hoàng Cường',
                'email' => 'cuong.lh68@st.huce.edu.vn',
                'so_dien_thoai' => '0912333444',
                'ngay_sinh' => '2004-11-05',
                'gioi_tinh' => 'Nam',
                'tuoi' => 20,
                'dia_chi' => 'Đà Nẵng',
                'lop_hoc_id' => !empty($lopHocIds) ? ($lopHocIds[1] ?? $lopHocIds[0]) : null,
                'trang_thai' => true,
            ],
            [
                'ma_sv' => 'SV680004',
                'ten' => 'Phạm Thu Dung',
                'email' => 'dung.pt68@st.huce.edu.vn',
                'so_dien_thoai' => '0912444555',
                'ngay_sinh' => '2002-05-12',
                'gioi_tinh' => 'Nữ',
                'tuoi' => 22,
                'dia_chi' => 'Cần Thơ',
                'lop_hoc_id' => !empty($lopHocIds) ? ($lopHocIds[1] ?? $lopHocIds[0]) : null,
                'trang_thai' => true,
            ],
            [
                'ma_sv' => 'SV680005',
                'ten' => 'Hoàng Minh Đức',
                'email' => 'duc.hm68@st.huce.edu.vn',
                'so_dien_thoai' => '0912555666',
                'ngay_sinh' => '2004-01-25',
                'gioi_tinh' => 'Nam',
                'tuoi' => 20,
                'dia_chi' => 'TP. Hồ Chí Minh',
                'lop_hoc_id' => !empty($lopHocIds) ? ($lopHocIds[2] ?? $lopHocIds[0]) : null,
                'trang_thai' => true,
            ],
        ];

        foreach ($mau as $item) {
            SinhVien::updateOrCreate(['ma_sv' => $item['ma_sv']], $item);
        }

        // Tạo thêm 25 sinh viên ngẫu nhiên bằng factory
        SinhVien::factory()->count(25)->create();
    }
}
