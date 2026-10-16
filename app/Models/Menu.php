<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Các vị trí hiển thị menu trên layout
    public const VI_TRI = [
        'header' => 'Header',
        'sidebar' => 'Sidebar',
    ];

    protected $fillable = [
        'ten',
        'url',
        'vi_tri',
        'nhom',
        'role',
        'thu_tu',
        'trang_thai',
        'slug',
        'tenhienthi',
        'trangthai',
    ];

    protected static function booted(): void
    {
        static::saving(function (Menu $menu) {
            if (empty($menu->slug)) {
                $menu->slug = \Illuminate\Support\Str::slug($menu->ten ?: 'menu') . '-' . substr(uniqid(), -5);
            }
            if (empty($menu->tenhienthi)) {
                $menu->tenhienthi = $menu->ten;
            }
            if (!isset($menu->trangthai)) {
                $menu->trangthai = $menu->trang_thai ?? true;
            }
        });
    }

    /**
     * Menu này có trỏ tới trang đang xem không (để tô sáng).
     */
    public function dangChon(): bool
    {
        $path = trim((string) parse_url($this->url, PHP_URL_PATH), '/');

        if ($path === '') {
            return $this->url === '/' && request()->is('/');
        }

        return request()->is($path, $path . '/*');
    }
}
