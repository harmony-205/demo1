<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SinhVienResource;
use App\Models\SinhVien;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SinhVienController extends Controller
{
    use ApiResponse;

    /**
     * Lấy danh sách sinh viên qua API (hỗ trợ phân trang, tìm kiếm, lọc theo lớp)
     */
    public function index(Request $request)
    {
        $keyword = $request->query('keyword');
        $lopHocId = $request->query('lop_hoc_id');
        $trangThai = $request->query('trang_thai');
        $gioiTinh = $request->query('gioi_tinh');
        $perPage = $request->query('per_page');

        $query = SinhVien::with('lopHoc')
            ->when($keyword, function ($q) use ($keyword) {
                $kw = '%' . $keyword . '%';
                $q->where(function ($sub) use ($kw) {
                    $sub->where('ten', 'like', $kw)
                        ->orWhere('ma_sv', 'like', $kw)
                        ->orWhere('email', 'like', $kw)
                        ->orWhere('dia_chi', 'like', $kw);
                });
            })
            ->when($lopHocId, fn ($q) => $q->where('lop_hoc_id', $lopHocId))
            ->when($gioiTinh, fn ($q) => $q->where('gioi_tinh', $gioiTinh))
            ->when(isset($trangThai), fn ($q) => $q->where('trang_thai', $trangThai))
            ->orderBy('id', 'desc');

        if ($perPage) {
            $sinhViens = $query->paginate((int) $perPage);
            return $this->success([
                'items' => SinhVienResource::collection($sinhViens),
                'pagination' => [
                    'current_page' => $sinhViens->currentPage(),
                    'last_page' => $sinhViens->lastPage(),
                    'per_page' => $sinhViens->perPage(),
                    'total' => $sinhViens->total(),
                ]
            ], 'Lấy danh sách sinh viên thành công.');
        }

        $sinhViens = $query->get();
        return $this->success(SinhVienResource::collection($sinhViens), 'Lấy danh sách sinh viên thành công.');
    }

    /**
     * Thêm mới sinh viên qua API
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ma_sv' => 'required|string|max:50|unique:sinh_viens,ma_sv',
            'ten' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:sinh_viens,email',
            'so_dien_thoai' => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'required|in:Nam,Nữ,Khác',
            'tuoi' => 'required|integer|min:16|max:100',
            'dia_chi' => 'nullable|string|max:255',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'trang_thai' => 'nullable|boolean',
        ], [
            'ma_sv.required' => 'Mã sinh viên không được để trống.',
            'ma_sv.unique' => 'Mã sinh viên này đã tồn tại.',
            'ten.required' => 'Họ và tên sinh viên không được để trống.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email sinh viên này đã tồn tại.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'tuoi.required' => 'Tuổi không được để trống.',
            'lop_hoc_id.exists' => 'Lớp học không tồn tại.',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Dữ liệu không hợp lệ.', 422);
        }

        $data = $validator->validated();
        $data['trang_thai'] = $request->has('trang_thai') ? (bool) $request->trang_thai : true;

        $sinhVien = SinhVien::create($data);
        $sinhVien->load('lopHoc');

        return $this->success(new SinhVienResource($sinhVien), 'Thêm mới sinh viên thành công.', 201);
    }

    /**
     * Xem chi tiết một sinh viên
     */
    public function show(SinhVien $sinhVien)
    {
        $sinhVien->load('lopHoc');
        return $this->success(new SinhVienResource($sinhVien), 'Lấy thông tin sinh viên thành công.');
    }

    /**
     * Cập nhật thông tin sinh viên qua API
     */
    public function update(Request $request, SinhVien $sinhVien)
    {
        $validator = Validator::make($request->all(), [
            'ma_sv' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sinh_viens', 'ma_sv')->ignore($sinhVien->id),
            ],
            'ten' => 'required|string|max:100',
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('sinh_viens', 'email')->ignore($sinhVien->id),
            ],
            'so_dien_thoai' => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'required|in:Nam,Nữ,Khác',
            'tuoi' => 'required|integer|min:16|max:100',
            'dia_chi' => 'nullable|string|max:255',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'trang_thai' => 'nullable|boolean',
        ], [
            'ma_sv.required' => 'Mã sinh viên không được để trống.',
            'ma_sv.unique' => 'Mã sinh viên này đã tồn tại.',
            'ten.required' => 'Họ và tên sinh viên không được để trống.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email sinh viên này đã tồn tại.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'tuoi.required' => 'Tuổi không được để trống.',
            'lop_hoc_id.exists' => 'Lớp học không tồn tại.',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Dữ liệu không hợp lệ.', 422);
        }

        $data = $validator->validated();
        if ($request->has('trang_thai')) {
            $data['trang_thai'] = (bool) $request->trang_thai;
        }

        $sinhVien->update($data);
        $sinhVien->load('lopHoc');

        return $this->success(new SinhVienResource($sinhVien), 'Cập nhật thông tin sinh viên thành công.');
    }

    /**
     * Xóa sinh viên qua API
     */
    public function destroy(SinhVien $sinhVien)
    {
        $sinhVien->delete();

        return $this->success(null, 'Sinh viên đã được xóa thành công.');
    }
}
