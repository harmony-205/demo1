<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('teacher')->after('email'); // 'admin' hoặc 'teacher'
            $table->string('so_dien_thoai', 20)->nullable()->after('role');
            $table->boolean('trang_thai')->default(true)->after('so_dien_thoai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'so_dien_thoai', 'trang_thai']);
        });
    }
};
