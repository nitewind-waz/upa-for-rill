<?php

use App\Models\EptResultMahasiswa;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\EptResultMahasiswaController;

use App\Http\Controllers\Admin\CourseController;

Route::get('/admin/course', [CourseController::class, 'index'])->name('admin.course.index');


Route::get('/hasil', [EptResultMahasiswaController::class, 'index'])->name('hasil');
Route::post('/hasil/check', [EptResultMahasiswaController::class, 'checkResult'])->name('hasil.check');
Route::post('/hasil/stats', [EptResultMahasiswaController::class, 'getStats'])->name('hasil.stats');



require __DIR__.'/settings.php';
