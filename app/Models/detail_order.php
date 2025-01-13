<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class detail_order extends Model
{
    protected $table = 'detail_order';
    protected $primaryKey = 'id_detail_order';
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
            'id_detail_order', 
            'id_order',
            'id_buku',
            'buku_dikembalikan'
    ];

    public function order() : belongsTo {
        return $this->BelongsTo(order::class , 'id_order' , 'id_order');
    }

    public function buku() : belongsTo {
        return $this->belongsTo(buku::class , 'id_buku' , 'id_buku');
    }
}
