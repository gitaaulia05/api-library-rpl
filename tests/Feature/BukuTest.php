<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\buku;
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

     public function testPetugasLogin(){
        $this->seed([PetugasSeeder::class]);

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
            "Authorization" => "test"
            
        ])->assertStatus(200)->assertJson([
            "data"=> [
                "username" => "yayaa100"
            ]
        ]);
     }


     public function testLogout(){
        $this->seed([PetugasSeeder::class]);
        $this->delete(uri: '/api/petugas/logout', headers: [
            "Authorization" =>"test"
        ])->assertStatus(200)->assertJson([
            "data" => true
        ]);
     }

    public function testCreateSuccess()
    {
        //  $this->seed([PetugasSeeder::class]);
 
        $this->post('/api/buku' , [
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
        ], [
                'Authorization' =>"f9507097-24e1-46b0-933d-09498925a9f4"
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
            "nama" => "yayash g", 
            "email" => "yayash@gmail.com",
            "gambar_anggota" =>new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true)
        ] , [
            'Authorization' => 'f9507097-24e1-46b0-933d-09498925a9f4'
        ])->assertStatus(201)->assertJson([
            "nama" => "yayash g", 
            "email" => "yayash@gmail.com",
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
           
             "Authorization" => "test"
        ])->assertStatus(200)->json();
    }


    public function testPinjamBuku(){
      
        $response = $this->post("/api/pinjam-buku/", [
                'id_buku' => '81daa480-1da6-407e-a2fb-b401d3ae1816', 
                'id_anggota' => 'a0bd7116-a181-4fe3-b018-b426d53893b8'
        ] ,[
            'Authorization' => '4bb1ca0d-0060-4f22-b765-7cb1e9923132'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'id_buku' => '81daa480-1da6-407e-a2fb-b401d3ae1816', 
                'id_anggota' => 'a0bd7116-a181-4fe3-b018-b426d53893b8'
            ]
        ])->json();
    

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
    }
}
