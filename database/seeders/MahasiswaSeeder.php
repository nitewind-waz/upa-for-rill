<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        Mahasiswa::insert([
            [
                'nim' => '220001',
                'nama' => 'Gilang Balatro',
                'tanggal_lahir' => '2003-06-15',
                'email' => 'gilang@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 1,
                'prodi_id' => 1,
                'kelas_id' => 1,
            ],
            [
                'nim' => '220002',
                'nama' => 'Siti Rahmawati',
                'tanggal_lahir' => '2004-02-10',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 1,
                'prodi_id' => 2,
                'kelas_id' => 2,
            ],
            [
                'nim' => '220003',
                'nama' => 'Dimas Pratama',
                'tanggal_lahir' => '2003-09-21',
                'email' => 'dimas@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 2,
                'prodi_id' => 3,
                'kelas_id' => 3,
            ],
        ]);
    }
}
