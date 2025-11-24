<?php

use App\Models\EptResultMahasiswa;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\EptResultController;
use App\Http\Controllers\MaterialPembelajaranController;

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


Route::get('/hasil', [EptResultController::class, 'index'])->name('hasil');
Route::post('/hasil/check', [EptResultController::class, 'checkResult'])->name('hasil.check');
Route::get('/hasil/stats', [EptResultController::class, 'getStats'])->name('hasil.stats');

Route::prefix('admin')->group(function () {
    // Route::resource('pembelajaran', MaterialPembelajaranController::class);

    // Add explicit routes
    Route::get('pembelajaran', [MaterialPembelajaranController::class, 'index'])->name('pembelajaran.index');
    Route::post('pembelajaran', [MaterialPembelajaranController::class, 'store'])->name('pembelajaran.store');

    // THE CRITICAL CHANGE: Define the update route as POST
    Route::post('pembelajaran/{pembelajaran}', [MaterialPembelajaranController::class, 'update'])->name('pembelajaran.update');

    Route::delete('pembelajaran/{pembelajaran}', [MaterialPembelajaranController::class, 'destroy'])->name('pembelajaran.destroy');
});


require __DIR__.'/settings.php';
