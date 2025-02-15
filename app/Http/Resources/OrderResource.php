<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
         
            "anggota" => [
                'nama' => $this->anggota->nama,
                'gambar_anggota' => $this->anggota->gambar_anggota,
            ],
               // akses relasi dari tabel detail_order. map buat koleksi (has many dll)
            "detail_order" => $this->detail_order->map(function ($detail) {
                return [
                    // akses relasi dari detail_order ke tabel buku.
                        "nama_buku" => $detail->buku->nama_buku,
                        "gambar_buku" => $detail->buku->gambar_buku,
                        "buku_dikembalikan" => $detail->buku_dikembalikan,
                        "is_telat" => $detail->is_telat,
                        "created_at" =>Carbon::parse($detail->created_at)->format('Y-m-d') ,
                        "updated_at" => $detail->updated_at,
                        "dikembalikan_pada" => Carbon::parse($detail->dikembalikan_pada)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
                ];
            }),
        ];
    }
}
