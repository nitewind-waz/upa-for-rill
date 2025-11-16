<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/BeritaAdmin', [
            'beritas' => Berita::latest()->get(),
        ]);
    }

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

    public function update(Request $request, Berita $berita)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi_singkat' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $gambarFilename = $berita->gambar;
        if ($request->hasFile('gambar')) {
            // Delete old file if it exists
            if ($berita->gambar) {
                Storage::disk('public')->delete('berita/' . $berita->gambar);
            }
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

    public function destroy(Berita $berita)
    {
        if ($berita->gambar) {
            Storage::disk('public')->delete('berita/' . $berita->gambar);
        }
        $berita->delete();

        return redirect('/admin/berita')->with('success', 'Berita deleted successfully.');
    }
}