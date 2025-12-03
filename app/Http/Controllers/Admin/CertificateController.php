<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Mahasiswa;
use App\Models\EptSchedule;
use Inertia\Inertia;
// Removed: use Illuminate\Http\Request as Request; - This caused a conflict.

class CertificateController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::whereHas('eptScores')->with('eptScores')->get();

        return Inertia::render('admin/Certificate/Index', [
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function show($id)
    {
        $mahasiswa = Mahasiswa::with('eptScores', 'jurusan')->findOrFail($id);
        
        // For simplicity, we'll take the first EPT score.
        // In a real application, you might need a way to select which score to print.
        $eptScore = $mahasiswa->eptScores->first();

        if (!$eptScore) {
            return redirect()->back()->with('error', 'No EPT score found for this student.');
        }

        // Find the test date. Assumption: Match by jurusan and year.
        $testDate = EptSchedule::where('jurusan_id', $mahasiswa->jurusan_id)
                                ->whereYear('tanggal', $eptScore->tahun)
                                ->first();

        return Inertia::render('admin/Certificate/Show', [
            'mahasiswa' => $mahasiswa,
            'eptScore' => $eptScore,
            'testDate' => $testDate ? $testDate->tanggal : null,
        ]);
    }
}
