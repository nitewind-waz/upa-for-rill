<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->input('year');

        // Ambil semua tahun unik dari kedua tabel
        $beritaYears = Berita::select(DB::raw('YEAR(tanggal) as year'))
            ->distinct()
            ->pluck('year');
            
        $acaraYears = Acara::select(DB::raw('YEAR(tanggal_acara) as year'))
            ->distinct()
            ->pluck('year');

        // Gabungkan, urutkan, dan buat unik
        $years = $beritaYears->concat($acaraYears)
            ->unique()
            ->sortDesc()
            ->values();

        // Query dasar
        $beritaQuery = Berita::query();
        $acaraQuery = Acara::query();

        // Filter berdasarkan tahun jika dipilih
        if ($selectedYear) {
            $beritaQuery->whereYear('tanggal', $selectedYear);
            $acaraQuery->whereYear('tanggal_acara', $selectedYear);
        }

        $berita = $beritaQuery->latest('tanggal')->get();
        $acara = $acaraQuery->latest('tanggal_acara')->get();

        return Inertia::render('mahasiswa/Beranda', [
            'berita' => $berita,
            'acara' => $acara,
            'years' => $years,
            'selectedYear' => $selectedYear,
        ]);
    }
}
