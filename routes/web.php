<?php

use App\Http\Controllers\Admin\BeritaController;
use App\Models\EptResultMahasiswa;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\EptResultMahasiswaController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('berita', BeritaController::class)->parameters([
        'berita' => 'berita',
    ]);
});

Route::get('/lololol', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/jadwal', function(){
    return Inertia::render('mahasiswa/Jadwal');
})->name('jadwal');


require __DIR__.'/settings.php';
