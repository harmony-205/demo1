<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use DateRangeError;
use Illuminate\Http\Request;

class LopHocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($perPage = 10)
    {
        //  
        $perPage = request()->query('perPage', 10); 
        $lopHocs = LopHoc::paginate($perPage)->withQueryString(); // Lấy số lượng bản ghi trên mỗi trang từ query parameter 'perPage', mặc định là 10
        return view('lop_hocs.index', ['lopHocs' => $lopHocs]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lop_hocs.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'ten_lop' => 'required|string|max:255',
            'ma_lop' => 'required|string|max:6|unique:lop_hocs,ma_lop',
            'giao_vien' => 'required|string|max:255',
            'so_dien_thoai_gvcn' => 'required|string|max:255',
            'ghi_chu' => 'nullable|string|max:500',
            'si_so' => 'required|integer|min:1',
            'trang_thai' => 'required|boolean',
        ]);
        try {
            LopHoc::create($request->all());
            return redirect()->route('lophoc.index')->with('success', 'Lớp học đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo lớp học: ' . $e->getMessage()]);
        }
        
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LopHoc $lopHoc)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $lopHoc = LopHoc::findOrFail($id);
        return view('lop_hocs.edit', ['lopHoc' => $lopHoc]);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LopHoc $lopHoc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LopHoc $lopHoc)
    {
        //
    }
}
