<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LopHoc extends Model
{
    //
    protected $table = 'lop_hocs';
    protected $fillable = [
        'ten_lop',
        'ma_lop',
        'giao_vien',
        'ghi_chu',
        'si_so',
        'trang_thai',
        'so_dien_thoai_gvcn'
    ];
    
}
