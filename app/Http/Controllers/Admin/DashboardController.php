<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use App\Models\Berita;
use App\Models\Course;
use App\Models\Mahasiswa;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'courses' => Course::count(),
            'mahasiswa' => Mahasiswa::count(),
            'berita' => Berita::count(),
            'acara' => Acara::count(),
        ];

        return Inertia::render('admin/AdminDashboard', [
            'stats' => $stats,
        ]);
    }
}
