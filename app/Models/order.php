<?php

namespace App\Models;

use App\Models\order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class order extends Model
{
    protected $table = "orders";
    protected $primaryKey = "id_order";
    protected $keyType = "string";

    public $incrementing = false;

    protected $fillable = [
        "id_order",
        "id_petugas",
        "id_anggota"
    ];

    public function detail_order() : hasMany {
        return $this->hasMany(detail_order::class , 'id_order' , 'id_order');
    }

}
