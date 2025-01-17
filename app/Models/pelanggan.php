<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pelanggan extends Model
{
    protected $table = "pelanggan";
    protected $primaryKey = 'id_pelanggan';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'nama',
        'no_hp',
        'alamat',
        'limit',
        'dibuat_pada',
        'diperbarui_pada',
        
    ];
}
