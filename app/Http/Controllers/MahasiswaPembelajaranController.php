<?php

namespace App\Http\Controllers;

use App\Models\MaterialPembelajaran;
use Inertia\Inertia;

use Illuminate\Support\Facades\Storage;

class MahasiswaPembelajaranController extends Controller
{
    public function index()
    {
        // Ambil semua data materi, urutkan berdasarkan ID terbaru
        $materis = MaterialPembelajaran::orderBy('id', 'desc')
            ->get()
            ->map(function ($materi) {
                if ($materi->link_pdf) {
                    $materi->link_pdf_url = Storage::url($materi->link_pdf);
                    // Extract file extension
                    $extension = pathinfo($materi->link_pdf, PATHINFO_EXTENSION);
                    $materi->file_type = strtolower($extension); // 'pdf', 'ppt', 'pptx'
                }
                return $materi;
            });

        return Inertia::render('mahasiswa/Pembelajaran', [
            'materis' => $materis,
        ]);
    }
}