<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\buku;
use App\Models\order;
use App\Models\detail_order;
use App\Models\anggota;


use App\Models\Petugas;
use Illuminate\Support\Str;
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
        // $this->seed([PetugasSeeder::class]);
        $this->get('/api/petugas/saatIni' , [
            "Authorization" => "5726a87c-2dca-4ff0-b188-ddac333bde6c"
            
        ])->assertStatus(200)->assertJson([
            "data"=> [
                "username" => "yayaa100"
            ]
        ]);
     }


     public function testLogout(){
        // $this->seed([PetugasSeeder::class]);
        $this->delete(uri: '/api/petugas/logout', headers: [
            "Authorization" =>"8b1c7a53-bae9-470a-a8c1-f6c279c271a"
        ])->assertStatus(200)->assertJson([
            "data" => true
        ]);
     }

    public function testCreateSuccess()
    {
        //  $this->seed([PetugasSeeder::class]);
 
        $this->post('/api/buku' , [
      
            "nama_buku_slug" => "30-Cerita-Teladan-Islami",
            "nama_buku" => "30 Cerita Teladan Islami",
            "gambar_buku" => new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true),
            "nama_penulis" => "Mahmudah Mastur",
            "nama_penerbit" => "Noktah",
            "jumlah_buku" => "88",
            "buku_tersedia" => "1",
            "tahun_terbit" => "2021",
            "created_at" => "2024-12-04",
            "updated_at" => "2024-12-04",
        ], [
                'Authorization' =>"b8280b2a-b65d-4fd8-99a4-51fca575f3c"
        ])
        ->assertStatus(201)
        ->assertJson([
            'data' => [
                "id_buku" => "235520110700",
                "nama_buku_slug" => "30-Cerita-Teladan-Islami",
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
            "nama" => "hyuna", 
            "email" => "hyuna@gmail.com",
            "gambar_anggota" =>new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true)
        ] , [
            'Authorization' => 'a759dc03-b052-4e2f-b07a-5da630147d33'
        ])->assertStatus(201)->assertJson([
            "nama" => "hyuna", 
            "email" => "hyuna@gmail.com",
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
        $response = $this->get('/api/buku?nama_buku=30 Cerita Teladan Islami' , [
            'Authorization' =>"ef5d6181-4c1d-4fc6-8235-bf111633ffc7"
        ])->assertStatus(200)->json();
        self::assertEquals(1, count($response['data']));
    }

    public function testSearchAnggota(){
        $response = $this->get('/api/anggota?nama=becky' , [
            'Authorization' => '06dd9326-7946-4ba2-96f0-a55321f15eee'
        ])->assertStatus(200)->json();
    }

    public function testBukuHabis(){
        $response = $this->get('api/buku?buku_tersedia=0' , [
                'Authorization' =>"ef5d6181-4c1d-4fc6-8235-bf111633ffc7"

        ])->assertStatus(200)->json();
        Log::info(json_encode($response , JSON_PRETTY_PRINT));
    }

    public function testDeleteSuccess(){
        $buku  = Buku::query()->limit(1)->first();
        $this->delete('/api/buku/'.$buku->slug, [
                'Authorization' =>'test'
        ])->assertStatus(200)->assertJson([
            'data' => true
            ]); 
    }

    public function testUpdateSuccess(){
        $this->seed([PetugasSeeder::class]);
 
        $buku  = buku::query()->limit(1)->first();

        $this->put('/api/buku/'.$buku->slug, [
            'id_buku' => '30298891b-637b-46d2-8198-6bf79badf03a',
        "slug" => "30-Cerita-Teladan-Islami",
        "nama_buku" => "30 Cerita Teladan Islami",
        "gambar_buku" => new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true),
        "nama_penulis" => "Mahmudah Mastur",
        "nama_penerbit" => "Noktah",
        "jumlah_buku" => "88",
        "buku_tersedia" => "1",
        "created_at" => "2024-12-04",
        "updated_at" => "2024-12-04",
        ], [
            "Authorization" => "test"
        ])->assertStatus(200)->assertJson([
            'data' => [
                // "id_buku" => "0298891b-637b-46d2-8198-6bf79badf03a",
                "slug" => "30-Cerita-Teladan-Islami",
                "nama_buku" => "30 Cerita Teladan Islami",
               "gambar_buku" => new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true),
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
      
        $response = $this->post("/api/pinjam-buku/", [
                 'slug' => '30-cerita-teladan-islami', 
                'id_anggota' => '30892be2-f96c-4910-b933-264835ecfaf4'
        ] ,[
            'Authorization' => 'fe1ddb81-3a0a-4e73-a009-cb384cc5479d'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'slug' => '30-cerita-teladan-islami', 
                'id_anggota' => '30892be2-f96c-4910-b933-264835ecfaf4'
            ]
        ])->json();
    

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
    }

    public function testDetailAnggota() {
        $anggota = anggota::query()->limit(1)->first();
        $response = $this->get("/api/detail-anggota/".$anggota->slug , [
            'Authorization' => "a759dc03-b052-4e2f-b07a-5da630147d33"
        ])->assertStatus(200)->json();
    }

    public function testSearchPeminjamanAnggota() {
        $anggota = anggota::query()->limit(1)->first();
        $response = $this->get('/api/peminjaman-anggota/'.$anggota->slug.'?nama_buku=30 Cerita Teladan Islami' , [
            'Authorization' => "a759dc03-b052-4e2f-b07a-5da630147d33"
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
        $response = $this->withSession(['telat' => true])->post("/api/pengembalian-simpan/b0df2755-3eab-444c-999d-d14a5da216b0" , [
            'id_order' => "b0df2755-3eab-444c-999d-d14a5da216b0"
        ], [
            "Authorization" => "994bd39e-9760-415a-a090-b6076676e29f"
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
