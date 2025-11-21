<?php

namespace App\Http\Controllers;

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
        $jadwals = EptSchedule::with(['jurusan', 'prodi', 'kelas', 'ruang', 'gedung'])->orderBy('tanggal', 'asc')->get();
        return Inertia::render('mahasiswa/Jadwal', [
            'jadwal' => $jadwals,
            'jurusan' => Jurusan::all(),
            'prodi' => Prodi::all(),
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
            'kelas_id' => 'required|exists:kelas,id',
            'ruang_id' => 'required|exists:ruangs,id',
            'gedung_id' => 'required|exists:gedungs,id',
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
            'kelas_id' => 'required|exists:kelas,id',
            'ruang_id' => 'required|exists:ruangs,id',
            'gedung_id' => 'required|exists:gedungs,id',
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
