<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

use App\Http\Controllers\Admin\CourseController;

Route::get('/admin/course', [CourseController::class, 'index'])->name('admin.course.index');

require __DIR__.'/settings.php';
