<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SinhVienResource extends JsonResource
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
            'ma_sv' => $this->ma_sv,
            'ten' => $this->ten,
            'email' => $this->email,
            'so_dien_thoai' => $this->so_dien_thoai,
            'ngay_sinh' => $this->ngay_sinh?->format('Y-m-d'),
            'gioi_tinh' => $this->gioi_tinh,
            'tuoi' => $this->tuoi,
            'dia_chi' => $this->dia_chi,
            'lop_hoc_id' => $this->lop_hoc_id,
            'lop_hoc' => $this->whenLoaded('lopHoc', function () {
                return $this->lopHoc ? [
                    'id' => $this->lopHoc->id,
                    'ten_lop' => $this->lopHoc->ten_lop,
                    'ma_lop' => $this->lopHoc->ma_lop,
                    'giao_vien' => $this->lopHoc->giao_vien,
                ] : null;
            }, function () {
                return $this->lopHoc ? [
                    'id' => $this->lopHoc->id,
                    'ten_lop' => $this->lopHoc->ten_lop,
                    'ma_lop' => $this->lopHoc->ma_lop,
                ] : null;
            }),
            'trang_thai' => (bool) $this->trang_thai,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
