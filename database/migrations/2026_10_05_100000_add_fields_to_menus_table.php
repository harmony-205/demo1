<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'ten')) {
                $table->string('ten', 100)->nullable()->after('id');
            }
            if (!Schema::hasColumn('menus', 'url')) {
                $table->string('url', 255)->nullable()->after('ten');
            }
            if (!Schema::hasColumn('menus', 'vi_tri')) {
                $table->string('vi_tri', 50)->nullable()->after('url');
            }
            if (!Schema::hasColumn('menus', 'nhom')) {
                $table->string('nhom', 100)->nullable()->after('vi_tri');
            }
            if (!Schema::hasColumn('menus', 'thu_tu')) {
                $table->integer('thu_tu')->default(0)->after('nhom');
            }
            if (!Schema::hasColumn('menus', 'trang_thai')) {
                $table->boolean('trang_thai')->default(true)->after('thu_tu');
            }
        });

        if (!app()->environment('testing')) {
            // Cập nhật dữ liệu từ các cột cũ sang các cột mới mà không làm mất dữ liệu gốc
            if (Schema::hasColumn('menus', 'tenhienthi')) {
                DB::statement("UPDATE menus SET ten = tenhienthi WHERE ten IS NULL OR ten = ''");
            }
            if (Schema::hasColumn('menus', 'trangthai')) {
                DB::statement("UPDATE menus SET trang_thai = trangthai WHERE trang_thai IS NULL");
            }

            // Cấu hình vị trí và liên kết cho các menu có sẵn của người dùng
            $map = [
                1 => ['url' => '/', 'vi_tri' => 'header', 'nhom' => null, 'thu_tu' => 1],
                2 => ['url' => '#', 'vi_tri' => 'header', 'nhom' => null, 'thu_tu' => 2],
                3 => ['url' => '#', 'vi_tri' => 'header', 'nhom' => null, 'thu_tu' => 3],
                4 => ['url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục', 'thu_tu' => 4],
                5 => ['url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục', 'thu_tu' => 5],
                6 => ['url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục', 'thu_tu' => 6],
                7 => ['url' => '#', 'vi_tri' => 'header', 'nhom' => null, 'thu_tu' => 7],
            ];

            foreach ($map as $id => $val) {
                DB::table('menus')->where('id', $id)->update($val);
            }

            // Bổ sung các menu chức năng hệ thống còn thiếu để thanh điều hướng hoạt động đầy đủ
            $extraMenus = [
                ['ten' => 'Sinh viên', 'url' => '/sinhvien', 'vi_tri' => 'sidebar', 'nhom' => 'Quản trị', 'thu_tu' => 8, 'trang_thai' => true, 'slug' => 'sinh-vien', 'tenhienthi' => 'Sinh viên', 'trangthai' => true],
                ['ten' => 'Quản lý lớp học', 'url' => '/lophoc', 'vi_tri' => 'sidebar', 'nhom' => 'Quản trị', 'thu_tu' => 9, 'trang_thai' => true, 'slug' => 'quan-ly-lop-hoc', 'tenhienthi' => 'Quản lý lớp học', 'trangthai' => true],
                ['ten' => 'Quản lý menu', 'url' => '/menu', 'vi_tri' => 'sidebar', 'nhom' => 'Quản trị', 'thu_tu' => 10, 'trang_thai' => true, 'slug' => 'quan-ly-menu', 'tenhienthi' => 'Quản lý menu', 'trangthai' => true],
            ];

            foreach ($extraMenus as $extra) {
                $exists = DB::table('menus')->where('url', $extra['url'])->exists();
                if (!$exists) {
                    DB::table('menus')->insert(array_merge($extra, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]));
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['ten', 'url', 'vi_tri', 'nhom', 'thu_tu', 'trang_thai']);
        });
    }
};
