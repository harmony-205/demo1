<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSinhVienRequest;
use App\Http\Requests\UpdateSinhVienRequest;
use App\Models\LopHoc;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SinhVienController extends Controller
{
    private const SORTABLE = ['id', 'ma_sv', 'ten', 'tuoi', 'gioi_tinh', 'lop_hoc_id', 'trang_thai', 'created_at'];
    private const PER_PAGE_OPTIONS = [10, 20, 50, 100];

    /**
     * Hiển thị danh sách sinh viên có phân trang, bộ lọc và sắp xếp
     */
    public function index(Request $request)
    {
        $filters = Validator::make($request->query(), [
            'keyword' => 'nullable|string|max:100',
            'lop_hoc_id' => 'nullable|integer',
            'gioi_tinh' => 'nullable|in:Nam,Nữ,Khác',
            'trang_thai' => 'nullable|in:0,1',
            'tuoi_min' => 'nullable|integer|min:0',
            'tuoi_max' => 'nullable|integer|min:0',
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE),
            'direction' => 'nullable|in:asc,desc',
            'perPage' => 'nullable|in:' . implode(',', self::PER_PAGE_OPTIONS),
        ])->valid();

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'desc';
        $perPage = (int) ($filters['perPage'] ?? 10);

        $sinhviens = SinhVien::query()
            ->with('lopHoc')
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $kw = '%' . $filters['keyword'] . '%';
                $query->where(function ($q) use ($kw) {
                    $q->where('ten', 'like', $kw)
                        ->orWhere('ma_sv', 'like', $kw)
                        ->orWhere('email', 'like', $kw)
                        ->orWhere('dia_chi', 'like', $kw)
                        ->orWhere('so_dien_thoai', 'like', $kw);
                });
            })
            ->when(!empty($filters['lop_hoc_id']), fn ($query) => $query->where('lop_hoc_id', $filters['lop_hoc_id']))
            ->when(isset($filters['gioi_tinh']), fn ($query) => $query->where('gioi_tinh', $filters['gioi_tinh']))
            ->when(isset($filters['trang_thai']), fn ($query) => $query->where('trang_thai', $filters['trang_thai']))
            ->when(isset($filters['tuoi_min']), fn ($query) => $query->where('tuoi', '>=', $filters['tuoi_min']))
            ->when(isset($filters['tuoi_max']), fn ($query) => $query->where('tuoi', '<=', $filters['tuoi_max']))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        $lopHocs = LopHoc::orderBy('ten_lop')->get();

        return view('sinhvien.index', [
            'sinhviens' => $sinhviens,
            'lopHocs' => $lopHocs,
            'filters' => $filters,
            'sort' => $sort,
            'direction' => $direction,
            'perPage' => $perPage,
            'sortable' => self::SORTABLE,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
        ]);
    }

    /**
     * Giao diện thêm mới sinh viên
     */
    public function create()
    {
        $lopHocs = LopHoc::where('trang_thai', true)->orderBy('ten_lop')->get();

        return view('sinhvien.create', [
            'lopHocs' => $lopHocs,
        ]);
    }

    /**
     * Lưu sinh viên mới vào cơ sở dữ liệu
     */
    public function store(StoreSinhVienRequest $request)
    {
        $data = $request->validated();
        $data['trang_thai'] = $request->has('trang_thai') ? (bool) $request->trang_thai : true;

        $sinhVien = SinhVien::create($data);

        return redirect()->route('sinhvien.index')
            ->with('success', 'Sinh viên "' . $sinhVien->ten . '" (' . $sinhVien->ma_sv . ') đã được tạo thành công.');
    }

    /**
     * Xem thông tin chi tiết sinh viên
     */
    public function show($id)
    {
        $sinhvien = SinhVien::with('lopHoc')->findOrFail($id);

        return view('sinhvien.show', [
            'sinhvien' => $sinhvien,
        ]);
    }

    /**
     * Giao diện chỉnh sửa thông tin sinh viên
     */
    public function edit($id)
    {
        $sinhvien = SinhVien::findOrFail($id);
        $lopHocs = LopHoc::where('trang_thai', true)->orderBy('ten_lop')->get();

        return view('sinhvien.edit', [
            'sinhvien' => $sinhvien,
            'lopHocs' => $lopHocs,
        ]);
    }

    /**
     * Cập nhật thông tin sinh viên trong cơ sở dữ liệu
     */
    public function update(UpdateSinhVienRequest $request, $id)
    {
        $sinhvien = SinhVien::findOrFail($id);
        $data = $request->validated();
        $data['trang_thai'] = $request->has('trang_thai') ? (bool) $request->trang_thai : false;

        $sinhvien->update($data);

        return redirect()->route('sinhvien.index')
            ->with('success', 'Thông tin sinh viên "' . $sinhvien->ten . '" đã được cập nhật thành công.');
    }

    /**
     * Xóa sinh viên
     */
    public function destroy($id)
    {
        $sinhvien = SinhVien::findOrFail($id);
        $ten = $sinhvien->ten;
        $sinhvien->delete();

        return back()->with('success', 'Sinh viên "' . $ten . '" đã được xóa thành công.');
    }
}
