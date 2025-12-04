<?php

use App\Models\EptResultMahasiswa;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\EptResultMahasiswaController;
use App\Http\Controllers\PublicCourseController;

use App\Http\Controllers\EptScheduleController;
use App\Http\Controllers\Admin\JadwalTestPublikController;

Route::get('/', function () {
    return Inertia::render('mahasiswa/Dashboard');
})->name('dashboard');

Route::get('/jadwal', [EptScheduleController::class, 'index'])->name('jadwal');

// Course CRUD ADMIN
Route::get('/admin/course', [CourseController::class, 'index'])->name('admin.course.index');
Route::get('admin/course/create', [CourseController::class, 'create'])->name('admin.course.create');
Route::post('admin/course/create', [CourseController::class, 'store'])->name('admin.course.store');
Route::get('/admin/course/{course}/edit', [CourseController::class, 'edit'])->name('admin.course.edit');
Route::put('/admin/course/{course}', [CourseController::class, 'update'])->name('admin.course.store');
Route::delete('/admin/course/{course}', [CourseController::class, 'destroy'])->name('admin.course.delete');


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
    
    Route::post('/jadwal-publik', [JadwalTestPublikController::class, 'store'])->name('admin.jadwal-publik.store');
    Route::delete('/jadwal-publik/{id}', [JadwalTestPublikController::class, 'destroy'])->name('admin.jadwal-publik.destroy');
});


require __DIR__.'/settings.php';

