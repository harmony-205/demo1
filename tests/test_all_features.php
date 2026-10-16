<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "====================================================\n";
echo "   KIỂM TRA TỰ ĐỘNG HỆ THỐNG QUẢN LÝ LỚP HỌC HUCE   \n";
echo "====================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertTest($condition, $name) {
    global $passCount, $failCount;
    if ($condition) {
        echo " [PASS] $name\n";
        $passCount++;
    } else {
        echo " [FAIL] $name\n";
        $failCount++;
    }
}

// 1. Kiểm tra tài khoản admin & teacher trong DB
$admin = App\Models\User::where('email', 'admin@st.huce.edu.vn')->first();
$teacher = App\Models\User::where('email', 'teacher@st.huce.edu.vn')->first();

assertTest($admin !== null, "Tài khoản admin@st.huce.edu.vn tồn tại");
assertTest($admin && $admin->isAdmin(), "Tài khoản admin có role = 'admin'");
assertTest($admin && Illuminate\Support\Facades\Hash::check('123456', $admin->password), "Mật khẩu admin là 123456");

assertTest($teacher !== null, "Tài khoản teacher@st.huce.edu.vn tồn tại");
assertTest($teacher && $teacher->isTeacher(), "Tài khoản teacher có role = 'teacher'");
assertTest($teacher && Illuminate\Support\Facades\Hash::check('123456', $teacher->password), "Mật khẩu teacher là 123456");

// 2. Kiểm tra phân quyền Middleware
// Giả lập request người dùng chưa đăng nhập vào /lophoc
$reqGuest = Illuminate\Http\Request::create('/lophoc', 'GET');
$resGuest = $kernel->handle($reqGuest);
assertTest($resGuest->getStatusCode() === 302 && str_contains($resGuest->headers->get('Location'), 'login'), "Khách chưa đăng nhập vào /lophoc bị chuyển hướng về /login");

// Đăng nhập tài khoản Giảng viên và kiểm tra quyền
Illuminate\Support\Facades\Auth::login($teacher);

$reqTeacherLopHoc = Illuminate\Http\Request::create('/lophoc', 'GET');
$resTeacherLopHoc = $kernel->handle($reqTeacherLopHoc);
assertTest($resTeacherLopHoc->getStatusCode() === 200, "Giảng viên truy cập được Quản lý lớp học (/lophoc) -> HTTP 200");

$reqTeacherSinhVien = Illuminate\Http\Request::create('/sinhvien', 'GET');
$resTeacherSinhVien = $kernel->handle($reqTeacherSinhVien);
assertTest($resTeacherSinhVien->getStatusCode() === 200, "Giảng viên truy cập được Quản lý sinh viên (/sinhvien) -> HTTP 200");

$reqTeacherProfile = Illuminate\Http\Request::create('/profile', 'GET');
$resTeacherProfile = $kernel->handle($reqTeacherProfile);
assertTest($resTeacherProfile->getStatusCode() === 200, "Giảng viên truy cập được Quản lý tài khoản bản thân (/profile) -> HTTP 200");

$reqTeacherUsers = Illuminate\Http\Request::create('/users', 'GET');
$resTeacherUsers = $kernel->handle($reqTeacherUsers);
assertTest($resTeacherUsers->getStatusCode() === 302, "Giảng viên bị chặn khi vào Quản lý người dùng (/users) -> Redirect");

$reqTeacherMenu = Illuminate\Http\Request::create('/menu', 'GET');
$resTeacherMenu = $kernel->handle($reqTeacherMenu);
assertTest($resTeacherMenu->getStatusCode() === 302, "Giảng viên bị chặn khi vào Quản lý menu (/menu) -> Redirect");

// Đăng nhập tài khoản Admin và kiểm tra toàn quyền
Illuminate\Support\Facades\Auth::login($admin);

$reqAdminUsers = Illuminate\Http\Request::create('/users', 'GET');
$resAdminUsers = $kernel->handle($reqAdminUsers);
assertTest($resAdminUsers->getStatusCode() === 200, "Admin truy cập được Quản lý người dùng (/users) -> HTTP 200");

$reqAdminMenu = Illuminate\Http\Request::create('/menu', 'GET');
$resAdminMenu = $kernel->handle($reqAdminMenu);
assertTest($resAdminMenu->getStatusCode() === 200, "Admin truy cập được Quản lý menu (/menu) -> HTTP 200");

// 3. Kiểm tra API Lớp học (Full CRUD)
// GET list
$reqApiLopHocGet = Illuminate\Http\Request::create('/api/lop-hoc', 'GET');
$resApiLopHocGet = $kernel->handle($reqApiLopHocGet);
assertTest($resApiLopHocGet->getStatusCode() === 200, "API GET /api/lop-hoc -> HTTP 200");

