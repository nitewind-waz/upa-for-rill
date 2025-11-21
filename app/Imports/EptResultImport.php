<?php

namespace App\Imports;

use App\Models\EptResultPesertaMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Jurusan;
use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class EptResultImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        // --- 1. CEK DATA KOSONG (Double Protection) ---
        if (!isset($row['jurusan']) || !isset($row['nim'])) {
            return null;
        }

        // --- 2. VALIDASI MANUAL ---
        $validator = Validator::make($row, [
            'jurusan'   => 'required',
            'prodi'     => 'required',
            'kelas'     => 'required|numeric',
            'nim'       => 'required',
            'nama'      => 'required',
            'tahun'     => 'required|numeric',
            'listening' => 'required|numeric',
            'structure' => 'required|numeric',
            'reading'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $errorMsg = "Error pada NIM " . ($row['nim'] ?? 'Unknown') . ": " . $validator->errors()->first();
            throw new \Exception($errorMsg);
        }

        // --- 3. SIMPAN DATA ---

        // A. JURUSAN
        $namaJurusan = Str::title($row['jurusan']);
        $jurusan = Jurusan::firstOrCreate(
            ['nama_jurusan' => $namaJurusan],
            ['nama_jurusan' => $namaJurusan]
        );

        // B. PRODI
        $namaProdi = Str::title($row['prodi']);
        $prodi = Prodi::firstOrCreate(
            ['nama_prodi' => $namaProdi, 'jurusan_id' => $jurusan->id],
            ['nama_prodi' => $namaProdi, 'jurusan_id' => $jurusan->id]
        );

        // C. MAHASISWA
        $defaultBirthdate = '2000-01-01';

        $mahasiswa = Mahasiswa::firstOrCreate(
            ['nim' => $row['nim']],
            [
                'nama'          => $row['nama'],
                'jurusan_id'    => $jurusan->id,
                'prodi_id'      => $prodi->id,
                'kelas_id'      => $row['kelas'],
                'angkatan'      => $row['tahun'] ?? date('Y'),
                'tanggal_lahir' => isset($row['tanggal_lahir']) && !empty($row['tanggal_lahir'])
                                    ? \Carbon\Carbon::parse($row['tanggal_lahir'])->format('Y-m-d')
                                    : $defaultBirthdate,
            ]
        );

        // D. TOTAL SCORE
        $listening = $row['listening'];
        $structure = $row['structure'];
        $reading   = $row['reading'];

        if (isset($row['total_score'])) {
            $rawScore = str_replace(',', '.', $row['total_score']);
            if (is_numeric($rawScore)) {
                $totalScore = round((float) $rawScore);
            } else {
                $totalScore = round((($listening + $structure + $reading) * 10) / 3);
            }
        } else {
            $totalScore = round((($listening + $structure + $reading) * 10) / 3);
        }

        // E. RETURN
        return new EptResultPesertaMahasiswa([
            'mahasiswa_id' => $mahasiswa->id,
            'tahun'        => $row['tahun'],
            'listening'    => $listening,
            'structure'    => $structure,
            'reading'      => $reading,
            'total_score'  => $totalScore,
        ]);
    }
}
