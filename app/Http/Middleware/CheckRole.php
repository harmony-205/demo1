<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Kiểm tra xem user có vai trò phù hợp trong danh sách cho phép hay không.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = auth()->user();

        if (!empty($roles) && !in_array($user->role, $roles)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập tài nguyên này.'
                ], 403);
            }

            return redirect()->route('lophoc.index')
                ->with('error', 'Bạn không có quyền truy cập chức năng này.');
        }

        return $next($request);
    }
}
