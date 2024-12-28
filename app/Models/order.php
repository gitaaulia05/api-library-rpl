<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $table = "orders";
    protected $primaryKey = "id_order";
    protected $keyType = "string";

    public $incrementing = false;

    protected $fillable = [
        "id_order",
        "id_buku",
        "id_petugas",
        "id_anggota"
    ];
}
