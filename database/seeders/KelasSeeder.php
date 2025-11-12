<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        Kelas::insert([
            ['nama_kelas' => 'TI-1A', 'prodi_id' => 1],
            ['nama_kelas' => 'TI-1B', 'prodi_id' => 1],
            ['nama_kelas' => 'EL-1A', 'prodi_id' => 3],
        ]);
    }
}
