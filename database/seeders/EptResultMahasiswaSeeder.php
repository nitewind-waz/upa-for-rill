<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EptResultMahasiswa;

class EptResultMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        EptResultMahasiswa::insert([
            [
                'mahasiswa_id' => 1,
                'tahun' => 2023,
                'listening' => 480,
                'structure' => 450,
                'reading' => 470,
                'total_score' => 1400,
                'sertifikat_pdf' => 'sertifikat_gilang.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 2,
                'tahun' => 2023,
                'listening' => 400,
                'structure' => 420,
                'reading' => 410,
                'total_score' => 1230,
                'sertifikat_pdf' => 'sertifikat_siti.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 3,
                'tahun' => 2024,
                'listening' => 500,
                'structure' => 480,
                'reading' => 495,
                'total_score' => 1475,
                'sertifikat_pdf' => 'sertifikat_dimas.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
