<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Menu::query();

        // 1. Bộ lọc tìm kiếm theo từ khóa (Tên hiển thị, slug)
        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('tenhienthi', 'like', "%{$keyword}%")
                  ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }

        // 2. Bộ lọc theo trạng thái (1: Hiển thị, 0: Ẩn)
        if ($request->filled('trangthai') && in_array($request->input('trangthai'), ['0', '1'], true)) {
            $query->where('trangthai', $request->input('trangthai'));
        }

        // 3. Sắp xếp (Sorting)
        $allowedSortColumns = ['id', 'tenhienthi', 'slug', 'trangthai', 'created_at'];
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

        $menus = $query->paginate($perPage)->withQueryString();

        return view('menus.index', compact('menus', 'sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tenhienthi' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug',
            'trangthai' => 'required|in:0,1',
        ], [
            'tenhienthi.required' => 'Vui lòng nhập tên hiển thị của menu.',
            'slug.required' => 'Vui lòng nhập slug cho menu.',
            'slug.unique' => 'Slug này đã tồn tại, vui lòng chọn slug khác.',
            'trangthai.required' => 'Vui lòng chọn trạng thái.',
        ]);

        try {
            Menu::create($request->all());
            return redirect()->route('menus.index')->with('success', 'Menu đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo menu: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menus.edit', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'tenhienthi' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $menu->id,
            'trangthai' => 'required|in:0,1',
        ], [
            'tenhienthi.required' => 'Vui lòng nhập tên hiển thị của menu.',
            'slug.required' => 'Vui lòng nhập slug cho menu.',
            'slug.unique' => 'Slug này đã tồn tại, vui lòng chọn slug khác.',
            'trangthai.required' => 'Vui lòng chọn trạng thái.',
        ]);

        try {
            $menu->update($request->all());
            return redirect()->route('menus.index')->with('success', 'Cập nhật menu thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật menu: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();
            return redirect()->route('menus.index')->with('success', 'Xóa menu thành công.');
        } catch (\Exception $e) {
            return redirect()->route('menus.index')->withErrors(['error' => 'Đã xảy ra lỗi khi xóa menu: ' . $e->getMessage()]);
        }
    }
}
