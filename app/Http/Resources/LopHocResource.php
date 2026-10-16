<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LopHocResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ten_lop' => $this->ten_lop,
            'ma_lop' => $this->ma_lop,
            'giao_vien' => $this->giao_vien,
            'so_dien_thoai_gvcn' => $this->so_dien_thoai_gvcn,
            'ghi_chu' => $this->ghi_chu,
            'si_so' => $this->si_so,
            'so_sinh_vien' => $this->sinh_viens_count ?? $this->sinhViens()->count(),
            'trang_thai' => (bool) $this->trang_thai,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
