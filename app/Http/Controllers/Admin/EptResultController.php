<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EptResultPesertaMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EptResultImport; // <--- PENTING: Tambahkan ini
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class EptResultController extends Controller
{
    // ... method index yang sudah kamu buat ...
   public function index(Request $request)
    {
        // 1. Ambil Input Parameter
        $perPage = $request->input('per_page', 20);
        // Fix sortField kosong jadi default created_at
        $sortField = $request->input('sortField') ?: 'created_at'; 
        $sortOrder = $request->input('sortOrder', 'desc');
        $globalFilter = $request->input('globalFilter');
        $tahun = $request->input('tahun');
        $mahasiswaId = $request->input('mahasiswa_id');

        // 2. Query Utama Data EPT
        $results = EptResultPesertaMahasiswa::with('mahasiswa')
            ->when($globalFilter, function ($query, $filter) {
                $query->whereHas('mahasiswa', function ($q) use ($filter) {
                    $q->where('nama', 'like', '%' . $filter . '%')
                      ->orWhere('nim', 'like', '%' . $filter . '%');
                });
            })
            ->when($tahun, function ($query, $tahun) {
                $query->where('tahun', $tahun);
            })
            ->when($mahasiswaId, function ($query, $id) {
                $query->where('mahasiswa_id', $id);
            })
            ->orderBy($sortField, $sortOrder === 'desc' ? 'desc' : 'asc')
            ->paginate($perPage)
            ->withQueryString();

        $mahasiswas = Mahasiswa::select('id', 'nama', 'nim')->orderBy('nama')->get(); 

        // 4. Return ke View
        return Inertia::render('admin/EPT/Index', [
            'results' => $results,
            'mahasiswas' => $mahasiswas, // <--- PASTIKAN INI ADA
            'filters' => $request->only(['sortField', 'sortOrder', 'globalFilter', 'tahun', 'mahasiswa_id']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'tahun'        => 'required|numeric',
            'listening'    => 'required|numeric|min:0',
            'structure'    => 'required|numeric|min:0',
            'reading'      => 'required|numeric|min:0',
            'total_score'  => 'required|numeric|min:0',
            'sertifikat_pdf' => 'nullable|file|mimes:pdf|max:2048', // Max 2MB
        ]);

        // Handle Upload PDF jika ada
        if ($request->hasFile('sertifikat_pdf')) {
            $path = $request->file('sertifikat_pdf')->store('sertifikat_ept', 'public');
            $validated['sertifikat_pdf'] = $path;
        }

        EptResultPesertaMahasiswa::create($validated);

        return redirect()->back()->with('success', 'Data EPT berhasil ditambahkan.');
    }

    /**
     * Method untuk Import Excel
     */
    public function import(Request $request)
    {
        // 1. Validasi File
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // Max 5MB
        ]);

        try {
            // 2. Eksekusi Import menggunakan Class yang kita buat
            Excel::import(new EptResultImport, $request->file('file'));

            return redirect()->back()->with('success', 'Data EPT berhasil diimport!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             // Handle jika ada format excel yang salah (misal NIM tidak ada di DB)
             $failures = $e->failures();
             $errorMsg = "Gagal import pada baris: " . $failures[0]->row() . ". " . $failures[0]->errors()[0];
             
             return redirect()->back()->withErrors(['file' => $errorMsg]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => 'Terjadi kesalahan saat import: ' . $e->getMessage()]);
        }
    }
}