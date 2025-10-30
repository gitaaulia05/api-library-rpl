<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\buku;
use App\Models\order;
use App\Models\anggota;


use App\Models\Petugas;
use Illuminate\Support\Str;
use App\Models\detail_order;
use Illuminate\Http\UploadedFile;
use Database\Seeders\PetugasSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BukuTest extends TestCase
{
    /**
     * A basic feature test example.
     */


     public function testFaker (){
        $this->get('api/faker')->assertStatus(200)->json();
     }
     public function testPetugasLogin(){
        // $this->seed([PetugasSeeder::class]);

        $this->post('api/petugas/login' , [
            'username' => 'yayaa100',
            'password' => 'test',

            
        ])->assertStatus(200)->assertJson([
            'data' => [
                'username' => 'yayaa100',
                'password' => 'test',
            ]
        ]);

        $petugas = Petugas::where('username' , 'yayaa100')->first();
        self::assertNotNull($petugas->token);
     }


     public function testGetPetugas(){
        $this->seed([PetugasSeeder::class]);
        $this->get('/api/petugas/saatIni' , [
            "Authorization" => "ec755c15-0027-4b3e-b266-84d82fd1b1e4"
            
        ])->assertStatus(200)->assertJson([
            "data"=> [
                "username" => "yayaa100"
            ]
        ]);
     }


     public function testLogout(){
        // $this->seed([PetugasSeeder::class]);
        $this->delete(uri: '/api/petugas/logout', headers: [
            "Authorization" =>"54e3d8ef-33ba-4097-b5d0-b20bbd8a5da"
        ])->assertStatus(200)->assertJson([
            "data" => true
        ]);
     }

    public function testCreateSuccess()
    {
        //  $this->seed([PetugasSeeder::class]);
 
        $this->post('/api/buku' , [
      
            // "nama_buku_slug" => "perempuan-yang-menangis-kepada-bulan-hitam",
            "nama_buku" => "Perempuan yang Menangis Kepada Bulan Hitam",
            "gambar_buku" => new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true),
            "nama_penulis" => "Dian Purnomo",
            "nama_penerbit" => "Gramedia",
            "jumlah_buku" => "88",
            "buku_tersedia" => "1",
            "tahun_terbit" => "2024",
            "created_at" => "2024-12-04",
            // "updated_at" => "2024-12-04",
        ], [
                 "Authorization" =>"6ccf9ab7-0fa2-46e0-abd9-5c79b431170d"
        ])
        ->assertStatus(201)
        ->assertJson([
            'data' => [
              
            //     "nama_buku_slug" => "30-Cerita-Teladan-Islami",
            "nama_buku" => "30 Cerita Teladan Islami",
                "gambar_buku" => new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true),
            "nama_penulis" => "Mahmudah Mastur",
            "nama_penerbit" => "Noktah",
            "jumlah_buku" => "88",
            "buku_tersedia" => "1",
            "tahun_terbit" => "2021",
            "created_at" => "2024-12-04",
            "updated_at" => "2024-12-04",
            ]
        ]);
    }


    public function testAnggotaCreate(){
        $this->post('/api/anggota' , [
            // "nama" => "", 
            "email" => "gitaauliahafid@gmail.com",
            "gambar_anggota" =>new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true)
        ] , [
            'Authorization' => 'c110705a-4519-4d69-8215-13c88d0b4f07'
        ])->assertStatus(201)->assertJson([
            "nama" => "becky", 
            "email" => "gitaauliahafid@gmail.com",
            "gambar_anggota" =>new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true)
        ]);
    }

    public function testGetSlugBuku(){
        $buku  = buku::query()->limit(1)->first();
        $this->get('/api/buku/'.$buku->nama_buku_slug, [
         
        ])->assertStatus(200)->assertJson([
            'data' => [
                "id_buku" => "2355201100",
                "nama_buku_slug" => "30-Cerita-Teladan-Islami",
            "nama_buku" => "30 Cerita Teladan Islami",
            "nama_penulis" => "Mahmudah Mastur",
            "nama_penerbit" => "Noktah",
            "jumlah_buku" => "88",
            "buku_tersedia" => "1",
            "created_at" => "2024-12-04",
            "updated_at" => "2024-12-04",
            ]
            ]);
    }

    public function testSearchData(){
        // $response = $this->get('/api/buku' , [
        $response = $this->get('/api/buku?nama_buku=30 Cerita Teladan Islami' , [
            'Authorization' =>"73dae7ab-0ea1-48a3-97e2-9aff04a2f85e"
        // ]);
        ])->assertStatus(200)->json();
        // $response->dump();
        // self::assertEquals(1, count($response['data']));
    }

