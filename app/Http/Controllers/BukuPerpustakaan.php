<?php

namespace App\Http\Controllers;

use Log;
use App\Models\buku;
use App\Models\order;
use App\Models\anggota;
use App\Models\Petugas;
use Illuminate\Support\Str;
use App\Models\detail_order;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use App\Http\Resources\BukuResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;

use App\Http\Resources\BukuCollection;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BukuCreateRequest;
use App\Http\Requests\bukuUpdateRequest;
use App\Http\Requests\OrderCreateRequest;
use Illuminate\Database\Eloquent\Builder;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Exceptions\HttpResponseException;


class BukuPerpustakaan extends Controller
{


    public function data(){
        $data  = [
            'nama' => "gita "
        ];

        return response()->json($data);
    }
    
    public function create(BukuCreateRequest $request): JsonResponse{
        $data = $request->validated();
        $data['id_buku'] = (String) Str::uuid();

        $data['slug'] = SlugService::createSlug(Buku::class , 'slug' , $data['nama_buku']);
        $qr = QrCode::format('png')->generate('pinjam-buku/'.$data['slug']);
        $qrName = $data['slug'].'.png';
        $qrPath = 'barcode/' . $qrName;
        Storage::disk('public')->put($qrPath, $qr);

        $data['gambar_qr'] =$qrPath;

        if($request->hasFile('gambar_buku')){
            $data['gambar_buku'] = $request->file('gambar_buku')->store('gambarBuku' , 'public');
        }
        $buku = new buku($data);
        $buku->save();
        return (new BukuResource($buku))->response()->setStatusCode(201);
    }

    public function search_data(Request $request) : BukuCollection{
        $pageBuku = $request->input('page', 1);
        $size = $request->input('size' , 15);

        $buku = buku::query();

        $name = $request->input('nama_buku');
        $buku_tersedia = $request->input('buku_tersedia');

        $buku->where(function (Builder $query) use ($name , $buku_tersedia) {
          
     
            if($name) {
                $query->where('nama_buku' , 'like' ,'%'.$name.'%');
            }
        
             if($buku_tersedia !== null) {
                $query->where('buku_tersedia' , $buku_tersedia);
             }

        });

        $buku = $buku->paginate(perPage : $size , page: $pageBuku)->appends([
            'nama_buku'=>$name,
            'buku_tersedia'=>$buku_tersedia,
        ]);
        return  new BukuCollection($buku);
    }

   public function bukuHabis () : BukuCollection{
     $buku = buku::where('buku_tersedia' , 0)->get();
     return  new BukuCollection($buku);
   }
    public function detail_data($slugNamaBuku): BukuResource {
        $buku = buku::where('slug' , $slugNamaBuku)->first();

        if(!$buku){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "Buku Tidak Ditemukan"
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
 
        } else {
            $data['gambar_buku'] = $request->gambar_lama;
        }

        if($request->nama_buku && $request->nama_buku !== $buku->nama_buku){
            $data['slug'] = SlugService::createSlug(Buku::class , 'slug' , $data['nama_buku']);
            $qr = Qrcode::format('png')->generate('pinjam-buku/'.$data['slug']);
            $qrName = $data['slug'].'.png';
            $qrPath = 'barcode/'.$qrName;
            if($buku->gambar_qr && Storage::disk('public')->exists($buku->gambar_qr)){
                Storage::disk('public')->delete($buku->gambar_qr);
            }
            storage::disk('public')->put($qrPath,$qr);
            $data['gambar_qr'] = $qrPath;
        }

  
        $buku->fill($data);
        $buku->save();

        return (new BukuResource($buku));
    }
    
    

    public function deleteBuku($slugNamaBuku){
        $buku = buku::where('slug' , $slugNamaBuku )->first();
        if(!$buku){
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "Buku tidak ditemukan"
                    ]
                ]
            ])->setStatusCode(404));
        }

        $order = detail_order::where('id_buku' , $buku->id_buku)->count();
         if($order > 0) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "Buku tidak dapat dihapus karena masih terdapat dalam Riwayat Peminjaman"
                    ]
                ]
                   
            ])->setStatusCode(400));
         }
         
        if ($buku->gambar_buku && Storage::disk('public')->exists($buku->gambar_buku)) {
            Storage::disk('public')->delete($buku->gambar_buku);
        }

        if($buku->gambar_qr && Storage::disk('public')->exists($buku->gambar_qr)){
            Storage::disk('public')->delete($buku->gambar_qr);
        }   

        $buku->delete();
        return response()->json([
            'data' =>true,
        ])->setStatusCode(200);
       
    }

    public function pinjamBuku(OrderCreateRequest $request) : JsonResponse{

            $petugas = Auth::id();
            $dataOrder = $request->validated();
            $dataOrder['id_order'] = (String) Str::uuid();
            $dataOrder['id_petugas'] = $petugas;

            $anggota = anggota::where('id_anggota' , $dataOrder['id_anggota'])->first();

            $buku = buku::where('slug' , $dataOrder['slug'])->first();
            // $dataOrder['id_buku'] = $buku->id_buku;

            if($anggota->credit_anggota >50 && $buku->buku_tersedia !=0) {
                 $anggota->credit_anggota -= 5;
               $anggota->save();
                
               $buku->jumlah_buku -= 1;
               if($buku->jumlah_buku == 0){
              $buku->buku_tersedia = 0;
               }
               $buku->save();
               $order = new order($dataOrder);
               $order->save();

               $detailOrder = detail_order::create([
                'id_detail_order' => (String) Str::uuid(),
                'id_order' => $dataOrder['id_order'],
                'id_buku' => $buku->id_buku
               ]);

               $detailOrder->save();

                return (new OrderResource($order))->response()->setStatusCode(201);
            } else {

                return response()->json([
                    'message'=> "Gagal Memperoses Permintaan !"
                ] , 422);
            }
         
    }
    

}
