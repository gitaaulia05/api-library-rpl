<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\PetugasResource;
use App\Http\Requests\PetugasLoginRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PetugasPerpustakaan extends Controller
{
    public function login(PetugasLoginRequest $request): PetugasResource {
        $data = $request->validated();

        $petugas = Petugas::where('username' , $data['username'])->first();
        
        if(!$petugas || !Hash::check($data['password'], $petugas->password)){
            throw new HttpResponseException(response([
                "errors" =>[
                    "message" => [
                        "username atau password salah"
                    ]
                ]
                    ], 401));
        }

        $petugas->token = Str::uuid()->toString();
        $petugas->save();

        return new PetugasResource($petugas);
    }
}
