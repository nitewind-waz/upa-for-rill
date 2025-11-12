<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Inertia\Inertia;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();

        return Inertia::render('mahasiswa/Hasil', [
            'kelas' => $kelas,
            'activeTab' => 'Kelas'
        ]);
    }
}
