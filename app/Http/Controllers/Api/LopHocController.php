<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LopHocResource;
use App\Models\LopHoc;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LopHocController extends Controller
{
    use ApiResponse;

    /**
     * Lấy danh sách lớp học (có phân trang hoặc lấy tất cả, hỗ trợ tìm kiếm)
     */
    public function index(Request $request)
    {
        $keyword = $request->query('keyword');
        $trangThai = $request->query('trang_thai');
        $perPage = $request->query('per_page');

        $query = LopHoc::withCount('sinhViens')
            ->when($keyword, function ($q) use ($keyword) {
                $kw = '%' . $keyword . '%';
                $q->where(function ($sub) use ($kw) {
                    $sub->where('ten_lop', 'like', $kw)
                        ->orWhere('ma_lop', 'like', $kw)
                        ->orWhere('giao_vien', 'like', $kw);
                });
            })
            ->when(isset($trangThai), function ($q) use ($trangThai) {
                $q->where('trang_thai', $trangThai);
            })
            ->orderBy('id', 'desc');

        if ($perPage) {
            $lopHocs = $query->paginate((int) $perPage);
            return $this->success([
                'items' => LopHocResource::collection($lopHocs),
                'pagination' => [
                    'current_page' => $lopHocs->currentPage(),
                    'last_page' => $lopHocs->lastPage(),
                    'per_page' => $lopHocs->perPage(),
                    'total' => $lopHocs->total(),
                ]
            ], 'Lấy danh sách lớp học thành công.');
        }

        $lopHocs = $query->get();
        return $this->success(LopHocResource::collection($lopHocs), 'Lấy danh sách lớp học thành công.');
    }

    /**
     * Thêm mới lớp học qua API
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_lop' => 'required|string|max:100',
            'ma_lop' => 'required|string|max:50|unique:lop_hocs,ma_lop',
            'giao_vien' => 'required|string|max:100',
            'so_dien_thoai_gvcn' => 'nullable|string|max:20',
            'ghi_chu' => 'nullable|string',
            'si_so' => 'required|integer|min:1|max:200',
            'trang_thai' => 'nullable|boolean',
        ], [
            'ten_lop.required' => 'Tên lớp học không được để trống.',
            'ma_lop.required' => 'Mã lớp học không được để trống.',
            'ma_lop.unique' => 'Mã lớp học này đã tồn tại.',
            'giao_vien.required' => 'Tên giáo viên chủ nhiệm không được để trống.',
            'si_so.required' => 'Sĩ số không được để trống.',
            'si_so.min' => 'Sĩ số phải từ 1 trở lên.',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Dữ liệu không hợp lệ.', 422);
        }

        $data = $validator->validated();
        $data['trang_thai'] = $request->has('trang_thai') ? (bool) $request->trang_thai : true;

        $lopHoc = LopHoc::create($data);

        return $this->success(new LopHocResource($lopHoc), 'Tạo lớp học thành công.', 201);
    }

    /**
     * Xem chi tiết một lớp học
     */
    public function show(LopHoc $lopHoc)
    {
        $lopHoc->loadCount('sinhViens');
        return $this->success(new LopHocResource($lopHoc), 'Lấy thông tin lớp học thành công.');
    }

    /**
     * Cập nhật thông tin lớp học qua API
     */
    public function update(Request $request, LopHoc $lopHoc)
    {
        $validator = Validator::make($request->all(), [
            'ten_lop' => 'required|string|max:100',
            'ma_lop' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lop_hocs', 'ma_lop')->ignore($lopHoc->id),
            ],
            'giao_vien' => 'required|string|max:100',
            'so_dien_thoai_gvcn' => 'nullable|string|max:20',
            'ghi_chu' => 'nullable|string',
            'si_so' => 'required|integer|min:1|max:200',
            'trang_thai' => 'nullable|boolean',
        ], [
            'ten_lop.required' => 'Tên lớp học không được để trống.',
            'ma_lop.required' => 'Mã lớp học không được để trống.',
            'ma_lop.unique' => 'Mã lớp học này đã tồn tại.',
            'giao_vien.required' => 'Tên giáo viên chủ nhiệm không được để trống.',
            'si_so.required' => 'Sĩ số không được để trống.',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Dữ liệu không hợp lệ.', 422);
        }

        $data = $validator->validated();
        if ($request->has('trang_thai')) {
            $data['trang_thai'] = (bool) $request->trang_thai;
        }

        $lopHoc->update($data);

        return $this->success(new LopHocResource($lopHoc), 'Cập nhật thông tin lớp học thành công.');
    }

    /**
     * Xóa lớp học qua API
     */
    public function destroy(LopHoc $lopHoc)
    {
        $lopHoc->delete();

        return $this->success(null, 'Lớp học đã được xóa thành công.');
    }
}
