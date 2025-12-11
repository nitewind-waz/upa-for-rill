<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// --- CONTROLLERS ---
// Public / Mahasiswa
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PublicCourseController;
use App\Http\Controllers\EptResultPesertaController;

// Admin
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController; // Pastikan buat controller ini atau pakai closure
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\AcaraController;
use App\Http\Controllers\Admin\EptResultController;
use App\Http\Controllers\EptScheduleController;
use App\Http\Controllers\MaterialPembelajaranController;
use App\Http\Controllers\MahasiswaPembelajaranController;
use App\Http\Controllers\Admin\CertificateController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Mahasiswa / Visitor)
|--------------------------------------------------------------------------
| Bisa diakses tanpa login.
*/

// Beranda
Route::get('/', [BerandaController::class, 'index'])->name('home');

// Program Kursus
Route::get('/course', [PublicCourseController::class, 'index'])->name('course.index');

// Jadwal EPT (View Mahasiswa)
Route::get('/jadwal', [EptScheduleController::class, 'index'])->name('jadwal');

// Hasil EPT (Cek Nilai)
Route::controller(EptResultPesertaController::class)->prefix('hasil')->name('hasil')->group(function () {
    Route::get('/', 'index');
    Route::post('/check', 'checkResult')->name('.check');
    Route::get('/stats', 'getStats')->name('.stats');
});

// Pembelajaran (View Mahasiswa)
Route::get('/pembelajaran', [MahasiswaPembelajaranController::class, 'index'])->name('pembelajaran');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
| Semua route di bawah ini diawali dengan prefix '/admin'
| dan nama route 'admin.*'
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // ----------------------------------------------------------
    // 1. GUEST ADMIN (Belum Login)
    // ----------------------------------------------------------
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // ----------------------------------------------------------
    // 2. PROTECTED ADMIN (Sudah Login)
    // ----------------------------------------------------------
    Route::middleware('auth:admin')->group(function () {

        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --- MANAJEMEN COURSE (CRUD) ---
        // Menggunakan resource controller standard
        Route::resource('course', CourseController::class);

        // --- MANAJEMEN BERITA (CRUD) ---
        Route::resource('berita', BeritaController::class)->parameters([
            'berita' => 'berita',
        ]);

        // --- MANAJEMEN ACARA (CRUD) ---
        Route::resource('acara', AcaraController::class)->parameters([
            'acara' => 'acara',
        ]);

        // --- MANAJEMEN JADWAL EPT ---
        // Karena controller jadwal menyatu/custom method, kita definisikan manual
        Route::controller(EptScheduleController::class)->prefix('jadwal')->name('jadwal.')->group(function () {
            Route::get('/', 'adminIndex')->name('index');   // admin.jadwal.index
            Route::post('/', 'store')->name('store');       // admin.jadwal.store
            Route::put('/{id}', 'update')->name('update');  // admin.jadwal.update
            Route::delete('/{id}', 'destroy')->name('delete'); // admin.jadwal.delete
        });

        // --- MANAJEMEN HASIL EPT (RESULT) ---
        Route::controller(EptResultController::class)->prefix('ept')->name('ept.')->group(function () {
            Route::get('/', 'index')->name('index'); 
            Route::post('/', 'store')->name('store');  
            Route::put('/{id}', 'update')->name('update'); 
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/import', 'import')->name('import'); 
            Route::get('/template', 'downloadTemplate')->name('template');
        });

        // --- MANAJEMEN SERTIFIKAT EPT ---
        Route::controller(CertificateController::class)->prefix('certificate')->name('certificate.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
        });

        // --- MANAJEMEN PEMBELAJARAN (MATERI) ---
        // Resource-like routes for MaterialPembelajaran
        Route::get('pembelajaran', [MaterialPembelajaranController::class, 'index'])->name('pembelajaran.index');
        Route::post('pembelajaran', [MaterialPembelajaranController::class, 'store'])->name('pembelajaran.store');
        Route::get('pembelajaran/{pembelajaran}/edit', [MaterialPembelajaranController::class, 'edit'])->name('pembelajaran.edit'); // Added edit route
        Route::put('pembelajaran/{pembelajaran}', [MaterialPembelajaranController::class, 'update'])->name('pembelajaran.update');  // Changed to PUT
        Route::delete('pembelajaran/{pembelajaran}', [MaterialPembelajaranController::class, 'destroy'])->name('pembelajaran.destroy');

    });

});

// Other require (Fortify/Jetstream jika ada)
require __DIR__.'/settings.php';