<?php

namespace Database\Seeders;

use App\Models\Gedung;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gedungs = [
            ['nama' => 'Gedung A'],
            ['nama' => 'Gedung B'],
            ['nama' => 'Gedung C'],
            ['nama' => 'Gedung D'],
            ['nama' => 'Gedung E'],
            ['nama' => 'Gedung F'],
        ];

        foreach ($gedungs as $gedung) {
            Gedung::create($gedung);
        }
    }
}
