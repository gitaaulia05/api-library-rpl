<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        return [
            "id_buku" => $this->id_buku,
            "slug"=> $this->slug,
            "nama_buku"=> $this->nama_buku,
            "gambar_buku"=> $this->gambar_buku,
            "gambar_qr"=> $this->gambar_qr,
            "nama_penulis"=> $this->nama_penulis,
            "nama_penerbit"=> $this->nama_penerbit,
            "jumlah_buku"=> $this->jumlah_buku,
            "buku_tersedia"=> $this->buku_tersedia,
            "tahun_terbit"=> $this->tahun_terbit,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
           
        ];
    }
}
