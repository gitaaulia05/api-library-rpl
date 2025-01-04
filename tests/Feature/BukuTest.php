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
                'Authorization' =>"a8262976-735a-462c-9034-d6a72b2d80ae"
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
            "nama" => "natti", 
            "email" => "natti@gmail.com",
            "gambar_anggota" =>new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true)
        ] , [
            'Authorization' => 'a8262976-735a-462c-9034-d6a72b2d80ae'
        ])->assertStatus(201)->assertJson([
            "nama" => "natti", 
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
             "Authorization" => "a8262976-735a-462c-9034-d6a72b2d80ae"
        ])->assertStatus(200)->json();
    }


    public function testPinjamBuku(){
      
        $response = $this->post("/api/pinjam-buku/", [
                 'slug' => '30-cerita-teladan-islami', 
                'id_anggota' => '02623d52-6a46-40e1-a47c-99659c10e4b4'
        ] ,[
            'Authorization' => 'a8262976-735a-462c-9034-d6a72b2d80ae'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'slug' => '30-cerita-teladan-islami', 
                'id_anggota' => '02623d52-6a46-40e1-a47c-99659c10e4b4'
            ]
        ])->json();
    

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
    }


    public function testSearchPeminjamanAnggota() {
        $response = $this->get('/api/peminjaman-anggota?nama_buku=30 Cerita Teladan Islami' , [
            'Authorization' => "9796ef70-dee7-4a06-9805-0b57c4e2ae43"
        ])->assertStatus(200)->json();
    }
}
