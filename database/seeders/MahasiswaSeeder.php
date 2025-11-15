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
                'jurusan_id' => 1, 'prodi_id' => 1, 'kelas_id' => 1,
            ],
            [
                'nim' => '220002',
                'nama' => 'Siti Rahmawati',
                'tanggal_lahir' => '2004-02-10',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 1, 'prodi_id' => 2, 'kelas_id' => 3,
            ],
            [
                'nim' => '220003',
                'nama' => 'Dimas Pratama',
                'tanggal_lahir' => '2003-09-21',
                'email' => 'dimas@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 2, 'prodi_id' => 3, 'kelas_id' => 4,
            ],
            [
                'nim' => '220004',
                'nama' => 'Budi Santoso',
                'tanggal_lahir' => '2003-01-01',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 3, 'prodi_id' => 5, 'kelas_id' => 6,
            ],
            [
                'nim' => '220005',
                'nama' => 'Citra Lestari',
                'tanggal_lahir' => '2004-05-05',
                'email' => 'citra@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 4, 'prodi_id' => 7, 'kelas_id' => 8,
            ],
            [
                'nim' => '220006',
                'nama' => 'Dewi Anggraini',
                'tanggal_lahir' => '2003-11-30',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 5, 'prodi_id' => 9, 'kelas_id' => 10,
            ],
            [
                'nim' => '220007',
                'nama' => 'Eko Prasetyo',
                'tanggal_lahir' => '2004-08-17',
                'email' => 'eko@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 6, 'prodi_id' => 11, 'kelas_id' => 12,
            ],
            [
                'nim' => '220008',
                'nama' => 'Fitriani',
                'tanggal_lahir' => '2003-03-12',
                'email' => 'fitri@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 1, 'prodi_id' => 1, 'kelas_id' => 2,
            ],
            [
                'nim' => '220009',
                'nama' => 'Gunawan',
                'tanggal_lahir' => '2004-07-22',
                'email' => 'gunawan@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 2, 'prodi_id' => 4, 'kelas_id' => 5,
            ],
            [
                'nim' => '220010',
                'nama' => 'Heru Wibowo',
                'tanggal_lahir' => '2003-12-25',
                'email' => 'heru@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 3, 'prodi_id' => 6, 'kelas_id' => 7,
            ],
            [
                'nim' => '220011',
                'nama' => 'Indah Permata',
                'tanggal_lahir' => '2004-10-08',
                'email' => 'indah@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 4, 'prodi_id' => 8, 'kelas_id' => 9,
            ],
            [
                'nim' => '220012',
                'nama' => 'Joko Susilo',
                'tanggal_lahir' => '2003-02-14',
                'email' => 'joko@example.com',
                'password' => Hash::make('password123'),
                'jurusan_id' => 5, 'prodi_id' => 10, 'kelas_id' => 11,
            ],
        ]);
    }
}
