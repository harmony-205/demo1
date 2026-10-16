<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user instanceof User ? $user->id : $user;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_TEACHER])],
            'so_dien_thoai' => 'nullable|string|max:20',
            'trang_thai' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'password.min' => 'Mật khẩu phải có tối thiểu 6 ký tự nếu muốn thay đổi.',
            'role.required' => 'Vui lòng chọn vai trò (Admin hoặc Giảng viên).',
            'role.in' => 'Vai trò không hợp lệ.',
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
