<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        // Data Prodi Polban (Lengkap dengan jenjang di dalam namanya)
        $dataPolban = [
            'Teknik Sipil' => [
                'D3 Teknik Sipil',
                'D3 Teknik Konstruksi Gedung',
                'D4 Teknik Perancangan Jalan dan Jembatan',
                'D4 Teknik Perawatan dan Perbaikan Gedung',
                'Magister Terapan Rekayasa Infrastruktur',
            ],
            'Teknik Mesin' => [
                'D3 Teknik Mesin',
                'D3 Teknik Aeronautika',
                'D4 Teknik Perancangan dan Konstruksi Mesin',
                'D4 Proses Manufaktur',
            ],
            'Teknik Refrigerasi dan Tata Udara' => [
                'D3 Teknik Pendingin dan Tata Udara',
                'D4 Teknik Pendingin dan Tata Udara',
            ],
            'Teknik Konversi Energi' => [
                'D3 Teknik Konversi Energi',
                'D4 Teknologi Pembangkit Tenaga Listrik',
                'D4 Teknik Konservasi Energi',
            ],
            'Teknik Elektro' => [
                'D3 Teknik Elektronika',
                'D3 Teknik Listrik',
                'D3 Teknik Telekomunikasi',
                'D4 Teknik Elektronika',
                'D4 Teknik Telekomunikasi',
                'D4 Teknik Otomasi Industri',
            ],
            'Teknik Kimia' => [
                'D3 Teknik Kimia',
                'D3 Analis Kimia',
                'D4 Teknik Kimia Produksi Bersih',
            ],
            'Teknik Komputer dan Informatika' => [
                'D3 Teknik Informatika',
                'D4 Teknik Informatika',
            ],
            'Akuntansi' => [
                'D3 Akuntansi',
                'D3 Keuangan dan Perbankan',
                'D4 Akuntansi Manajemen Pemerintahan',
                'D4 Akuntansi',
                'D4 Keuangan Syariah',
                'Magister Terapan Keuangan dan Perbankan Syariah',
            ],
            'Administrasi Niaga' => [
                'D3 Administrasi Bisnis',
                'D3 Manajemen Pemasaran',
                'D3 Usaha Perjalanan Wisata',
                'D4 Manajemen Aset',
                'D4 Manajemen Pemasaran',
                'D4 Destinasi Pariwisata', 
                'D4 Usaha Perjalanan Wisata',
            ],
            'Bahasa Inggris' => [
                'D3 Bahasa Inggris',
            ],
        ];

        foreach ($dataPolban as $jurusanName => $listProdi) {
            // 1. Cari ID Jurusan
            $jurusan = Jurusan::where('nama_jurusan', $jurusanName)->first();

            if ($jurusan) {
                foreach ($listProdi as $namaProdi) {
                    // 2. Simpan Prodi (Cek biar gak duplikat)
                    Prodi::firstOrCreate([
                        'jurusan_id' => $jurusan->id,
                        'nama_prodi'       => $namaProdi
                    ]);
                }
            }
        }
    }
}