<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Petugas extends Model
{
    use HasFactory;

    protected $table = "petugas";
    protected $primaryKey = 'id_petugas';
    protected $keyType = 'string';

    protected $fillable = [
        'id_petugas',
        'username',
        'password',
        'token',
        
    ];

}
