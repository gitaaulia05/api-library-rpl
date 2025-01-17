<?php

namespace Database\Seeders;

use App\Models\pelanggan;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class pelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            pelanggan::create([
                'id_pelanggan' => (String) Str::uuid(),
                'nama' =>  $faker->name,
                'no_hp' => $faker->randomDigit(),
                'alamat' => $faker->word,
                'limit' => "500000",
                'dibuat_pada'=> $faker->dateTime(),
                'diperbarui_pada'=>$faker->dateTime(),
        ]);
        
    }

    }
}
