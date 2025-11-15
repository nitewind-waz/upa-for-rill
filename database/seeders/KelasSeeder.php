<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        Kelas::insert([
            // Prodi D4 TI (ID: 1)
            ['nama_kelas' => 'TI-1A', 'prodi_id' => 1],
            ['nama_kelas' => 'TI-1B', 'prodi_id' => 1],
            // Prodi D3 TI (ID: 2)
            ['nama_kelas' => 'TI-D3-1A', 'prodi_id' => 2],
            // Prodi D3 TE (ID: 3)
            ['nama_kelas' => 'EL-1A', 'prodi_id' => 3],
            // Prodi D4 TL (ID: 4)
            ['nama_kelas' => 'TL-1A', 'prodi_id' => 4],
            // Prodi D3 TM (ID: 5)
            ['nama_kelas' => 'ME-1A', 'prodi_id' => 5],
            // Prodi D4 TP (ID: 6)
            ['nama_kelas' => 'TP-1A', 'prodi_id' => 6],
            // Prodi D3 AK (ID: 7)
            ['nama_kelas' => 'AK-1A', 'prodi_id' => 7],
            // Prodi D4 AM (ID: 8)
            ['nama_kelas' => 'AM-1A', 'prodi_id' => 8],
            // Prodi D3 AB (ID: 9)
            ['nama_kelas' => 'AB-1A', 'prodi_id' => 9],
            // Prodi D4 MB (ID: 10)
            ['nama_kelas' => 'MB-1A', 'prodi_id' => 10],
            // Prodi D3 TS (ID: 11)
            ['nama_kelas' => 'CE-1A', 'prodi_id' => 11],
            // Prodi D4 PJJ (ID: 12)
            ['nama_kelas' => 'PJJ-1A', 'prodi_id' => 12],
        ]);
    }
}
