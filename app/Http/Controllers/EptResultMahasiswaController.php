<?php

namespace App\Http\Controllers;

use App\Models\EptResultMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class EptResultMahasiswaController extends Controller
{
    /**
     * Menampilkan halaman hasil EPT (Jurusan, Prodi, Kelas, Individu)
     */
    public function index(Request $request)
    {
        $activeTab = $request->input('tab', 'Individu');

        return Inertia::render('mahasiswa/Hasil', [
            'jurusan' => Jurusan::all(),
            'prodi' => Prodi::all(),
            'kelas' => Kelas::all(),
            'activeTab' => $activeTab,
        ]);
    }

    /**
     * Cek hasil EPT individu berdasarkan NIM dan password.
     */
    public function checkResult(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari mahasiswa berdasarkan NIM
        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();

        // Validasi NIM
        if (!$mahasiswa){
            return back()->withErrors(['individu' => 'NIM tidak ditemukan!'])->withInput();
        }

        // Validasi Password
        if (! Hash::check($validated['password'], $mahasiswa->password)) {
            return back()->withErrors(['individu' => 'Password salah!'])->withInput();
        }

        // Ambil hasil EPT mahasiswa
        $results = EptResultMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('tahun')
            ->get(['tahun', 'listening', 'structure', 'reading', 'total_score', 'sertifikat_pdf']);

        return Inertia::render('mahasiswa/Hasil', [
            'jurusan' => Jurusan::all(),
            'prodi' => Prodi::all(),
            'kelas' => Kelas::all(),
            'activeTab' => 'Individu',
            'eptResults' => $results,
            'mahasiswa' => [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
            ],
            'input' => $request->only(['nim']),
        ]);
    }

    /**
     * Ambil data untuk grafik EPT.
     */
    public function getStats(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|in:jurusan,prodi,kelas',
            'ids'   => 'sometimes|array',
            'ids.*' => 'integer',
        ]);

        $level = $validated['level'];
        $ids = $validated['ids'] ?? [];
        
        $datasets = [];
        $detailed_statistics = [];
        $backgroundColors = ['#42A5F5', '#FFA726', '#66BB6A', '#EF5350', '#AB47BC', '#26A69A'];
        $colorIndex = 0;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                $mahasiswaIds = collect();
                $name = '';

                if ($level === 'jurusan') {
                    $model = Jurusan::find($id);
                    if(!$model) continue;
                    $name = $model->nama_jurusan;
                    $mahasiswaIds = Mahasiswa::where('jurusan_id', $id)->pluck('id');
                } elseif ($level === 'prodi') {
                    $model = Prodi::find($id);
                    if(!$model) continue;
                    $name = $model->nama_prodi;
                    $mahasiswaIds = Mahasiswa::where('prodi_id', $id)->pluck('id');
                } elseif ($level === 'kelas') {
                    $model = Kelas::find($id);
                    if(!$model) continue;
                    $name = $model->nama_kelas;
                    $mahasiswaIds = Mahasiswa::where('kelas_id', $id)->pluck('id');
                }

                if ($mahasiswaIds->isEmpty()) continue;

                $stats = EptResultMahasiswa::whereIn('mahasiswa_id', $mahasiswaIds)
                    ->select(
                        DB::raw('MAX(listening) as max_listening'),
                        DB::raw('MIN(listening) as min_listening'),
                        DB::raw('AVG(listening) as avg_listening'),
                        DB::raw('MAX(structure) as max_structure'),
                        DB::raw('MIN(structure) as min_structure'),
                        DB::raw('AVG(structure) as avg_structure'),
                        DB::raw('MAX(reading) as max_reading'),
                        DB::raw('MIN(reading) as min_reading'),
                        DB::raw('AVG(reading) as avg_reading'),
                        DB::raw('MAX(total_score) as max_total_score'),
                        DB::raw('MIN(total_score) as min_total_score'),
                        DB::raw('AVG(total_score) as avg_total_score')
                    )
                    ->first();
                
                if(!$stats->max_listening) continue;

                $datasets[] = [
                    'label' => $name,
                    'backgroundColor' => $backgroundColors[$colorIndex % count($backgroundColors)],
                    'data' => [
                        $stats->max_total_score,
                        $stats->min_total_score,
                        round($stats->avg_total_score, 2)
                    ]
                ];

                $detailed_statistics[] = [
                    'name' => $name,
                    'listening' => ['max' => $stats->max_listening, 'min' => $stats->min_listening, 'avg' => round($stats->avg_listening, 2)],
                    'structure' => ['max' => $stats->max_structure, 'min' => $stats->min_structure, 'avg' => round($stats->avg_structure, 2)],
                    'reading' => ['max' => $stats->max_reading, 'min' => $stats->min_reading, 'avg' => round($stats->avg_reading, 2)],
                ];
                
                $colorIndex++;
            }
        }

        $responseData = [
            'chart' => [
                'labels' => ['Tertinggi', 'Terendah', 'Rata-rata'],
                'datasets' => $datasets,
            ],
            'detailed_statistics' => $detailed_statistics
        ];

        return Inertia::render('mahasiswa/Hasil', [
            'jurusan' => Jurusan::all(),
            'prodi' => Prodi::all(),
            'kelas' => Kelas::all(),
            'activeTab' => ucfirst($level),
            'stats' => $responseData,
            'input' => $request->only(['level', 'ids']),
        ]);
    }
}
