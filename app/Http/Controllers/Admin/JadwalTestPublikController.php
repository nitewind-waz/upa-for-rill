<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalTestPublik;
use App\Models\Publik; // Import the Publik model
use Illuminate\Http\Request;

class JadwalTestPublikController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruang_id' => 'required|exists:ruangs,id',
            'gedung_id' => 'required|exists:gedungs,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'nik' => 'required|string|max:20|unique:publiks,nik', // Validate NIK
            'nama_lengkap' => 'required|string|max:255', // Validate Nama Lengkap
        ]);

        // Find or create Publik entry
        $publik = Publik::firstOrCreate(
            ['nik' => $validated['nik']],
            ['nama_lengkap' => $validated['nama_lengkap']]
        );

        // Create JadwalTestPublik with publik_id
        JadwalTestPublik::create([
            'ruang_id' => $validated['ruang_id'],
            'gedung_id' => $validated['gedung_id'],
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'publik_id' => $publik->id, // Associate with the publik
        ]);

        return redirect()->back()->with('success', 'Jadwal publik berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalTestPublik::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal publik berhasil dihapus.');
    }
}
