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
        Schema::create('sinh_viens', function (Blueprint $table) {
            $table->id();
            $table->string('ma_sv')->unique();
            $table->string('ten');
            $table->string('email')->nullable();
            $table->string('so_dien_thoai', 20)->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->string('gioi_tinh', 10)->default('Nam');
            $table->unsignedInteger('tuoi')->default(18);
            $table->string('dia_chi')->nullable();
            $table->foreignId('lop_hoc_id')->nullable()->constrained('lop_hocs')->nullOnDelete();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinh_viens');
    }
};
