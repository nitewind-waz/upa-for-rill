<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\EptSchedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EptScheduleController extends Controller
{
    //  mahasiswa - tampil daftar jadwal 
    public function index()
    {
        $jadwals = EptSchedule::orderBy('tanggal', 'asc')->get();

        return Inertia::render('mahasiswa/Jadwal', [
            'jadwal' => $jadwals
        ]);
    }

    // halaman admin
    public function adminIndex()
    {
        $jadwals = EptSchedule::orderBy('tanggal', 'asc')->get();

        return Inertia::render('admin/EptSchedulePage', [
            'jadwal' => $jadwals,
            'success' => session('success')
        ]);
    }



    //  admin - simpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'tempat' => 'required|string',
            'gedung' => 'nullable|string',
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
}
