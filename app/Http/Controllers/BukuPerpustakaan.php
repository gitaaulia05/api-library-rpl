<?php

namespace App\Http\Controllers;

use App\Models\buku;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BukuResource;
use App\Http\Resources\BukuCollection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BukuCreateRequest;
use App\Http\Requests\bukuUpdateRequest;

class BukuPerpustakaan extends Controller
{
    public function create(BukuCreateRequest $request): JsonResponse{
        $data = $request->validated();
        $data['id_buku'] = (String) Str::uuid();

        if($request->hasFile('gambar_buku')){

            // $image = $request->file('image');
            // $image->storeAs('public/posts', $image->hashName());

            $data['gambar_buku'] = $request->file('gambar_buku')->store('gambarBuku' , 'public');
        }

        $buku = new buku($data);
     
        $buku->save();

        return (new BukuResource($buku))->response()->setStatusCode(201);
    }

    public function search_data(Request $request) : BukuCollection{
        $buku = buku::all();
        $pageBuku = $request->input('page', 1);
        $size = $request->input('size' , 10);

        $buku = buku::query();
            $name = $request->input('nama_buku');
                   
                    if($name) {
                        $buku->where('nama_buku' , 'like' ,'%'.$name.'%');
                    }

        $buku = $buku->paginate(perPage : $size , page: $pageBuku);
        return  new BukuCollection($buku);
    }

    public function detail_data($slugNamaBuku): BukuResource {
        $buku = buku::where('slug' , $slugNamaBuku)->first();

        if(!$buku){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        " Buku tidak ditemukan"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return new BukuResource($buku);

    }
    public function updateBuku( BukuUpdateRequest $request , $slugNamaBuku): BukuResource
    {
        $buku = Buku::where('slug', $slugNamaBuku)->first();
    
        if(!$buku){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        " buku tidak ditemukan"
                    ]
                ]
            ])->setStatusCode(404));
        }


        $data = $request->validated();

        if ($request->hasFile('gambar_buku')) {
    
            if ($buku->gambar_buku && Storage::disk('public')->exists($buku->gambar_buku)) {
                Storage::disk('public')->delete($buku->gambar_buku);
            }
    
            $data['gambar_buku'] = $request->file('gambar_buku')->store('gambarBuku', 'public');
            // Path relatif sudah disimpan di $data['gambar_buku'].
          
        }
        // Update data buku
        $buku->fill($data);
        $buku->save();
    
        // Return response sukses dengan data yang diperbarui
        return (new BukuResource($buku));
    }
    
    

    public function deleteBuku($slugNamaBuku){
        $buku = buku::where('slug' , $slugNamaBuku )->first();

        if(!$buku){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        " Buku tidak ditemukan"
                    ]
                ]
            ])->setStatusCode(404));
        }

        if ($buku->gambar_buku && Storage::disk('public')->exists($buku->gambar_buku)) {
            Storage::disk('public')->delete($buku->gambar_buku);
        }

        $buku->delete();
        return response()->json([
            'data' =>true,
        ])->setStatusCode(200);
       
    }




}
