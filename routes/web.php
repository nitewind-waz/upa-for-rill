<?php

use App\Models\EptResultMahasiswa;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\EptResultMahasiswaController;

Route::get('/lololol', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/', function () {
    return Inertia::render('mahasiswa/Dashboard');
})->name('dashboard');

Route::get('/jadwal', function(){
    return Inertia::render('mahasiswa/Jadwal');
})->name('jadwal');


Route::get('/hasil', [EptResultMahasiswaController::class, 'index'])->name('hasil');
Route::post('/hasil/check', [EptResultMahasiswaController::class, 'checkResult'])->name('hasil.check');
Route::get('/hasil/jurusan', [JurusanController::class, 'index'])->name('hasil.jurusan');
Route::get('/hasil/prodi', [ProdiController::class, 'index'])->name('hasil.prodi');
Route::get('/hasil/kelas', [KelasController::class, 'index'])->name('hasil.kelas');


require __DIR__.'/settings.php';
