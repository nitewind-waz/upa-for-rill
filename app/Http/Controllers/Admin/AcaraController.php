<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Acara;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class AcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/AcaraAdmin', [
            'acaras' => Acara::latest()->get(),
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
            'tanggal_acara' => 'required|date',
        ]);

        $gambarFilename = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('acara', 'public');
            $gambarFilename = basename($path);
        }

        Acara::create([
            'judul' => $request->judul,
            'gambar' => $gambarFilename,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'tanggal_acara' => $request->tanggal_acara,
        ]);

        return redirect('/admin/acara')->with('success', 'Acara created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acara $acara)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi_singkat' => 'required|string',
            'tanggal_acara' => 'required|date',
        ]);

        $gambarFilename = $acara->gambar;
        // Handle the file upload if a new image is provided
        if ($request->hasFile('gambar')) {
            // Delete the old image file if it exists
            if ($acara->gambar) {
                Storage::disk('public')->delete('acara/' . $acara->gambar);
            }
            // Store the new image and get its filename
            $path = $request->file('gambar')->store('acara', 'public');
            $gambarFilename = basename($path);
        }

        $acara->update([
            'judul' => $request->judul,
            'gambar' => $gambarFilename,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'tanggal_acara' => $request->tanggal_acara,
        ]);

        return redirect('/admin/acara')->with('success', 'Acara updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acara $acara)
    {
        // If the news item has an image, delete it from storage
        if ($acara->gambar) {
            Storage::disk('public')->delete('acara/' . $acara->gambar);
        }
        // Delete the news item from the database
        $acara->delete();

        return redirect('/admin/acara')->with('success', 'Acara deleted successfully.');
    }
}
