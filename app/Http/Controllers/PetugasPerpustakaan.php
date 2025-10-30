<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Petugas;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\PetugasResource;
use Illuminate\Support\Facades\Request;
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

    public function getCurrentPetugas(Request $request) : PetugasResource {
        $petugas = Auth::user();
        \Log::info('Petugas Saat ini: ' , ['petugas' =>$petugas]);
        return new PetugasResource($petugas);
    }

    public function logout(Request $request) : JsonResponse {

        $petugas = Auth::user();
        $petugas->token = null;

        $petugas->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
    
}
