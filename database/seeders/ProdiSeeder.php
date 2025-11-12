<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        Prodi::insert([
            ['nama_prodi' => 'D4 Teknik Informatika', 'jurusan_id' => 1],
            ['nama_prodi' => 'D3 Teknik Informatika', 'jurusan_id' => 1],
            ['nama_prodi' => 'D3 Teknik Elektro', 'jurusan_id' => 2],
        ]);
    }
}
