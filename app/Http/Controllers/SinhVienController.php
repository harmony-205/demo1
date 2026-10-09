<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinhVienController extends Controller
{
    private array $defaultSinhviens = [
        ['id' => 1, 'name' => 'Nguyen Van A', 'ten' => 'Nguyen Van A', 'age' => 20, 'tuoi' => 20, 'diachi' => 'Hà Nội'],
        ['id' => 2, 'name' => 'Tran Thi B', 'ten' => 'Tran Thi B', 'age' => 21, 'tuoi' => 21, 'diachi' => 'Hải Phòng'],
        ['id' => 3, 'name' => 'Le Van C', 'ten' => 'Le Van C', 'age' => 22, 'tuoi' => 22, 'diachi' => 'Đà Nẵng'],
        ['id' => 4, 'name' => 'Pham Thi D', 'ten' => 'Pham Thi D', 'age' => 23, 'tuoi' => 23, 'diachi' => 'Cần Thơ'],
        ['id' => 5, 'name' => 'Hoang Van E', 'ten' => 'Hoang Van E', 'age' => 24, 'tuoi' => 24, 'diachi' => 'TP. Hồ Chí Minh'],
    ];

    /**
     * Lấy danh sách sinh viên từ session (hoặc giá trị mặc định)
     */
    private function getList(): array
    {
        if (!session()->has('sinhviens')) {
            session(['sinhviens' => $this->defaultSinhviens]);
        }
        return session('sinhviens', $this->defaultSinhviens);
    }

    /**
     * Lưu danh sách sinh viên vào session
     */
    private function saveList(array $list): void
    {
        session(['sinhviens' => array_values($list)]);
    }

    /**
     * Hiển thị danh sách sinh viên
     */
    public function index()
    {
        return view('sinhvien.index', [
            'title' => 'Danh sách sinh viên',
            'sinhviens' => $this->getList(),
        ]);
    }

    /**
     * Xem chi tiết thông tin sinh viên
     */
    public function show($id = null)
    {
        // Hỗ trợ cả trường hợp gọi hàm show($hoten, $tuoi) từ bài tập cũ
        if (!is_numeric($id) && is_string($id)) {
            $tuoi = func_num_args() > 1 ? func_get_arg(1) : 0;
            return "Đây là sinh viên có tên = $id và tuổi = $tuoi";
        }

        $list = $this->getList();
        $sinhvien = collect($list)->firstWhere('id', (int) $id);

        if (!$sinhvien) {
            return redirect()->route('sinhvien.index')->with('error', 'Không tìm thấy sinh viên!');
        }

        return view('sinhvien.show', [
            'title' => 'Chi tiết sinh viên: ' . ($sinhvien['ten'] ?? $sinhvien['name']),
            'sinhvien' => $sinhvien,
        ]);
    }

    /**
     * Form thêm mới sinh viên (Resource chuẩn: create)
     */
    public function create()
    {
        return view('sinhvien.add', [
            'title' => 'Thêm sinh viên',
        ]);
    }

    /**
     * Alias cho route cũ /sinhvien/add
     */
    public function add()
    {
        return $this->create();
    }

    /**
     * Lưu thông tin sinh viên mới
     */
    public function store(Request $request)
    {
        // Chuẩn hóa tên trường nếu form gửi name/age thay vì ten/tuoi
        if (!$request->has('ten') && $request->has('name')) {
            $request->merge(['ten' => $request->input('name')]);
        }
        if (!$request->has('tuoi') && $request->has('age')) {
            $request->merge(['tuoi' => $request->input('age')]);
        }

        $validated = $request->validate([
            'ten' => 'required|string|max:255',
            'tuoi' => 'required|integer|min:1|max:120',
            'diachi' => 'nullable|string|max:255',
        ], [
            'ten.required' => 'Tên sinh viên không được để trống.',
            'tuoi.required' => 'Tuổi không được để trống.',
            'tuoi.integer' => 'Tuổi phải là số nguyên.',
            'tuoi.min' => 'Tuổi phải từ 1 trở lên.',
            'tuoi.max' => 'Tuổi không hợp lệ.',
        ]);

        $list = $this->getList();
        $newId = count($list) > 0 ? max(array_column($list, 'id')) + 1 : 1;

        $ten = trim($validated['ten']);
        $tuoi = (int) $validated['tuoi'];
        $diachi = trim($validated['diachi'] ?? '');

        $list[] = [
            'id' => $newId,
            'name' => $ten,
            'ten' => $ten,
            'age' => $tuoi,
            'tuoi' => $tuoi,
            'diachi' => $diachi,
        ];

        $this->saveList($list);

        return redirect()->route('sinhvien.index')->with('success', 'Thêm sinh viên thành công!');
    }

    /**
     * Form chỉnh sửa sinh viên
     */
    public function edit($id)
    {
        $list = $this->getList();
        $sinhvien = collect($list)->firstWhere('id', (int) $id);

        if (!$sinhvien) {
            return redirect()->route('sinhvien.index')->with('error', 'Không tìm thấy sinh viên cần sửa!');
        }

        return view('sinhvien.edit', [
            'title' => 'Sửa sinh viên: ' . ($sinhvien['ten'] ?? $sinhvien['name']),
            'sinhvien' => $sinhvien,
        ]);
    }

    /**
     * Cập nhật thông tin sinh viên
     */
    public function update(Request $request, $id)
    {
        if (!$request->has('ten') && $request->has('name')) {
            $request->merge(['ten' => $request->input('name')]);
        }
        if (!$request->has('tuoi') && $request->has('age')) {
            $request->merge(['tuoi' => $request->input('age')]);
        }

        $validated = $request->validate([
            'ten' => 'required|string|max:255',
            'tuoi' => 'required|integer|min:1|max:120',
            'diachi' => 'nullable|string|max:255',
        ], [
            'ten.required' => 'Tên sinh viên không được để trống.',
            'tuoi.required' => 'Tuổi không được để trống.',
            'tuoi.integer' => 'Tuổi phải là số nguyên.',
            'tuoi.min' => 'Tuổi phải từ 1 trở lên.',
            'tuoi.max' => 'Tuổi không hợp lệ.',
        ]);

        $list = $this->getList();
        $found = false;

        foreach ($list as &$item) {
            if ($item['id'] == (int) $id) {
                $ten = trim($validated['ten']);
                $tuoi = (int) $validated['tuoi'];
                $diachi = trim($validated['diachi'] ?? '');

                $item['name'] = $ten;
                $item['ten'] = $ten;
                $item['age'] = $tuoi;
                $item['tuoi'] = $tuoi;
                $item['diachi'] = $diachi;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            return redirect()->route('sinhvien.index')->with('error', 'Không tìm thấy sinh viên để cập nhật!');
        }

        $this->saveList($list);

        return redirect()->route('sinhvien.index')->with('success', 'Cập nhật sinh viên thành công!');
    }

    /**
     * Xóa sinh viên (Resource chuẩn: destroy)
     */
    public function destroy($id)
    {
        $list = $this->getList();
        $newList = array_filter($list, fn ($item) => $item['id'] != (int) $id);

        if (count($newList) === count($list)) {
            return redirect()->route('sinhvien.index')->with('error', 'Không tìm thấy sinh viên để xóa!');
        }

        $this->saveList($newList);

        return redirect()->route('sinhvien.index')->with('success', 'Xóa sinh viên thành công!');
    }

    /**
     * Alias cho delete($id)
     */
    public function delete($id)
    {
        return $this->destroy($id);
    }

    /**
     * Reset danh sách sinh viên về dữ liệu mẫu ban đầu
     */
    public function reset()
    {
        session()->forget('sinhviens');
        return redirect()->route('sinhvien.index')->with('success', 'Đã reset về dữ liệu mẫu!');
    }
}
