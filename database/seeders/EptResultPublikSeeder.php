<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EptResultPublikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ept_results_publik')->insert([
            [
                'publik_id' => 1,
                'tahun' => 2023,
                'listening' => 45,
                'structure' => 40,
                'reading' => 50,
                'total_score' => 135,
                'sertifikat_pdf' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'publik_id' => 1,
                'tahun' => 2024,
                'listening' => 60,
                'structure' => 55,
                'reading' => 65,
                'total_score' => 180,
                'sertifikat_pdf' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'publik_id' => 2,
                'tahun' => 2024,
                'listening' => 70,
                'structure' => 65,
                'reading' => 72,
                'total_score' => 207,
                'sertifikat_pdf' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
