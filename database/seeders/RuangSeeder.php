<?php

namespace Database\Seeders;

use App\Models\Ruang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangs = [
            ['nama' => 'Lab Komputer 1'],
            ['nama' => 'Lab Komputer 2'],
            ['nama' => 'Ruang Kuliah 101'],
            ['nama' => 'Ruang Kuliah 102'],
            ['nama' => 'Aula Serbaguna'],
            ['nama' => 'Ruang Rapat'],
        ];

        foreach ($ruangs as $ruang) {
            Ruang::create($ruang);
        }
    }
}
