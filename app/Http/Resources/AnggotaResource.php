<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnggotaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id_anggota' => $this->id_anggota,
            'slug' => $this->slug,
            'nama' => $this->nama,
            'gambar_anggota' => $this->gambar_anggota,
            'email' => $this->email,
            'credit_anggota' => $this->credit_anggota,
        
           

        ];
    }
}
