<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use Illuminate\Http\Request;

class LopHocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LopHoc::query();

        // 1. Bộ lọc tìm kiếm theo từ khóa (Mã lớp, Tên lớp, Giáo viên, SĐT)
        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('ma_lop', 'like', "%{$keyword}%")
                  ->orWhere('ten_lop', 'like', "%{$keyword}%")
                  ->orWhere('giao_vien', 'like', "%{$keyword}%")
                  ->orWhere('so_dien_thoai_gvcn', 'like', "%{$keyword}%");
            });
        }

        // 2. Bộ lọc theo trạng thái (1: Hoạt động, 0: Khóa)
        if ($request->filled('trang_thai') && in_array($request->input('trang_thai'), ['0', '1'], true)) {
            $query->where('trang_thai', $request->input('trang_thai'));
        }

        // 3. Sắp xếp (Sorting)
        $allowedSortColumns = ['id', 'ma_lop', 'ten_lop', 'giao_vien', 'si_so', 'trang_thai', 'created_at'];
        $sortBy = $request->input('sort_by', 'id');
        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'id';
        }

        $sortDirection = strtolower($request->input('sort_direction', 'desc'));
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortBy, $sortDirection);

        // 4. Phân trang
        $perPage = (int) $request->input('perPage', 10);
        if (!in_array($perPage, [5, 10, 20, 50, 100], true)) {
            $perPage = 10;
        }

        $lopHocs = $query->paginate($perPage)->withQueryString();

        return view('lop_hocs.index', compact('lopHocs', 'sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lop_hocs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_lop' => 'required|string|max:255',
            'ma_lop' => 'required|string|max:20|unique:lop_hocs,ma_lop',
            'giao_vien' => 'required|string|max:255',
            'so_dien_thoai_gvcn' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string|max:500',
            'si_so' => 'required|integer|min:1',
            'trang_thai' => 'required|in:0,1',
        ]);

        try {
            LopHoc::create($request->all());
            return redirect()->route('lophocs.index')->with('success', 'Lớp học đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo lớp học: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $lopHoc = LopHoc::findOrFail($id);
        return view('lop_hocs.edit', compact('lopHoc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $lopHoc = LopHoc::findOrFail($id);
        return view('lop_hocs.edit', compact('lopHoc'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $lopHoc = LopHoc::findOrFail($id);
        $request->validate([
            'ten_lop' => 'required|string|max:255',
            'ma_lop' => 'required|string|max:20|unique:lop_hocs,ma_lop,' . $lopHoc->id,
            'giao_vien' => 'required|string|max:255',
            'so_dien_thoai_gvcn' => 'nullable|string|max:255',
            'ghi_chu' => 'nullable|string|max:500',
            'si_so' => 'required|integer|min:1',
            'trang_thai' => 'required|in:0,1',
        ]);

        try {
            $lopHoc->update($request->all());
            return redirect()->route('lophocs.index')->with('success', 'Cập nhật lớp học thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật lớp học: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $lopHoc = LopHoc::findOrFail($id);
            $lopHoc->delete();
            return redirect()->route('lophocs.index')->with('success', 'Xóa lớp học thành công.');
        } catch (\Exception $e) {
            return redirect()->route('lophocs.index')->withErrors(['error' => 'Đã xảy ra lỗi khi xóa lớp học: ' . $e->getMessage()]);
        }
    }
}
