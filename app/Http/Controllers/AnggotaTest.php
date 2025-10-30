<?php

namespace App\Http\Controllers;

use session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Benchmark;

class AnggotaTest extends Controller
{
    public function index(){
        $data = [];
        $eTag = session('EtagSearchAnggota');
        // dd($eTag);
       
        //    dd($eTag);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.'b5e03c75-f03c-469d-bd5f-4a401204d910',
                'If-None-Match' => $eTag,
            ])->get('http://api-library.test/api/anggota');
        
            
            $newETag = (string) ($response->json('headers.ETag') ?? '');
            
        
            // dd($response->status());
            if($response->status() == 304){
            //    dd('Masuk ke blok 304');
               
               $data = Cache::get('AnggotaCache');
            }
        
            if($response->json('headers.ETag') !== $eTag){
                dd('hm');
               $data =  Cache::put('AnggotaCache' , $response->json() , now()->addMinutes(10));
                $eTag = $newETag;
                session(['EtagSearchAnggota' => $newETag]);   
            }


            // $response = Http::withHeaders([
            //     'Authorization' => 'Bearer '.'b5e03c75-f03c-469d-bd5f-4a401204d910',
            // ])->get('http://api-library.test/api/anggota');

            // $data = $response->json();
        
            return view('testAnggota' , [
                'datas' => $data
            ]);
    }
}
