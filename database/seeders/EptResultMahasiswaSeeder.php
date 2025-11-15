<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EptResultMahasiswa;

class EptResultMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        EptResultMahasiswa::insert([
            // Results for existing students
            [
                'mahasiswa_id' => 1, 'tahun' => 2023, 'listening' => 480, 'structure' => 450, 'reading' => 470, 'total_score' => 467, 'sertifikat_pdf' => 'sertifikat_gilang_2023.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 1, 'tahun' => 2024, 'listening' => 490, 'structure' => 460, 'reading' => 480, 'total_score' => 477, 'sertifikat_pdf' => 'sertifikat_gilang_2024.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 2, 'tahun' => 2023, 'listening' => 400, 'structure' => 420, 'reading' => 410, 'total_score' => 410, 'sertifikat_pdf' => 'sertifikat_siti.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 3, 'tahun' => 2024, 'listening' => 500, 'structure' => 480, 'reading' => 495, 'total_score' => 492, 'sertifikat_pdf' => 'sertifikat_dimas.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            // Results for new students
            [
                'mahasiswa_id' => 4, 'tahun' => 2023, 'listening' => 420, 'structure' => 430, 'reading' => 440, 'total_score' => 430, 'sertifikat_pdf' => 'sertifikat_budi.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 5, 'tahun' => 2023, 'listening' => 510, 'structure' => 520, 'reading' => 500, 'total_score' => 510, 'sertifikat_pdf' => 'sertifikat_citra.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 6, 'tahun' => 2024, 'listening' => 450, 'structure' => 440, 'reading' => 460, 'total_score' => 450, 'sertifikat_pdf' => 'sertifikat_dewi.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 7, 'tahun' => 2024, 'listening' => 380, 'structure' => 390, 'reading' => 400, 'total_score' => 390, 'sertifikat_pdf' => 'sertifikat_eko.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 8, 'tahun' => 2023, 'listening' => 480, 'structure' => 470, 'reading' => 490, 'total_score' => 480, 'sertifikat_pdf' => 'sertifikat_fitriani.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 9, 'tahun' => 2023, 'listening' => 460, 'structure' => 450, 'reading' => 470, 'total_score' => 460, 'sertifikat_pdf' => 'sertifikat_gunawan.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 10, 'tahun' => 2024, 'listening' => 410, 'structure' => 400, 'reading' => 420, 'total_score' => 410, 'sertifikat_pdf' => 'sertifikat_heru.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 11, 'tahun' => 2024, 'listening' => 520, 'structure' => 510, 'reading' => 530, 'total_score' => 520, 'sertifikat_pdf' => 'sertifikat_indah.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'mahasiswa_id' => 12, 'tahun' => 2023, 'listening' => 430, 'structure' => 440, 'reading' => 420, 'total_score' => 430, 'sertifikat_pdf' => 'sertifikat_joko.pdf', 'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
