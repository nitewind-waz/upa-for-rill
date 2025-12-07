<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EptResultPesertaMahasiswa;

class EptResultMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        EptResultPesertaMahasiswa::insert([
            // Student 1
            ['mahasiswa_id' => 1, 'tahun' => 2023, 'semester' => 1, 'listening' => 480, 'structure' => 450, 'reading' => 470, 'total_score' => 467, 'sertifikat_pdf' => 'sertifikat_1_2023_s1.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_id' => 1, 'tahun' => 2024, 'semester' => 3, 'listening' => 490, 'structure' => 460, 'reading' => 480, 'total_score' => 477, 'sertifikat_pdf' => 'sertifikat_1_2024_s3.pdf', 'created_at' => now(), 'updated_at' => now()],
            
            // Student 2
            ['mahasiswa_id' => 2, 'tahun' => 2023, 'semester' => 1, 'listening' => 400, 'structure' => 420, 'reading' => 410, 'total_score' => 410, 'sertifikat_pdf' => 'sertifikat_2_2023_s1.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_id' => 2, 'tahun' => 2025, 'semester' => 1, 'listening' => 410, 'structure' => 430, 'reading' => 420, 'total_score' => 420, 'sertifikat_pdf' => 'sertifikat_2_2025_s1.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 3
            ['mahasiswa_id' => 3, 'tahun' => 2024, 'semester' => 3, 'listening' => 500, 'structure' => 480, 'reading' => 495, 'total_score' => 492, 'sertifikat_pdf' => 'sertifikat_3_2024_s3.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 4
            ['mahasiswa_id' => 4, 'tahun' => 2023, 'semester' => 1, 'listening' => 420, 'structure' => 430, 'reading' => 440, 'total_score' => 430, 'sertifikat_pdf' => 'sertifikat_4_2023_s1.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_id' => 4, 'tahun' => 2024, 'semester' => 5, 'listening' => 430, 'structure' => 440, 'reading' => 450, 'total_score' => 440, 'sertifikat_pdf' => 'sertifikat_4_2024_s5.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 5
            ['mahasiswa_id' => 5, 'tahun' => 2023, 'semester' => 3, 'listening' => 510, 'structure' => 520, 'reading' => 500, 'total_score' => 510, 'sertifikat_pdf' => 'sertifikat_5_2023_s3.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_id' => 5, 'tahun' => 2025, 'semester' => 1, 'listening' => 520, 'structure' => 530, 'reading' => 510, 'total_score' => 520, 'sertifikat_pdf' => 'sertifikat_5_2025_s1.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 6
            ['mahasiswa_id' => 6, 'tahun' => 2024, 'semester' => 5, 'listening' => 450, 'structure' => 440, 'reading' => 460, 'total_score' => 450, 'sertifikat_pdf' => 'sertifikat_6_2024_s5.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 7
            ['mahasiswa_id' => 7, 'tahun' => 2024, 'semester' => 3, 'listening' => 380, 'structure' => 390, 'reading' => 400, 'total_score' => 390, 'sertifikat_pdf' => 'sertifikat_7_2024_s3.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_id' => 7, 'tahun' => 2025, 'semester' => 1, 'listening' => 390, 'structure' => 400, 'reading' => 410, 'total_score' => 400, 'sertifikat_pdf' => 'sertifikat_7_2025_s1.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 8
            ['mahasiswa_id' => 8, 'tahun' => 2023, 'semester' => 1, 'listening' => 480, 'structure' => 470, 'reading' => 490, 'total_score' => 480, 'sertifikat_pdf' => 'sertifikat_8_2023_s1.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 9
            ['mahasiswa_id' => 9, 'tahun' => 2023, 'semester' => 3, 'listening' => 460, 'structure' => 450, 'reading' => 470, 'total_score' => 460, 'sertifikat_pdf' => 'sertifikat_9_2023_s3.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_id' => 9, 'tahun' => 2024, 'semester' => 5, 'listening' => 470, 'structure' => 460, 'reading' => 480, 'total_score' => 470, 'sertifikat_pdf' => 'sertifikat_9_2024_s5.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_id' => 9, 'tahun' => 2025, 'semester' => 1, 'listening' => 480, 'structure' => 470, 'reading' => 490, 'total_score' => 480, 'sertifikat_pdf' => 'sertifikat_9_2025_s1.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 10
            ['mahasiswa_id' => 10, 'tahun' => 2024, 'semester' => 3, 'listening' => 410, 'structure' => 400, 'reading' => 420, 'total_score' => 410, 'sertifikat_pdf' => 'sertifikat_10_2024_s3.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 11
            ['mahasiswa_id' => 11, 'tahun' => 2024, 'semester' => 5, 'listening' => 520, 'structure' => 510, 'reading' => 530, 'total_score' => 520, 'sertifikat_pdf' => 'sertifikat_11_2024_s5.pdf', 'created_at' => now(), 'updated_at' => now()],

            // Student 12
            ['mahasiswa_id' => 12, 'tahun' => 2023, 'semester' => 1, 'listening' => 430, 'structure' => 440, 'reading' => 420, 'total_score' => 430, 'sertifikat_pdf' => 'sertifikat_12_2023_s1.pdf', 'created_at' => now(), 'updated_at' => now()],
            ['mahasiswa_id' => 12, 'tahun' => 2024, 'semester' => 3, 'listening' => 440, 'structure' => 450, 'reading' => 430, 'total_score' => 440, 'sertifikat_pdf' => 'sertifikat_12_2024_s3.pdf', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
