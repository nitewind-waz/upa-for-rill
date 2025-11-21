<?php

namespace App\Imports;

use App\Models\EptResultMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Jurusan;
use App\Models\Prodi;
// use App\Models\Kelas; // Tidak perlu di-import jika cuma ambil ID
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

// PENTING: Kita HAPUS 'WithValidation' agar kita bisa validasi manual di dalam
class EptResultImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        // --- 1. CEK DATA KOSONG (Double Protection) ---
        // Kadang SkipsEmptyRows saja tidak cukup, kita cek manual.
        if (!isset($row['jurusan']) || !isset($row['nim'])) {
            return null; // Skip baris ini jika jurusan atau NIM tidak terbaca
        }

        // --- 2. VALIDASI MANUAL ---
        // Kita validasi di sini supaya error-nya jelas dan terkontrol
        $validator = Validator::make($row, [
            'jurusan'   => 'required',
            'prodi'     => 'required',
            'kelas'     => 'required|numeric', // Pastikan ini Angka (ID Kelas)
            'nim'       => 'required',
            'nama'      => 'required',
            'tahun'     => 'required|numeric',
            'listening' => 'required|numeric',
            'structure' => 'required|numeric',
            'reading'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            // Lempar error biar Controller menangkapnya
            // Kita tambahkan info NIM biar tau baris mana yang salah
            $errorMsg = "Error pada NIM " . ($row['nim'] ?? 'Unknown') . ": " . $validator->errors()->first();
            throw new \Exception($errorMsg);
        }

        // --- 3. LOGIC SIMPAN DATA (Sama seperti sebelumnya) ---

        // A. Handle JURUSAN
        $namaJurusan = Str::title($row['jurusan']); 
        $jurusan = Jurusan::firstOrCreate(
            ['nama_jurusan' => $namaJurusan], 
            ['nama_jurusan' => $namaJurusan]
        );

        // B. Handle PRODI
        $namaProdi = Str::title($row['prodi']);
        $prodi = Prodi::firstOrCreate(
            ['nama_prodi' => $namaProdi, 'jurusan_id' => $jurusan->id],
            ['nama_prodi' => $namaProdi, 'jurusan_id' => $jurusan->id]
        );

        // C. Handle MAHASISWA
        $mahasiswa = Mahasiswa::firstOrCreate(
            ['nim' => $row['nim']], 
            [
                'nama'       => $row['nama'],
                'jurusan_id' => $jurusan->id,
                'prodi_id'   => $prodi->id,
                'kelas_id'   => $row['kelas'], // Pastikan di Excel isinya ANGKA ID KELAS (contoh: 1, 2, 5)
                'angkatan'   => $row['tahun'] ?? date('Y'),
            ]
        );

        // D. Handle TOTAL SCORE (Fix Desimal & Rumus)
        $listening = $row['listening'];
        $structure = $row['structure'];
        $reading   = $row['reading'];
        
        if (isset($row['total_score'])) {
            // Ubah koma jadi titik, lalu bulatkan
            $rawScore = str_replace(',', '.', $row['total_score']);
            // Pastikan numeric sebelum di-round
            if (is_numeric($rawScore)) {
                $totalScore = round((float)$rawScore);
            } else {
                // Kalau isinya rumus error/text, hitung manual
                $totalScore = round((($listening + $structure + $reading) * 10) / 3);
            }
        } else {
            $totalScore = round((($listening + $structure + $reading) * 10) / 3);
        }

        // E. Return Hasil
        return new EptResultMahasiswa([
            'mahasiswa_id' => $mahasiswa->id,
            'tahun'        => $row['tahun'],
            'listening'    => $listening,
            'structure'    => $structure,
            'reading'      => $reading,
            'total_score'  => $totalScore,
        ]);
    }
    
    // Function rules() KITA HAPUS karena sudah divalidasi manual di atas.
}