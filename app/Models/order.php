<?php

namespace App\Models;

use App\Models\detail_order;
use App\Models\anggota;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function anggota () : belongsTo {
        return $this->BelongsTo(anggota::class , 'id_anggota' , 'id_anggota');
    }

    

}
