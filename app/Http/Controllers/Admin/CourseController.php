<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Course/Index', [
            'title' => 'Course',
            'courses' => Course::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/Course/Create', [
            'title' => 'Tambah Kursus',
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'  => 'required|string|max:50',
            'deskripsi' => 'required|string|min:0',
            'ketentuan' => 'required|string|min:0',
            'jadwal' => 'required|string|min:0',
            'jenis' => 'required|string|min:0',
        ]);

        Course::create($validated);

        return redirect()
        ->route('admin.course.index')
        ->with('success', 'Kursus berhasil ditambahkan!');
    }

    public function edit(Course $course)
    {
        return Inertia::render('admin/Course/Edit', [
            'title' => 'Edit Kursus',
            'course' => $course,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'judul'  => 'required|string|max:50',
            'deskripsi' => 'required|string|min:0',
            'ketentuan' => 'required|string|min:0',
            'jadwal' => 'required|string|min:0',
            'jenis' => 'required|string|min:0',
        ]);

        $course->update($validated);

        return redirect()
        ->route('admin.course.index')
        ->with('success', 'Kursus berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()
        ->route('admin.course.index')
        ->with('success', 'Kursus berhasil dihapus!');
    }

}
