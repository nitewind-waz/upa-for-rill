<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\EptSchedule;
use App\Models\Gedung;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EptScheduleController extends Controller
{
    //  mahasiswa - tampil daftar jadwal 
    public function index()
    {
        // Pastikan semua relasi di-load dengan benar
        $allJadwals = EptSchedule::with([
            'jurusan:id,nama_jurusan', 
            'prodi:id,nama_prodi,jurusan_id', 
            'kelas:id,nama_kelas', 
            'ruang:id,nama', 
            'gedung:id,nama'
        ])
        ->orderBy('tanggal', 'asc')
        ->get();

        $jadwalMahasiswa = $allJadwals->filter(function($jadwal) {
            return !is_null($jadwal->kelas_id);
        })->values()->toArray(); // Konversi ke array

        $jadwalPublik = $allJadwals->filter(function($jadwal) {
            return is_null($jadwal->kelas_id);
        })->values()->toArray(); // Konversi ke array

        return Inertia::render('mahasiswa/Jadwal', [
            'jadwalMahasiswa' => $jadwalMahasiswa,
            'jadwalPublik' => $jadwalPublik,
            'jurusan' => Jurusan::select('id', 'nama_jurusan')->get(),
            'prodi' => Prodi::select('id', 'nama_prodi', 'jurusan_id')->get(),
        ]);
    }

    // halaman admin
    public function adminIndex()
    {
        $jadwals = EptSchedule::with(['jurusan', 'prodi', 'kelas', 'ruang', 'gedung'])->orderBy('tanggal', 'asc')->get();

        return Inertia::render('admin/EptSchedulePage', [
            'jadwal' => $jadwals,
            'jurusan' => Jurusan::all(),
            'prodi' => Prodi::all(),
            'kelas' => Kelas::all(),
            'gedung' => Gedung::all(),
            'ruang' => Ruang::all(),
            'success' => session('success')
        ]);
    }



    //  admin - simpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'prodi_id' => 'required|exists:prodis,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'ruang_id' => 'nullable|exists:ruangs,id',
            'gedung_id' => 'nullable|exists:gedungs,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
        ]);

        EptSchedule::create($request->all());

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    //  admin - edit jadwal
    public function update(Request $request, $id)
    {
        $jadwal = EptSchedule::findOrFail($id);
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'prodi_id' => 'required|exists:prodis,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'ruang_id' => 'nullable|exists:ruangs,id',
            'gedung_id' => 'nullable|exists:gedungs,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
        ]);
        $jadwal->update($request->all());
        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    //  admin - hapus jadwal
    public function destroy($id)
    {
        $jadwal = EptSchedule::findOrFail($id);
        $jadwal->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
    }

    // API - get all schedules
    public function getSchedules()
    {
        $jadwals = EptSchedule::with(['jurusan', 'prodi', 'kelas', 'ruang', 'gedung'])->orderBy('tanggal', 'asc')->get();
        return response()->json($jadwals);
    }
}
