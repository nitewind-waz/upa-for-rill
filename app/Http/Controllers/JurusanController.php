<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Inertia\Inertia;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();

        // Passing data ke halaman Vue bernama "Hasil"
        return Inertia::render('mahasiswa/Hasil', [
            'jurusan' => $jurusan,
            'activeTab' => 'Jurusan',
        ]);
    }
}
