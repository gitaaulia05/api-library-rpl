<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Petugas extends Model implements Authenticatable
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

    public function getAuthIdentifierName(){
            return 'username';
    }

    public function getAuthIdentifier(){
            return $this->id_petugas;
    }

    public function getAuthPassword(){
            return $this->password;
    }

    public function getAuthPasswordName(){
        return 'password';
    }

    public function getRememberToken(){
        return null;
    }

    public function setRememberToken($value){
    
    }

    public function getRememberTokenName(){
        return null;
    }

}
