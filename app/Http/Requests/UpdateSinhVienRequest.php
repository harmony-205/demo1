<?php

namespace App\Http\Requests;

use App\Models\SinhVien;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSinhVienRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $sinhVien = $this->route('sinhvien') ?? $this->route('sinh_vien');
        $sinhVienId = $sinhVien instanceof SinhVien ? $sinhVien->id : $sinhVien;

        return [
            'ma_sv' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sinh_viens', 'ma_sv')->ignore($sinhVienId),
            ],
            'ten' => 'required|string|max:100',
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('sinh_viens', 'email')->ignore($sinhVienId),
            ],
            'so_dien_thoai' => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'required|in:Nam,Nữ,Khác',
            'tuoi' => 'required|integer|min:16|max:100',
            'dia_chi' => 'nullable|string|max:255',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'trang_thai' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ma_sv.required' => 'Mã sinh viên không được để trống.',
            'ma_sv.unique' => 'Mã sinh viên này đã tồn tại trong hệ thống.',
            'ten.required' => 'Họ và tên sinh viên không được để trống.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email sinh viên này đã tồn tại.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',
            'tuoi.required' => 'Tuổi không được để trống.',
            'tuoi.integer' => 'Tuổi phải là số nguyên.',
            'tuoi.min' => 'Tuổi phải từ 16 trở lên.',
            'tuoi.max' => 'Tuổi không hợp lệ.',
            'lop_hoc_id.exists' => 'Lớp học được chọn không tồn tại.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('trang_thai')) {
            $this->merge([
                'trang_thai' => filter_var($this->trang_thai, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
