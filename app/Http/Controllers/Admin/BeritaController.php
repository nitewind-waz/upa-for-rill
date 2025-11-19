<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/BeritaAdmin', [
            'beritas' => Berita::latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi_singkat' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $gambarFilename = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('berita', 'public');
            $gambarFilename = basename($path);
        }

        Berita::create([
            'judul' => $request->judul,
            'gambar' => $gambarFilename,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'tanggal' => $request->tanggal,
        ]);

        return redirect('/admin/berita')->with('success', 'Berita created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Berita $berita)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi_singkat' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $gambarFilename = $berita->gambar;
        // Handle the file upload if a new image is provided
        if ($request->hasFile('gambar')) {
            // Delete the old image file if it exists
            if ($berita->gambar) {
                Storage::disk('public')->delete('berita/' . $berita->gambar);
            }
            // Store the new image and get its filename
            $path = $request->file('gambar')->store('berita', 'public');
            $gambarFilename = basename($path);
        }

        $berita->update([
            'judul' => $request->judul,
            'gambar' => $gambarFilename,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'tanggal' => $request->tanggal,
        ]);

        return redirect('/admin/berita')->with('success', 'Berita updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Berita $berita)
    {
        // If the news item has an image, delete it from storage
        if ($berita->gambar) {
            Storage::disk('public')->delete('berita/' . $berita->gambar);
        }
        // Delete the news item from the database
        $berita->delete();

        return redirect('/admin/berita')->with('success', 'Berita deleted successfully.');
    }
}