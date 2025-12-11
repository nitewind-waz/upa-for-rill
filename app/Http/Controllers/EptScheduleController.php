<?php

namespace App\Http\Controllers;

use App\Models\EptSchedule;
use App\Models\Gedung;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\JadwalPublik; // Import the new model
use App\Models\Publik; // Import Publik model
use Illuminate\Http\Request;
use Inertia\Inertia;

class EptScheduleController extends Controller
{
    // mahasiswa - tampil daftar jadwal 
    public function index()
    {
        // Fetch student schedules
        $jadwalMahasiswa = EptSchedule::with([
            'jurusan:id,nama_jurusan', 
            'prodi:id,nama_prodi,jurusan_id', 
            'kelas:id,nama_kelas', 
            'ruang:id,nama', 
            'gedung:id,nama'
        ])
        ->whereNotNull('kelas_id') // Filter for student schedules
        ->orderBy('tanggal', 'asc')
        ->get();

        // Fetch public schedules
        $jadwalPublik = JadwalPublik::with([
            'publik:id,nama_lengkap,nik', // Load publik with nik and nama_lengkap
            'ruang:id,nama', 
            'gedung:id,nama'
        ])
        ->orderBy('tanggal', 'asc')
        ->get();

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
        // Fetch student schedules
        $jadwalMahasiswa = EptSchedule::with(['jurusan', 'prodi', 'kelas', 'ruang', 'gedung'])
                                    ->orderBy('tanggal', 'asc')
                                    ->get();

        // Fetch public schedules
        $jadwalPublik = JadwalPublik::with(['publik', 'ruang', 'gedung'])
                                    ->orderBy('tanggal', 'asc')
                                    ->get();

        return Inertia::render('admin/EptSchedulePage', [
            'jadwal' => $jadwalMahasiswa,
            'jadwalPublik' => $jadwalPublik,
            'publik' => Publik::all(), 
            'jurusan' => Jurusan::all(),
            'prodi' => Prodi::all(),
            'kelas' => Kelas::all(),
            'gedung' => Gedung::all(),
            'ruang' => Ruang::all(),
            'success' => session('success')
        ]);
    }

    // admin - simpan jadwal baru (Mahasiswa)
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

    // admin - edit jadwal (Mahasiswa)
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

    // admin - hapus jadwal (Mahasiswa)
    public function destroy($id)
    {
        $jadwal = EptSchedule::findOrFail($id);
        $jadwal->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
    }

    // Admin - Simpan jadwal publik baru
    public function storePublik(Request $request)
    {
        $request->validate([
            'publik_id' => 'required|exists:publiks,id',
            'ruang_id' => 'nullable|exists:ruangs,id',
            'gedung_id' => 'nullable|exists:gedungs,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required', // Mapped to jam_mulai
            'waktu_selesai' => 'required', // Mapped to jam_selesai
        ]);

        JadwalPublik::create([
            'publik_id' => $request->publik_id,
            'ruang_id' => $request->ruang_id,
            'gedung_id' => $request->gedung_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->waktu_mulai,
            'jam_selesai' => $request->waktu_selesai,
        ]);

        return redirect()->back()->with('success', 'Jadwal publik berhasil ditambahkan!');
    }

    // Admin - Edit jadwal publik
    public function updatePublik(Request $request, $id)
    {
        $jadwal = JadwalPublik::findOrFail($id);
        $request->validate([
            'publik_id' => 'required|exists:publiks,id',
            'ruang_id' => 'nullable|exists:ruangs,id',
            'gedung_id' => 'nullable|exists:gedungs,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required', // Mapped to jam_mulai
            'waktu_selesai' => 'required', // Mapped to jam_selesai
        ]);
        
        $jadwal->update([
            'publik_id' => $request->publik_id,
            'ruang_id' => $request->ruang_id,
            'gedung_id' => $request->gedung_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->waktu_mulai,
            'jam_selesai' => $request->waktu_selesai,
        ]);
        return redirect()->back()->with('success', 'Jadwal publik berhasil diperbarui!');
    }

    // Admin - Hapus jadwal publik
    public function destroyPublik($id)
    {
        $jadwal = JadwalPublik::findOrFail($id);
        $jadwal->delete();
        return redirect()->back()->with('success', 'Jadwal publik berhasil dihapus!');
    }

    // API - get all schedules
    public function getSchedules()
    {
        // This method will likely need to be re-evaluated if it's still needed and how it combines both types of schedules.
        // For now, it will only return EptSchedule (student schedules) as before.
        $jadwals = EptSchedule::with(['jurusan', 'prodi', 'kelas', 'ruang', 'gedung'])->orderBy('tanggal', 'asc')->get();
        return response()->json($jadwals);
    }
}
