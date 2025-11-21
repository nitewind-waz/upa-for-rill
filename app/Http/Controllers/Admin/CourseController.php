<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Course/Index', [
            'title' => 'Manajemen Kursus',
            'courses' => Course::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:50',
            'images'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ganti jadi images
            'deskripsi' => 'required|string',
            'ketentuan' => 'required|string',
            'jadwal'    => 'required|string',
            'jenis'     => 'required|in:Gratis,Berbayar',
            'sistem_pembelajaran' => 'required|string',
        ]);

        if ($request->hasFile('images')) {
            $path = $request->file('images')->store('courses', 'public');
            $validated['images'] = $path;
            // dd($path); // Cek apakah path terbentuk
        } else {
            // dd('File tidak terdeteksi'); // Cek jika masuk sini
        }

        Course::create($validated);

        return redirect()->back()->with('success', 'Kursus berhasil ditambahkan!');
    }

    public function update(Request $request, Course $course)
    {
        $rules = [
            'judul'     => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'ketentuan' => 'required|string',
            'jadwal'    => 'required|string',
            'jenis'     => 'required|in:Gratis,Berbayar',
            'sistem_pembelajaran' => 'required|string',
        ];

        if ($request->hasFile('images')) {
            $rules['images'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('images')) {
            // Hapus gambar lama jika ada
            if ($course->images) {
                Storage::disk('public')->delete($course->images);
            }
            // Simpan gambar baru
            $validated['images'] = $request->file('images')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->back()->with('success', 'Kursus berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        if ($course->images) {
            Storage::disk('public')->delete($course->images);
        }

        $course->delete();

        return redirect()->back()->with('success', 'Kursus berhasil dihapus!');
    }
}