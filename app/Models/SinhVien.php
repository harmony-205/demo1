<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinh_viens';

    protected $fillable = [
        'ma_sv',
        'ten',
        'email',
        'so_dien_thoai',
        'ngay_sinh',
        'gioi_tinh',
        'tuoi',
        'dia_chi',
        'lop_hoc_id',
        'trang_thai',
    ];

    protected function casts(): array
    {
        return [
            'ngay_sinh' => 'date',
            'trang_thai' => 'boolean',
            'tuoi' => 'integer',
        ];
    }

    /**
     * Quan hệ lớp học của sinh viên
     */
    public function lopHoc(): BelongsTo
    {
        return $this->belongsTo(LopHoc::class, 'lop_hoc_id');
    }
}
