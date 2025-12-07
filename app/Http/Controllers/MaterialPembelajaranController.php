<?php

namespace App\Http\Controllers;

use App\Models\MaterialPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

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

        return Inertia::render('admin/AdminPembelajaran', [
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
            'link_pdf' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
            'link_video' => 'nullable|url|max:255',
        ]);

        if (!$request->hasFile('link_pdf') && empty($validated['link_video'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'link_pdf' => 'Setidaknya harus ada file atau tautan video.',
                'link_video' => 'Setidaknya harus ada file atau tautan video.',
            ]);
        }

        if ($request->hasFile('link_pdf')) {
            $file = $request->file('link_pdf');
            $ext = strtolower($file->getClientOriginalExtension());

            $folder = $ext === 'pdf'
                ? 'pembelajaran/pdf'
                : 'pembelajaran/ppt';

            $validated['link_pdf'] = $file->store($folder, 'public');
        }


        MaterialPembelajaran::create($validated);

        return redirect()->route('admin.pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, MaterialPembelajaran $pembelajaran)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi_singkat' => 'required|string',
            'link_pdf' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
            'link_video' => 'nullable|url|max:255',
            'remove_pdf' => 'nullable|boolean',
        ]);

        $validated = $validator->validate();

        $isRemovingPdf = $request->boolean('remove_pdf');
        $hasNewPdf = $request->hasFile('link_pdf');
        $hasVideo = !empty($validated['link_video']);
        
        // Prepare data for update
        $pembelajaran->judul = $validated['judul'];
        $pembelajaran->deskripsi_singkat = $validated['deskripsi_singkat'];
        $pembelajaran->link_video = $validated['link_video'] ?? null;

        if ($hasNewPdf) {
            if ($pembelajaran->link_pdf) {
                Storage::disk('public')->delete($pembelajaran->link_pdf);
            }

            $file = $request->file('link_pdf');
            $ext = strtolower($file->getClientOriginalExtension());

            $folder = $ext === 'pdf'
                ? 'pembelajaran/pdf'
                : 'pembelajaran/ppt';

            $pembelajaran->setAttribute('link_pdf', $file->store($folder, 'public'));
        } elseif ($isRemovingPdf) {
            if ($pembelajaran->link_pdf) {
                Storage::disk('public')->delete($pembelajaran->link_pdf);
            }
            $pembelajaran->setAttribute('link_pdf', null);
        }

        $willHavePdf = $pembelajaran->link_pdf !== null;
        if (!$willHavePdf && !$hasVideo) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'link_pdf' => 'Setidaknya harus ada file atau tautan video.',
                'link_video' => 'Setidaknya harus ada file atau tautan video.',
            ]);
        }
        
        $pembelajaran->save();
        
        return redirect()->route('admin.pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pembelajaran = MaterialPembelajaran::findOrFail($id);

        try {
            if ($pembelajaran->link_pdf) {
                Storage::disk('public')->delete($pembelajaran->link_pdf);
            }

            $deleted = $pembelajaran->delete();

            if ($deleted) {
                return redirect()->route('admin.pembelajaran.index')->with('success', 'Materi Pembelajaran berhasil dihapus.');
            }
            
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus dari database, meskipun model ditemukan.']);

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}