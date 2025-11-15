<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        Prodi::insert([
            // Jurusan Teknik Informatika (ID: 1)
            ['nama_prodi' => 'D4 Teknik Informatika', 'jurusan_id' => 1],
            ['nama_prodi' => 'D3 Teknik Informatika', 'jurusan_id' => 1],
            // Jurusan Teknik Elektro (ID: 2)
            ['nama_prodi' => 'D3 Teknik Elektro', 'jurusan_id' => 2],
            ['nama_prodi' => 'D4 Teknik Listrik', 'jurusan_id' => 2],
            // Jurusan Teknik Mesin (ID: 3)
            ['nama_prodi' => 'D3 Teknik Mesin', 'jurusan_id' => 3],
            ['nama_prodi' => 'D4 Teknik Produksi', 'jurusan_id' => 3],
            // Jurusan Akuntansi (ID: 4)
            ['nama_prodi' => 'D3 Akuntansi', 'jurusan_id' => 4],
            ['nama_prodi' => 'D4 Akuntansi Manajerial', 'jurusan_id' => 4],
            // Jurusan Administrasi Niaga (ID: 5)
            ['nama_prodi' => 'D3 Administrasi Bisnis', 'jurusan_id' => 5],
            ['nama_prodi' => 'D4 Manajemen Bisnis', 'jurusan_id' => 5],
            // Jurusan Teknik Sipil (ID: 6)
            ['nama_prodi' => 'D3 Teknik Sipil', 'jurusan_id' => 6],
            ['nama_prodi' => 'D4 Perancangan Jalan & Jembatan', 'jurusan_id' => 6],
        ]);
    }
}
