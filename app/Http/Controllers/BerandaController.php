<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Berita;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BerandaController extends Controller
{
    public function index()
    {
        $berita = Berita::latest()->get();
        $acara = Acara::latest()->get();

        return Inertia::render('mahasiswa/Beranda', [
            'berita' => $berita,
            'acara' => $acara,
        ]);
    }
}
