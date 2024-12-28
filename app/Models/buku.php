<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;


class buku extends Model
{
    use Sluggable;
    protected $table = "bukus";
    protected $primaryKey = "id_buku";
    protected $keyType = "string";

    public $incrementing = false;


    protected $fillable = [
        "id_buku",
        "slug",
        "nama_buku",
        "gambar_buku",
        "gambar_qr",
        "nama_penulis",
        "nama_penerbit",
        "jumlah_buku",
        "buku_tersedia",
        "tahun_terbit",
       "created_at",
       "updated_at"
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama_buku',
                'onUpdate' => true,
            ]
        ];
    }
    
}
