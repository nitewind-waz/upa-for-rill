<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Inertia\Inertia;

class ProdiController extends Controller
{
    public function index()
    {
        $prodi = Prodi::all();

        return Inertia::render('mahasiswa/Hasil', [
            'prodi' => $prodi,
            'activeTab' => 'Prodi'
        ]);
    }
}
