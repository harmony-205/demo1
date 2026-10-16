<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Hiển thị thông tin tài khoản cá nhân
     */
    public function index()
    {
        $user = Auth::user();

        return view('profile.index', [
            'user' => $user,
        ]);
    }

    /**
     * Cập nhật thông tin tài khoản cá nhân
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());

        return redirect()->route('profile.index')->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }

    /**
     * Đổi mật khẩu tài khoản cá nhân
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
