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
        Gedung::insert([
            ['nama' => 'A'],
            ['nama' => 'B'],
            ['nama' => 'C'],
            ['nama' => 'D'],
            ['nama' => 'E'],
            ['nama' => 'F'],
            ['nama' => 'G'],
        ]);
    }
}
