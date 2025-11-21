<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PublikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('publiks')->insert([
            [
                'nik' => '3210010101010001',
                'nama_lengkap' => 'Budi Setiawan',
                'password' => Hash::make('password123'),
            ],
            [
                'nik' => '3210010101010002',
                'nama_lengkap' => 'Siti Aminah',
                'password' => Hash::make('password123'),
            ],
            [
                'nik' => '3210010101010003',
                'nama_lengkap' => 'Gilang Balatro',
                'password' => Hash::make('gantengbanget'),
            ],
        ]);
    }
}
