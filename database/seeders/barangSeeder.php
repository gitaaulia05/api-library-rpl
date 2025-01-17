<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class barangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
        DB::table('barang')->insert([
                    'id_barang' =>(String) Str::uuid(),
                    'nama_barang' => $faker->word,
                    'stok_barang' => $faker->randomDigit(),
                    'harga_barang' => "10000",
                    'dibuat_pada'=> $faker->dateTime(),
                'diperbarui_pada'=>$faker->dateTime(),
            ]);
        }
    }
}
