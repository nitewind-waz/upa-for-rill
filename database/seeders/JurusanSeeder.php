<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        // Data sesuai gambar yang kamu upload
        $dataJurusan = [
            'Teknik Sipil',
            'Teknik Mesin',
            'Teknik Refrigerasi dan Tata Udara',
            'Teknik Konversi Energi',
            'Teknik Elektro',
            'Teknik Kimia',
            'Teknik Komputer dan Informatika', // Biasa disingkat JTK
            'Akuntansi',
            'Administrasi Niaga',
            'Bahasa Inggris',
        ];

        foreach ($dataJurusan as $nama) {
            // firstOrCreate mencegah duplikasi data jika seeder dijalankan 2x
            Jurusan::firstOrCreate(
                ['nama_jurusan' => $nama], // Cek berdasarkan nama
                ['nama_jurusan' => $nama]  // Data yang diinsert
            );
        }
    }
}