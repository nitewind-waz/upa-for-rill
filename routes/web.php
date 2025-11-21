<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Admin\AcaraController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\Admin\EptResultController;
use App\Http\Controllers\PublicCourseController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\EptScheduleController;

Route::get('/', function () {
    return Inertia::render('mahasiswa/Dashboard');
})->name('dashboard');

// Course CRUD ADMIN
Route::get('/admin/course', [CourseController::class, 'index'])->name('admin.course.index');
Route::get('/admin/course/create', [CourseController::class, 'create'])->name('admin.course.create');
Route::post('admin/course/create', [CourseController::class, 'store'])->name('admin.course.store');
Route::get('/admin/course/{course}/edit', [CourseController::class, 'edit'])->name('admin.course.edit');
Route::put('/admin/course/{course}', [CourseController::class, 'update'])->name('admin.course.store');
Route::delete('/admin/course/{course}', [CourseController::class, 'destroy'])->name('admin.course.delete');

// EPT Result

Route::get('/ept', [EptResultController::class, 'index'])->name('ept.index');
Route::post('/ept', [EptResultController::class, 'store'])->name('ept.store');
Route::post('/ept/import', [EptResultController::class, 'import'])->name('ept.import');


Route::get('/hasil', [EptResultMahasiswaController::class, 'index'])->name('hasil');
Route::post('/hasil/check', [EptResultMahasiswaController::class, 'checkResult'])->name('hasil.check');
Route::post('/hasil/stats', [EptResultMahasiswaController::class, 'getStats'])->name('hasil.stats');

Route::get('course', [PublicCourseController::class, 'index'])->name('course.index');
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


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('berita', BeritaController::class)->parameters([
        'berita' => 'berita',
    ]);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('acara', AcaraController::class)->parameters([
        'acara' => 'acara',
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


Route::get('/hasil', [EptResultPesertaController::class, 'index'])->name('hasil');
Route::post('/hasil/check', [EptResultPesertaController::class, 'checkResult'])->name('hasil.check');
Route::get('/hasil/stats', [EptResultPesertaController::class, 'getStats'])->name('hasil.stats');



require __DIR__.'/settings.php';