public function testSearchAnggota(){
        $response = $this->get('/api/anggota?nama=becky' , [
            'Authorization' => 'b5e03c75-f03c-469d-bd5f-4a401204d910',
            'If-None-Match' => '1739884493',
        ]);
        $response->assertStatus(200);
        // ->assertStatus(200)->json();
    }

    public function testBukuHabis(){
        $response = $this->get('api/buku?buku_tersedia=0' , [
                'Authorization' =>"ef5d6181-4c1d-4fc6-8235-bf111633ffc7"

        ])->assertStatus(200)->json();
        Log::info(json_encode($response , JSON_PRETTY_PRINT));
    }

    public function testDeleteSuccess(){
        $buku  = Buku::query()->limit(1)->first();
        $order = detail_order::where('id_buku' , $buku->id_buku)->count();
        dd($order);
        $this->delete('/api/buku/'.$buku->slug, [
                'Authorization' =>'6ccf9ab7-0fa2-46e0-abd9-5c79b431170d'
        ])->assertStatus(200)->assertJson([
            'data' => true
            ]); 
    }

    public function testUpdateSuccess(){
        // $this->seed([PetugasSeeder::class]);
 
        $buku  = buku::query()->limit(1)->first();

        $this->post('/api/buku/'.$buku->slug, [
            // 'id_buku' => '752aca66-bd35-43ab-9772-152dff274913',
        "slug" => "30-Cerita-Teladan-Islami",
        "nama_buku" => "30 Cerita Teladan Islami",
        "gambar_buku" => new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true),
        "nama_penulis" => "Mahmudah Mastur",
        "nama_penerbit" => "Noktah",
        "jumlah_buku" => "88",
        "buku_tersedia" => "1",
        "created_at" => "2024-12-04",
        // "updated_at" => "2024-12-04",
        ], [
            "Authorization" => "c05fa1dd-fde2-4f2e-bf77-47e84a2672dc"
        ])->assertStatus(200)->assertJson([
            'data' => [
                // "id_buku" => "0298891b-637b-46d2-8198-6bf79badf03a",
                "slug" => "30-Cerita-Teladan-Islami",
                "nama_buku" => "30 Cerita Teladan Islami",
            //    "gambar_buku" => new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true),
                "nama_penulis" => "Mahmudah Mastur",
                "nama_penerbit" => "Noktah",
                "jumlah_buku" => "88",
                "buku_tersedia" => "1",
                "created_at" => "2024-12-04",
                "updated_at" => "2024-12-04",
            ]
        ]);
    }



    public function testCreateAnggota(){
        $response = $this->get('/api/anggota' , [
             "Authorization" => "a8262976-735a-462c-9034-d6a72b2d80ae"
        ])->assertStatus(200)->json();
    }


    public function testPinjamBuku(){
      
        $anggota = anggota::query()->limit(1)->first();
        // $buku = buku::query()->limit(1)->first();
        $response = $this->post("/api/pinjam-buku/", [
                 'slug' => "gadis-minimarket", 
                'id_anggota' => $anggota->id_anggota
        ] ,[
            'Authorization' => '7d05016f-a9a0-49a0-b93e-91b8c635ccd1'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'slug' => "gadis-minimarket", 
                'id_anggota' => $anggota->id_anggota
            ]
            ]);
    

       // Log::info(json_encode($response, JSON_PRETTY_PRINT));
    }

    public function testDetailAnggota() {
        $anggota = anggota::query()->limit(1)->first();
        $response = $this->get("/api/detail-anggota/".$anggota->slug , [
            'Authorization' => "c05fa1dd-fde2-4f2e-bf77-47e84a2672dc"
        ])->assertStatus(200)->json();
    }

    public function testSearchPeminjamanAnggota() {
        $anggota = anggota::query()->limit(1)->first();
        $response = $this->get('/api/peminjaman-anggota/'.$anggota->slug.'?nama_buku=gadis-minimarket' , [
            'Authorization' => "7d05016f-a9a0-49a0-b93e-91b8c635ccd1"
        ])->assertStatus(200)->json();
    }


    public function testSearchPeminjamanAnggotaBuku() {
        $anggota = anggota::query()->limit(1)->first();
        $response = $this->get('/api/peminjaman-anggota/'.$anggota->slug.'?buku_dikembalikan=0' , [
            'Authorization' => "7d05016f-a9a0-49a0-b93e-91b8c635ccd1"
        ])->assertStatus(200)->json();
    }

    public function testDetailOrder() {
        $order = order::query()->limit(1)->first();
        $response = $this->get("/api/pengembalian-buku/".$order->id_order  , [
               'Authorization' => "0977bf3a-9799-4941-8402-40cca2e8cded"
        ])->assertStatus(200)->json();
    }

    public function testPengembalian() {
        $order = detail_order::query()->limit(1)->first();
        $response = $this->withSession(['telat' => true])->post("/api/pengembalian-simpan/0c19a71c-8627-4adb-9a16-8043dd34e5e0" , [
            'id_order' => "0c19a71c-8627-4adb-9a16-8043dd34e5e0"
        ], [
            "Authorization" => "35946bed-65ad-422c-b584-531da60a9519"
        ])->assertStatus(200)->json();
    }


    public function testPengembalian1() {
        $order = detail_order::query()->limit(1)->first();
        $response = $this->withSession(['telat' => true])->post("/api/pengembalian-simpan/4d142061-2126-4036-b822-dd50d35469e4" , [
            'id_order' => "4d142061-2126-4036-b822-dd50d35469e4"
        ], [
            "Authorization" => "994bd39e-9760-415a-a090-b6076676e29f"
        ])->assertStatus(200)->json();
    }
}