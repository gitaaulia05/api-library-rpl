<?php

namespace Database\Seeders;

use App\Models\Buku;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // $qr = QrCode::format('png')->generate('pinjam-buku/'.$data['slug']);
        // $qrName = $data['slug'].'.png';
        // $qrPath = 'barcode/' . $qrName;
        // Storage::disk('public')->put($qrPath, $qr);

        // $data['gambar_qr'] =$qrPath;
        // if($request->hasFile('gambar_buku')){
        //     $data['gambar_buku'] = $request->file('gambar_buku')->store('gambarBuku' , 'public');
        // }

        foreach (range(1,5) as $index) {
            $namaBuku = $faker->name();
            $slugs = SlugService::createSlug(Buku::class , 'slug' , $namaBuku);

            $qr = QrCode::format('png')->generate('pinjam-buku/'.$slugs);
            $qrName = $slugs.'.png';
            $qrPath = 'barcode/'.$qrName;
            Storage::disk('public')->put($qrPath, $qr);
                $gambar = new \Illuminate\Http\UploadedFile(resource_path('testImg/indomie.jpg'), 'indomie.jpg', null, null, true);
            $gambar_buku = $gambar->store('gambarBuku' , 'public');
            DB::table('bukus')->insert([
                "id_buku" => (String) Str::uuid(),
                "nama_buku" => $faker->word(),
                "slug" => $slugs,
                "gambar_buku" => $gambar_buku,
                "nama_penulis"=> $namaBuku,
                "gambar_qr" => $qrPath,
                "nama_penerbit" => $faker->name(),
                "jumlah_buku" => $faker->randomDigit(),
                "buku_tersedia" => $faker->randomDigitNot(2),
                "tahun_terbit" => $faker->year(),
                "created_at" =>$faker->dateTimeThisYear(),
                "updated_at" => $faker->dateTimeThisYear(),
            ]);
        }
    }
}
