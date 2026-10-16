<?php

namespace Tests\Feature;

use App\Models\LopHoc;
use App\Models\Menu;
use App\Models\SinhVien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClassroomManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teacher;
    protected LopHoc $lopHoc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create([
            'email' => 'admin@st.huce.edu.vn',
            'password' => Hash::make('123456'),
        ]);

        $this->teacher = User::factory()->teacher()->create([
            'email' => 'teacher@st.huce.edu.vn',
            'password' => Hash::make('123456'),
        ]);

        $this->lopHoc = LopHoc::factory()->create([
            'ten_lop' => '68PM12',
            'ma_lop' => 'PM12_TEST',
            'giao_vien' => 'ThS. Nguyễn Văn A',
            'si_so' => 40,
            'trang_thai' => true,
        ]);
    }

    /**
     * Test đăng nhập với tài khoản admin và teacher theo đề bài
     */
    public function test_dang_nhap_thanh_cong_admin_va_teacher(): void
    {
        // 1. Admin login
        $responseAdmin = $this->post(route('login.post'), [
            'email' => 'admin@st.huce.edu.vn',
            'password' => '123456',
        ]);
        $responseAdmin->assertRedirect(route('lophoc.index'));
        $this->assertAuthenticatedAs($this->admin);

        // Logout
        $this->post(route('logout'));
        $this->assertGuest();

        // 2. Teacher login
        $responseTeacher = $this->post(route('login.post'), [
            'email' => 'teacher@st.huce.edu.vn',
            'password' => '123456',
        ]);
        $responseTeacher->assertRedirect(route('lophoc.index'));
        $this->assertAuthenticatedAs($this->teacher);
    }

    /**
     * Test phân quyền: Giảng viên KHÔNG được vào Quản lý User và Quản lý Menu
     */
    public function test_giang_vien_khong_the_truy_cap_quan_ly_user_va_menu(): void
    {
        $this->actingAs($this->teacher);

        // Truy cập quản lý user -> bị chặn redirect về lophoc với thông báo lỗi
        $responseUsers = $this->get(route('users.index'));
        $responseUsers->assertRedirect(route('lophoc.index'));
        $responseUsers->assertSessionHas('error');

        // Truy cập quản lý menu -> bị chặn redirect về lophoc
        $responseMenu = $this->get(route('menu.index'));
        $responseMenu->assertRedirect(route('lophoc.index'));
        $responseMenu->assertSessionHas('error');
    }

    /**
     * Test phân quyền: Giảng viên ĐƯỢC quản lý lớp học, sinh viên và tài khoản bản thân
     */
    public function test_giang_vien_duoc_quan_ly_lop_hoc_sinh_vien_va_tai_khoan(): void
    {
        $this->actingAs($this->teacher);

        // Quản lý lớp học
        $resLopHoc = $this->get(route('lophoc.index'));
        $resLopHoc->assertOk();
        $resLopHoc->assertSee('Quản lý lớp học');

        // Quản lý sinh viên
        $resSinhVien = $this->get(route('sinhvien.index'));
        $resSinhVien->assertOk();
        $resSinhVien->assertSee('Quản lý sinh viên');

        // Thông tin cá nhân
        $resProfile = $this->get(route('profile.index'));
        $resProfile->assertOk();
        $resProfile->assertSee('Thông tin tài khoản');
    }

    /**
     * Test phân quyền: Admin có toàn quyền truy cập tất cả chức năng
     */
    public function test_admin_co_toan_quyen_truy_cap_tat_ca_chuc_nang(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('lophoc.index'))->assertOk();
        $this->get(route('sinhvien.index'))->assertOk();
        $this->get(route('users.index'))->assertOk();
        $this->get(route('menu.index'))->assertOk();
        $this->get(route('profile.index'))->assertOk();
    }

    /**
     * Test thêm mới sinh viên có validate bằng Request
     */
    public function test_them_moi_sinh_vien_thanh_cong_va_validate(): void
    {
        $this->actingAs($this->teacher);

        // Validate lỗi khi thiếu trường bắt buộc
        $resError = $this->post(route('sinhvien.store'), [
            'ma_sv' => '',
            'ten' => '',
            'tuoi' => '',
        ]);
        $resError->assertSessionHasErrors(['ma_sv', 'ten', 'tuoi', 'gioi_tinh']);

        // Thêm thành công
        $resSuccess = $this->post(route('sinhvien.store'), [
            'ma_sv' => 'SV_TEST_01',
            'ten' => 'Nguyễn Văn Kiểm Thử',
            'email' => 'kiemthu@st.huce.edu.vn',
            'tuoi' => 20,
            'gioi_tinh' => 'Nam',
            'dia_chi' => 'Hà Nội',
            'lop_hoc_id' => $this->lopHoc->id,
            'trang_thai' => 1,
        ]);

        $resSuccess->assertRedirect(route('sinhvien.index'));
        $resSuccess->assertSessionHas('success');
        $this->assertDatabaseHas('sinh_viens', [
            'ma_sv' => 'SV_TEST_01',
            'ten' => 'Nguyễn Văn Kiểm Thử',
        ]);
    }

    /**
     * Test API Quản lý lớp học (đủ CRUD)
     */
    public function test_api_lop_hoc_day_du_crud(): void
    {
        // 1. Index
        $resIndex = $this->getJson('/api/lop-hoc');
        $resIndex->assertOk()
            ->assertJsonPath('success', true);

        // 2. Store
        $resStore = $this->postJson('/api/lop-hoc', [
            'ten_lop' => 'Lớp API Test',
            'ma_lop' => 'API_TEST_01',
            'giao_vien' => 'TS. Trần API',
            'si_so' => 35,
            'trang_thai' => true,
        ]);
        $resStore->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.ma_lop', 'API_TEST_01');

        $createdId = $resStore->json('data.id');

        // 3. Show
        $resShow = $this->getJson('/api/lop-hoc/' . $createdId);
        $resShow->assertOk()
            ->assertJsonPath('data.ten_lop', 'Lớp API Test');

        // 4. Update
        $resUpdate = $this->putJson('/api/lop-hoc/' . $createdId, [
            'ten_lop' => 'Lớp API Test Cập Nhật',
            'ma_lop' => 'API_TEST_01',
            'giao_vien' => 'TS. Trần API Đã Đổi',
            'si_so' => 38,
        ]);
        $resUpdate->assertOk()
            ->assertJsonPath('data.ten_lop', 'Lớp API Test Cập Nhật');

        // 5. Destroy
        $resDestroy = $this->deleteJson('/api/lop-hoc/' . $createdId);
        $resDestroy->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('lop_hocs', ['id' => $createdId]);
    }

    /**
     * Test API Quản lý sinh viên (đủ CRUD)
     */
    public function test_api_sinh_vien_day_du_crud(): void
    {
        // 1. Index
        $resIndex = $this->getJson('/api/sinh-vien');
        $resIndex->assertOk()
            ->assertJsonPath('success', true);

        // 2. Store
        $resStore = $this->postJson('/api/sinh-vien', [
            'ma_sv' => 'SV_API_001',
            'ten' => 'Lê Văn API',
            'email' => 'api.sv@st.huce.edu.vn',
            'gioi_tinh' => 'Nam',
            'tuoi' => 21,
            'dia_chi' => 'Đà Nẵng',
            'lop_hoc_id' => $this->lopHoc->id,
            'trang_thai' => true,
        ]);
        $resStore->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.ma_sv', 'SV_API_001');

        $createdId = $resStore->json('data.id');

        // 3. Show
        $resShow = $this->getJson('/api/sinh-vien/' . $createdId);
        $resShow->assertOk()
            ->assertJsonPath('data.ten', 'Lê Văn API');

        // 4. Update
        $resUpdate = $this->putJson('/api/sinh-vien/' . $createdId, [
            'ma_sv' => 'SV_API_001',
            'ten' => 'Lê Văn API Đã Sửa',
            'gioi_tinh' => 'Nam',
            'tuoi' => 22,
            'dia_chi' => 'Hải Phòng',
        ]);
        $resUpdate->assertOk()
            ->assertJsonPath('data.ten', 'Lê Văn API Đã Sửa');

        // 5. Destroy
        $resDestroy = $this->deleteJson('/api/sinh-vien/' . $createdId);
        $resDestroy->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('sinh_viens', ['id' => $createdId]);
    }

    /**
     * Test Quản lý user: Thêm và chỉnh sửa user
     */
    public function test_quan_ly_user_crud_thanh_cong(): void
    {
        $this->actingAs($this->admin);

        // Thêm mới user
        $resStore = $this->post(route('users.store'), [
            'name' => 'Giảng viên Mới',
            'email' => 'gv.new@st.huce.edu.vn',
            'password' => '123456',
            'role' => User::ROLE_TEACHER,
            'so_dien_thoai' => '0933333333',
            'trang_thai' => 1,
        ]);
        $resStore->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'gv.new@st.huce.edu.vn']);

        $newUser = User::where('email', 'gv.new@st.huce.edu.vn')->first();

        // Chỉnh sửa user
        $resUpdate = $this->put(route('users.update', $newUser), [
            'name' => 'Giảng viên Đã Sửa Tên',
            'email' => 'gv.new@st.huce.edu.vn',
            'role' => User::ROLE_TEACHER,
            'so_dien_thoai' => '0944444444',
            'trang_thai' => 1,
        ]);
        $resUpdate->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['name' => 'Giảng viên Đã Sửa Tên']);

        // Xóa user
        $resDelete = $this->delete(route('users.destroy', $newUser));
        $resDelete->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }
}
