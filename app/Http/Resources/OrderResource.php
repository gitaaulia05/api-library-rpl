<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      
        return [
            "id_order" => $this->id_order,
            "id_petugas" => $this->id_petugas,
            "id_anggota" => $this->id_anggota,
            "detail_order" => $this->detail_order->map(function ($detail) {
                return [
                        "nama_buku" => $detail->buku->nama_buku,
                        "buku_dikembalikan" => $detail->buku_dikembalikan,
                ];
            }),
        ];
    }
}
