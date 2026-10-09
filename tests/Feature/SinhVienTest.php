<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SinhVienTest extends TestCase
{
    use RefreshDatabase;
    public function test_trang_danh_sach_sinh_vien_hien_thi_thanh_cong(): void
    {
        $response = $this->get(route('sinhvien.index'));
        $response->assertOk();
        $response->assertSee('Danh sách sinh viên');
        $response->assertSee('Thêm sinh viên');
    }

    public function test_form_them_sinh_vien_hien_thi_thanh_cong(): void
    {
        $response = $this->get(route('sinhvien.create'));
        $response->assertOk();
        $response->assertSee('Thêm sinh viên');
        $response->assertSee('Tên sinh viên');
    }

    public function test_them_sinh_vien_thanh_cong(): void
    {
        $response = $this->post(route('sinhvien.store'), [
            'ten' => 'Sinh Viên Mới',
            'tuoi' => 20,
            'diachi' => 'Hà Nội',
        ]);

        $response->assertRedirect(route('sinhvien.index'));
        $response->assertSessionHas('success');

        $this->get(route('sinhvien.index'))->assertSee('Sinh Viên Mới');
    }

    public function test_them_sinh_vien_bao_loi_khi_thieu_truong_bat_buoc(): void
    {
        $response = $this->post(route('sinhvien.store'), [
            'ten' => '',
            'tuoi' => '',
        ]);

        $response->assertSessionHasErrors(['ten', 'tuoi']);
    }

    public function test_form_sua_sinh_vien_hien_thi_thanh_cong(): void
    {
        $response = $this->get(route('sinhvien.edit', 1));
        $response->assertOk();
        $response->assertSee('Sửa sinh viên');
        $response->assertSee('Nguyen Van A');
    }

    public function test_cap_nhat_sinh_vien_thanh_cong(): void
    {
        $response = $this->put(route('sinhvien.update', 1), [
            'ten' => 'Nguyen Van A Updated',
            'tuoi' => 25,
            'diachi' => 'Đà Nẵng',
        ]);

        $response->assertRedirect(route('sinhvien.index'));
        $response->assertSessionHas('success');

        $this->get(route('sinhvien.index'))->assertSee('Nguyen Van A Updated');
    }

    public function test_xoa_sinh_vien_thanh_cong(): void
    {
        $response = $this->delete(route('sinhvien.destroy', 1));
        $response->assertRedirect(route('sinhvien.index'));
        $response->assertSessionHas('success');
    }

    public function test_lophoc_hien_thi_giao_dien_web_binh_thuong_khong_bi_chan_gio_hanh_chinh(): void
    {
        $response = $this->get(route('lophoc.index'));
        $response->assertOk();
        $response->assertDontSee('Outside working hours');
        $response->assertSee('Danh sách lớp học');
    }
}
