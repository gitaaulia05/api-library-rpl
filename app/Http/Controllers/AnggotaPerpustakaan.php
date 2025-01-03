<?php

namespace App\Http\Controllers;

use App\Models\anggota;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\AnggotaResource;
use App\Http\Resources\AnggotaCollection;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\AnggotaCreateRequest;

class AnggotaPerpustakaan extends Controller
{
    public function tambah_anggota(AnggotaCreateRequest $request) : JsonResponse{
     
        $data = $request->validated();
        $data['id_anggota'] = (String) Str::uuid();

        if($request->hasFile('gambar_anggota')){
            $data['gambar_anggota'] = $request->file('gambar_anggota')->store('gambarAnggota' , 'public');
        }

        $anggota = new anggota($data);
        $anggota->save();

        return (new AnggotaResource($anggota))->response()->setStatusCode(201);

        }

        public function search_anggota(Request $request) : AnggotaCollection {
            $pageBuku = $request->input('page' , 1);
            $size = $request->input('size' , 10);

            $anggota = anggota::query();

            $anggota->where(function (Builder $query) use ($request){

                $nama = $request->input('nama');

                if($nama) {
                    $query->where('nama' , 'like' , '%'. $nama. '%');
                }
            });

            $anggotas = $anggota->paginate(perPage : $size , page : $pageBuku);
            return new AnggotaCollection($anggotas);

        }



}
