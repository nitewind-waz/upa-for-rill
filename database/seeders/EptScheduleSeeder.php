<?php

namespace Database\Seeders;

use App\Models\EptSchedule;
use App\Models\Gedung;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Ruang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EptScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data di tabel-tabel terkait
        $jurusan = Jurusan::first();
        $prodi = Prodi::first();
        $kelas = Kelas::first();
        $ruang = Ruang::first();
        $gedung = Gedung::first();

        // Hanya jika semua relasi ditemukan
        if ($jurusan && $prodi && $kelas && $ruang && $gedung) {
            EptSchedule::create([
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'kelas_id' => $kelas->id,
                'ruang_id' => $ruang->id,
                'gedung_id' => $gedung->id,
                'tanggal' => now()->addDays(7)->format('Y-m-d'),
                'waktu_mulai' => '09:00:00',
                'waktu_selesai' => '11:00:00',
            ]);

            EptSchedule::create([
                'jurusan_id' => $jurusan->id,
                'prodi_id' => $prodi->id,
                'kelas_id' => $kelas->id,
                'ruang_id' => $ruang->id,
                'gedung_id' => $gedung->id,
                'tanggal' => now()->addDays(14)->format('Y-m-d'),
                'waktu_mulai' => '13:00:00',
                'waktu_selesai' => '15:00:00',
            ]);
        } else {
            $this->command->info('Tidak dapat membuat EptSchedule karena data Jurusan, Prodi, Kelas, Ruang, atau Gedung belum ada.');
        }
    }
}
