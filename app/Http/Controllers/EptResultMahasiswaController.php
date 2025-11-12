<?php

namespace App\Http\Controllers;

use App\Models\EptResultMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EptResultMahasiswaController extends Controller
{
    /**
     * Menampilkan halaman hasil EPT (Jurusan, Prodi, Kelas, Individu)
     */
    public function index()
    {
        return Inertia::render('mahasiswa/Hasil', [
            'activeTab' => 'Individu'
        ]);
    }

    /**
     * Cek hasil EPT individu berdasarkan NIM dan password.
     */
    public function checkResult(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari mahasiswa berdasarkan NIM
        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();

        // Validasi NIM
        if (!$mahasiswa){
            return response()->json(['message' => 'NIM tidak ditemukan!'], 404);
        }

        // Validasi Password
        if (! Hash::check($validated['password'], $mahasiswa->password)) {
            return response()->json(['message' => 'Password salah!'], 401);
        }

        // Ambil hasil EPT mahasiswa
        $results = EptResultMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('tahun')
            ->get(['tahun', 'listening', 'structure', 'reading', 'total_score', 'sertifikat_pdf']);

        return response()->json([
            'message' => 'Login berhasil',
            'mahasiswa' => [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
            ],
            'results' => $results,
        ]);
    }
}
