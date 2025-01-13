<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\anggota;
use Illuminate\Support\Str;
use App\Models\detail_order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;
use App\Http\Resources\AnggotaResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\AnggotaCollection;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\AnggotaCreateRequest;
use App\Http\Requests\DetailOrderUpdateRequest;



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

        public function detail_anggota($slugAnggota) : AnggotaResource {
                $anggota = anggota::where('slug' , $slugAnggota)->first();

            if(!$anggota) {
                throw new HttpResponseException(response()->json([
                    'errors' => [
                        "message" => [
                            "Anggota Tidak Ditemukan"
                        ]
                    ]
                ])->setStatusCode(404));
            }
                return new AnggotaResource($anggota);
        }

        public function peminjamanAnggota(Request $request , $slugAnggota) : OrderCollection {
            $pageBuku = $request->input('page' , 1);
            $size = $request->input('size' , 10);
            $nama_buku = $request->input('nama_sebuku');
             
                $order = order::with( [ 'anggota' , 'detail_order.buku'])->whereHas('anggota' , function($data) use($slugAnggota) {
                    $data->where('slug' , $slugAnggota);
                })->whereHas('detail_order.buku' , function ($query) use ($nama_buku) {
                    if($nama_buku) {    
                                $query->where('nama_buku' , 'like' , '%'. $nama_buku .'%');
                            }
                });
            
            $order = $order->paginate(perPage : $size , page: $pageBuku);

            return new OrderCollection($order);
        }


        public function detailOrder($idOrder) : OrderResource {

            $order = order::with(['anggota' , 'detail_order.buku'])->where('id_order' , $idOrder)->firstOrFail();;

            return new OrderResource($order);
        }

        public function simpanPengembalian(DetailOrderUpdateRequest $request , $idOrder)  {
            Log::info("Request masuk dengan id_order: $idOrder");

            $detailOrder = detail_order::with(['buku'] , ['order.anggota'])->where('id_order' , $idOrder)->first();
            
            if($detailOrder->buku_dikembalikan == 1) {
                return response()->json(['error' => "Buku Sudah Dikembalikan"]);
            } elseif($detailOrder->buku_dikembalikan != 1) {

                $detailOrder->buku_dikembalikan += 1;
                $detailOrder->save();

                if($detailOrder->buku){
                    $detailOrder->buku->buku_tersedia += 1;
                    $detailOrder->buku->save();
                }


                if(session()->get('telat') == true){
                    Log::info('Session Telat:', ['telat' => session()->get('telat')]);

                    // jalankan cronjob 

                    $executeAt = now()->addMinutes(1);

                    DB::table('cron_jobs')->insert([
                        'id_order' => $idOrder,
                        'execute_at' => $executeAt,
                        'created_at'=>now(),
                        'updated_at'=>now(),
                        
                    ]);
                } else {
                  
                        $detailOrder->order->anggota->credit_anggota += 5;
                        $detailOrder->order->anggota->save();
         
                } 
            } 
            else {
                return response()->json(['error' => "Id Order Tidak Ditemukan"], 404);
            }

            return response()->json(['data' => "Pengembalian Sukses"], 200);
        }


}
