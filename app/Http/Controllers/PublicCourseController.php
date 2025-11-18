<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Course;

class PublicCourseController extends Controller
{
    public function index()
    {
        return Inertia::render('mahasiswa/Course', [
            'title' => 'Course',
            'courses' => Course::all(),
        ]);
    }
}
