<?php

namespace Database\Seeders;

use App\Models\Petugas;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Petugas::create([
                'id_petugas' => (String) Str::uuid(),
                'username' => 'yayaa100',
                'password' => Hash::make('test'),
                'token' => 'test',
        ]);
    }
}
