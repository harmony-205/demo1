<?php

namespace Database\Factories;

use App\Models\LopHoc;
use App\Models\SinhVien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SinhVien>
 */
class SinhVienFactory extends Factory
{
    protected $model = SinhVien::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ho = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương'];
        $demNam = ['Văn', 'Hữu', 'Đức', 'Quang', 'Minh', 'Tuấn', 'Công', 'Xuân', 'Đình'];
        $demNu = ['Thị', 'Thu', 'Thanh', 'Mai', 'Ngọc', 'Phương', 'Hải', 'Quỳnh'];
        $tenNam = ['Anh', 'Bình', 'Cường', 'Dũng', 'Đạt', 'Huy', 'Khoa', 'Long', 'Nam', 'Phong', 'Quân', 'Sơn', 'Tùng', 'Việt'];
        $tenNu = ['An', 'Chi', 'Dung', 'Giang', 'Hà', 'Hương', 'Lan', 'Linh', 'Mai', 'Nga', 'Phương', 'Trang', 'Vy', 'Yến'];
        $tinhThanh = ['Hà Nội', 'Hải Phòng', 'Nam Định', 'Thái Bình', 'Nghệ An', 'Thanh Hóa', 'Đà Nẵng', 'Quảng Ninh', 'Bắc Ninh', 'Vĩnh Phúc'];

        $gioiTinh = fake()->randomElement(['Nam', 'Nữ']);
        $hoVal = fake()->randomElement($ho);

        if ($gioiTinh === 'Nam') {
            $hoTen = $hoVal . ' ' . fake()->randomElement($demNam) . ' ' . fake()->randomElement($tenNam);
        } else {
            $hoTen = $hoVal . ' ' . fake()->randomElement($demNu) . ' ' . fake()->randomElement($tenNu);
        }

        $tuoi = fake()->numberBetween(18, 24);
        $namSinh = date('Y') - $tuoi;
        $ngaySinh = fake()->dateTimeBetween("$namSinh-01-01", "$namSinh-12-31")->format('Y-m-d');

        return [
            'ma_sv' => 'SV' . fake()->unique()->numerify('######'),
            'ten' => $hoTen,
            'email' => fake()->unique()->userName() . '@st.huce.edu.vn',
            'so_dien_thoai' => fake()->numerify('09########'),
            'ngay_sinh' => $ngaySinh,
            'gioi_tinh' => $gioiTinh,
            'tuoi' => $tuoi,
            'dia_chi' => fake()->randomElement($tinhThanh),
            'lop_hoc_id' => LopHoc::inRandomOrder()->value('id') ?? LopHoc::factory(),
            'trang_thai' => fake()->boolean(90),
        ];
    }
}
