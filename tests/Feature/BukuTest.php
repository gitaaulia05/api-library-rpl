<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\buku;
use App\Models\Petugas;
use Illuminate\Support\Str;


use Illuminate\Http\UploadedFile;
use Database\Seeders\PetugasSeeder;
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
    
    public function testCreateSuccess()
    {
        //  $this->seed([UserSeeder::class]);
        $file = UploadedFile::fake()->image('buku.jpg');
        $this->post('/api/buku' , [
          "id_buku" => "235520110700",
            "nama_buku_slug" => "30-Cerita-Teladan-Islami",
            "nama_buku" => "30 Cerita Teladan Islami",
            "gambar_buku" => $file,
            "nama_penulis" => "Mahmudah Mastur",
            "nama_penerbit" => "Noktah",
            "jumlah_buku" => "88",
            "buku_tersedia" => "1",
            "tanggal_masuk_buku" => "2024-12-04",
            "update_terakhir" => "2024-12-04",
        ] )
        ->assertStatus(201)
        ->assertJson([
            'data' => [
                "id_buku" => "235520110700",
                "nama_buku_slug" => "30-Cerita-Teladan-Islami",
            "nama_buku" => "30 Cerita Teladan Islami",
                "gambar_buku" => $file,
            "nama_penulis" => "Mahmudah Mastur",
            "nama_penerbit" => "Noktah",
            "jumlah_buku" => "88",
            "buku_tersedia" => "1",
            "tanggal_masuk_buku" => "2024-12-04",
            "update_terakhir" => "2024-12-04",
            ]
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
            "tanggal_masuk_buku" => "2024-12-04",
            "update_terakhir" => "2024-12-04",
            ]
            ]);
    }

    public function testSearchData(){
        $response = $this->get('/api/buku?nama_buku=30 Cerita Teladan Islami')->assertStatus(200)->json();

        self::assertEquals(1, count($response['data']));
    }

    public function testDeleteSuccess(){
        $buku  = Buku::query()->limit(1)->first();
        $this->delete('/api/buku/'.$buku->slug, [
                // 'Authorization' =>"test";
        ])->assertStatus(200)->assertJson([
            'data' => true
            ]); 
    }

    public function testUpdateSuccess(){

        $buku  = buku::query()->limit(1)->first();

        $this->post('/api/buku/'.$buku->slug, [
            'id_buku' => '30298891b-637b-46d2-8198-6bf79badf03a',
        "slug" => "30-Cerita-Teladan-Islami",
        "nama_buku" => "30 Cerita Teladan Islami",
        "gambar_buku" => new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true),
        "nama_penulis" => "Mahmudah Mastur",
        "nama_penerbit" => "Noktah",
        "jumlah_buku" => "88",
        "buku_tersedia" => "1",
        "tanggal_masuk_buku" => "2024-12-04",
        "update_terakhir" => "2024-12-04",
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
                "tanggal_masuk_buku" => "2024-12-04",
                "update_terakhir" => "2024-12-04",
            ]
        ]);
    }

}
