<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
// Import Model Anda (Sesuaikan nama model yang ada di projectmu)
use App\Models\Course;
use App\Models\Berita;
use App\Models\Acara;
// use App\Models\EptResult; // Jika ada model EPT
// use App\Models\User;      // Jika ada model User/Mahasiswa

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/AdminDashboard', [
            'stats' => [
                // Contoh data statistik (Ganti Model::count() sesuai model yang kamu punya)
                'courses'   => Course::count(),
                'berita'    => Berita::count(),
                'acara'     => Acara::count(),
                'mahasiswa' => 120, // Dummy jika model User belum siap, ganti User::count() nanti
            ],
            'recent_activities' => [
                // Contoh data dummy aktivitas
                ['action' => 'Upload Berita', 'desc' => 'Pengumuman EPT November', 'time' => '2 jam lalu'],
                ['action' => 'Update Kursus', 'desc' => 'General English Level 1', 'time' => '5 jam lalu'],
                ['action' => 'Input Nilai', 'desc' => 'Import Excel EPT Batch 2', 'time' => '1 hari lalu'],
            ]
        ]);
    }
}