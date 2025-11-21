<?php

namespace App\Http\Controllers;

use App\Models\MaterialPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MaterialPembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materialPembelajarans = MaterialPembelajaran::all()->map(function ($material) {
            if ($material->link_pdf) {
                $material->link_pdf_url = Storage::url($material->link_pdf);
            }
            return $material;
        });

        return Inertia::render('AdminPembelajaran', [
            'materialPembelajarans' => $materialPembelajarans,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi_singkat' => 'required|string',
            'link_pdf' => 'nullable|file|mimes:pdf,ppt,pptx|max:2048', // 2MB Max
            'link_video' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('link_pdf')) {
            $validated['link_pdf'] = $request->file('link_pdf')->store('pembelajaran/pdf', 'public');
        }

        MaterialPembelajaran::create($validated);

        return redirect()->route('admin.pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil ditambahkan.');
    }

    // Note: The update method uses a POST request from Inertia, so we can't use the typical route model binding for file uploads.
    // We expect the file to be sent along with other data in a multipart/form-data request.
    public function update(Request $request, MaterialPembelajaran $materialPembelajaran)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi_singkat' => 'required|string',
            'link_pdf' => 'nullable|file|mimes:pdf,ppt,pptx|max:2048',
            'link_video' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('link_pdf')) {
            // Delete old file if it exists
            if ($materialPembelajaran->link_pdf) {
                Storage::disk('public')->delete($materialPembelajaran->link_pdf);
            }
            // Store the new file
            $validated['link_pdf'] = $request->file('link_pdf')->store('pembelajaran/pdf', 'public');
        }

        $materialPembelajaran->update($validated);
        
        // It's a good practice to use a separate endpoint for file uploads,
        // but for simplicity with Inertia's form helper, we handle it here.
        // We need to use a POST request with `_method: 'PUT'` in the form data for this to work correctly with file uploads.
        // The frontend will be adjusted to handle this.
        return redirect()->route('admin.pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialPembelajaran $materialPembelajaran)
    {
        // Delete the associated file if it exists
        if ($materialPembelajaran->link_pdf) {
            Storage::disk('public')->delete($materialPembelajaran->link_pdf);
        }

        $materialPembelajaran->delete();

        return redirect()->route('admin.pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil dihapus.');
    }
}
