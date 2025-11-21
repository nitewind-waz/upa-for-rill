<?php

use App\Models\EptResultMahasiswa;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\EptResultMahasiswaController;

use App\Http\Controllers\EptScheduleController;

Route::get('/lololol', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/', function () {
    return Inertia::render('mahasiswa/Dashboard');
})->name('dashboard');

Route::get('/jadwal', [EptScheduleController::class, 'index'])->name('jadwal');

Route::get('/hasil', [EptResultMahasiswaController::class, 'index'])->name('hasil');
Route::post('/hasil/check', [EptResultMahasiswaController::class, 'checkResult'])->name('hasil.check');
Route::post('/hasil/stats', [EptResultMahasiswaController::class, 'getStats'])->name('hasil.stats');

// admin
 Route::prefix('admin')->group(function () {

    // halaman admin
    Route::get('/jadwal', [EptScheduleController::class, 'adminIndex'])
        ->name('admin.jadwal.index');
    // tambah 
    Route::post('/jadwal', [EptScheduleController::class, 'store'])
        ->name('admin.jadwal.store');
    // update 
    Route::put('/jadwal/{id}', [EptScheduleController::class, 'update'])
        ->name('admin.jadwal.update');
    // hapus 
    Route::delete('/jadwal/{id}', [EptScheduleController::class, 'destroy'])
        ->name('admin.jadwal.delete');
});


require __DIR__.'/settings.php';