// POST create
$testMaLop = 'TEST_LH_' . time();
$reqApiLopHocPost = Illuminate\Http\Request::create('/api/lop-hoc', 'POST', [
    'ten_lop' => 'Lớp Test API Tự Động',
    'ma_lop' => $testMaLop,
    'giao_vien' => 'Giáo viên Test',
    'si_so' => 30,
    'trang_thai' => true,
]);
$reqApiLopHocPost->headers->set('Accept', 'application/json');
$resApiLopHocPost = $kernel->handle($reqApiLopHocPost);
assertTest($resApiLopHocPost->getStatusCode() === 201, "API POST /api/lop-hoc -> HTTP 201 Created");
$createdLop = json_decode($resApiLopHocPost->getContent(), true)['data'] ?? null;
$createdLopId = $createdLop['id'] ?? null;

// PUT update
if ($createdLopId) {
    $reqApiLopHocPut = Illuminate\Http\Request::create('/api/lop-hoc/' . $createdLopId, 'PUT', [
        'ten_lop' => 'Lớp Test API Đã Sửa',
        'ma_lop' => $testMaLop,
        'giao_vien' => 'Giáo viên Đã Đổi',
        'si_so' => 35,
    ]);
    $reqApiLopHocPut->headers->set('Accept', 'application/json');
    $resApiLopHocPut = $kernel->handle($reqApiLopHocPut);
    assertTest($resApiLopHocPut->getStatusCode() === 200, "API PUT /api/lop-hoc/$createdLopId -> HTTP 200");

    // DELETE destroy
    $reqApiLopHocDel = Illuminate\Http\Request::create('/api/lop-hoc/' . $createdLopId, 'DELETE');
    $reqApiLopHocDel->headers->set('Accept', 'application/json');
    $resApiLopHocDel = $kernel->handle($reqApiLopHocDel);
    assertTest($resApiLopHocDel->getStatusCode() === 200, "API DELETE /api/lop-hoc/$createdLopId -> HTTP 200");
}

// 4. Kiểm tra API Sinh viên (Full CRUD)
// GET list
$reqApiSvGet = Illuminate\Http\Request::create('/api/sinh-vien', 'GET');
$resApiSvGet = $kernel->handle($reqApiSvGet);
assertTest($resApiSvGet->getStatusCode() === 200, "API GET /api/sinh-vien -> HTTP 200");

// POST create
$testMaSv = 'SV_' . time();
$reqApiSvPost = Illuminate\Http\Request::create('/api/sinh-vien', 'POST', [
    'ma_sv' => $testMaSv,
    'ten' => 'Sinh viên API Test',
    'email' => 'sv.' . time() . '@st.huce.edu.vn',
    'gioi_tinh' => 'Nam',
    'tuoi' => 20,
    'dia_chi' => 'Hà Nội',
    'trang_thai' => true,
]);
$reqApiSvPost->headers->set('Accept', 'application/json');
$resApiSvPost = $kernel->handle($reqApiSvPost);
assertTest($resApiSvPost->getStatusCode() === 201, "API POST /api/sinh-vien -> HTTP 201 Created");
$createdSv = json_decode($resApiSvPost->getContent(), true)['data'] ?? null;
$createdSvId = $createdSv['id'] ?? null;

// PUT update
if ($createdSvId) {
    $reqApiSvPut = Illuminate\Http\Request::create('/api/sinh-vien/' . $createdSvId, 'PUT', [
        'ma_sv' => $testMaSv,
        'ten' => 'Sinh viên API Đã Sửa',
        'gioi_tinh' => 'Nam',
        'tuoi' => 21,
        'dia_chi' => 'Đà Nẵng',
    ]);
    $reqApiSvPut->headers->set('Accept', 'application/json');
    $resApiSvPut = $kernel->handle($reqApiSvPut);
    assertTest($resApiSvPut->getStatusCode() === 200, "API PUT /api/sinh-vien/$createdSvId -> HTTP 200");

    // DELETE destroy
    $reqApiSvDel = Illuminate\Http\Request::create('/api/sinh-vien/' . $createdSvId, 'DELETE');
    $reqApiSvDel->headers->set('Accept', 'application/json');
    $resApiSvDel = $kernel->handle($reqApiSvDel);
    assertTest($resApiSvDel->getStatusCode() === 200, "API DELETE /api/sinh-vien/$createdSvId -> HTTP 200");
}

echo "\n----------------------------------------------------\n";
echo " KẾT QUẢ: $passCount Passed, $failCount Failed.\n";
echo "====================================================\n";
