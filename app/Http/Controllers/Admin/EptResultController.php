<?php

namespace App\Http\Controllers;

use App\Models\EptResultMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EptResultController extends Controller
{
    /**
     * Tampilkan halaman index + form input + form import.
     */
    public function index()
    {
        $results = EptResultMahasiswa::with('mahasiswa')->latest()->paginate(20);
        $mahasiswas = Mahasiswa::all();

        return view('ept.index', compact('results', 'mahasiswas'));
    }

    /**
     * Simpan input manual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'tahun' => 'required|digits:4',
            'listening' => 'required|integer',
            'structure' => 'required|integer',
            'reading' => 'required|integer',
            'total_score' => 'required|integer',
            'sertifikat_pdf' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $data = $request->only([
            'mahasiswa_id',
            'tahun',
            'listening',
            'structure',
            'reading',
            'total_score',
        ]);

        // Upload PDF jika ada
        if ($request->hasFile('sertifikat_pdf')) {
            $data['sertifikat_pdf'] = $request->file('sertifikat_pdf')->store('sertifikat_ept', 'public');
        }

        EptResultMahasiswa::create($data);

        return back()->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Import Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new \App\Imports\EptImport, $request->file('file'));

        return back()->with('success', 'Import berhasil.');
    }
}
