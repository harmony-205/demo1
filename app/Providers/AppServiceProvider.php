<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrap();

        // View composer: mỗi lần render layout thì nạp menu từ CSDL theo vai trò của người dùng
        View::composer('layouts.layoutmaster', function ($view) {
            $user = auth()->user();
            $query = Menu::where('trang_thai', true);

            if (!$user) {
                $query->where(function ($q) {
                    $q->whereNull('role')->orWhere('role', 'all');
                });
            } elseif (!$user->isAdmin()) {
                // Giảng viên chỉ xem được menu dùng chung hoặc dành riêng cho giảng viên
                $query->where(function ($q) {
                    $q->whereNull('role')->orWhereIn('role', ['all', 'teacher']);
                });
            }

            $view->with('menuHienThi', $query
                ->orderBy('thu_tu')
                ->orderBy('id')
                ->get()
                ->groupBy('vi_tri'));
        });
    }
}
