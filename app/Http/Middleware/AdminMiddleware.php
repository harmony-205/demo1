<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Kiểm tra xem người dùng đã đăng nhập và có quyền Admin hay không.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        if (!auth()->user()->isAdmin()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền thực hiện hành động này. Yêu cầu quyền Quản trị viên (Admin).'
                ], 403);
            }

            return redirect()->route('lophoc.index')
                ->with('error', 'Bạn không có quyền truy cập chức năng này (chỉ dành cho Quản trị viên).');
        }

        return $next($request);
    }
}
