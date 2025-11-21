<?php

namespace App\Http\Controllers;

use App\Models\MaterialPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'link_pdf' => 'required_without:link_video|nullable|file|mimes:pdf,ppt,pptx|max:2048', // 2MB Max
            'link_video' => 'required_without:link_pdf|nullable|url|max:255',
        ]);

        if ($request->hasFile('link_pdf')) {
            $validated['link_pdf'] = $request->file('link_pdf')->store('pembelajaran/pdf', 'public');
        }

        MaterialPembelajaran::create($validated);

        return redirect()->route('pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil ditambahkan.');
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
            'remove_pdf' => 'nullable|boolean', // New flag
        ]);

        $isRemovingPdf = $request->boolean('remove_pdf');
        $hasNewPdf = $request->hasFile('link_pdf');
        $hasVideo = !empty($validated['link_video']);
        $hasExistingPdf = (bool)$materialPembelajaran->link_pdf;
        
        // If there's no new PDF, no video link, AND we are effectively left with no PDF
        // (either by removing it or because there wasn't one to begin with).
        if (!$hasNewPdf && !$hasVideo && ($isRemovingPdf || !$hasExistingPdf)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'link_pdf' => 'Setidaknya harus ada file atau tautan video.',
                'link_video' => 'Setidaknya harus ada file atau tautan video.',
            ]);
        }
        
        if ($hasNewPdf) {
            // If a new file is uploaded, delete the old one if it exists
            if ($materialPembelajaran->link_pdf) {
                Storage::disk('public')->delete($materialPembelajaran->link_pdf);
            }
            // Store the new file
            $validated['link_pdf'] = $request->file('link_pdf')->store('pembelajaran/pdf', 'public');

        } elseif ($isRemovingPdf && $hasExistingPdf) {
            // If the user requested to remove the PDF and didn't upload a new one
            Storage::disk('public')->delete($materialPembelajaran->link_pdf);
            $validated['link_pdf'] = null; // Ensure the path is set to null in the database
        }
        
        $materialPembelajaran->update($validated);
        
        return redirect()->route('pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $materialPembelajaran = MaterialPembelajaran::findOrFail($id);

        try {
            if ($materialPembelajaran->link_pdf) {
                Storage::disk('public')->delete($materialPembelajaran->link_pdf);
            }

            $deleted = $materialPembelajaran->delete();

            if ($deleted) {
                return redirect()->route('pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil dihapus.');
            }
            
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus dari database, meskipun model ditemukan.']);

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
