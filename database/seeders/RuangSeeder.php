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
        Ruang::insert([
            ['nama' => 'BA-101'],
            ['nama' => 'BA-102'],
            ['nama' => 'BA-103'],
            ['nama' => 'BA-201'],
            ['nama' => 'BA-202'],
            ['nama' => 'BA-203'],
            ['nama' => 'BA-301'],
            ['nama' => 'BA-302'],
            ['nama' => 'BA-303'],
        ]);
    }
}
