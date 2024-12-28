<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class anggota extends Model
{
    use Sluggable;

    protected $table = "anggotas";
    protected $primaryKey = "id_anggota";
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "id_anggota",
        "slug",
        "nama",
        "gambar_anggota",
        "email", 
        "credit_anggota"
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama',
                'onUpdate' => true,
            ]
        ];
    }
    
}
