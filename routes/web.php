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

use App\Http\Controllers\Admin\CourseController;

/*
    PERINGATAN ROUTE INI BELUM DISESUAIKAN DENGAN MIDDLEWARE
    KAREA BELUM ADA AUTH UNTUK ADMIN
*/

Route::get('/', function () {
    return Inertia::render('mahasiswa/Dashboard');
})->name('dashboard');

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

Route::get('/course', [PublicCourseController::class, 'index'])->name('course');





require __DIR__.'/settings.php';
