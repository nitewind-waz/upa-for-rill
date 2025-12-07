<?php

namespace App\Http\Controllers;

use App\Models\EptResultPesertaMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Kelas;
use App\Models\Publik;
use App\Models\EptResultPublik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class EptResultPesertaController extends Controller
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
            'eptResults' => session('eptResults'),
            'mahasiswa' => session('mahasiswa'),
            'input' => session('input'),
        ]);
    }

    /**
     * Cek hasil EPT individu berdasarkan NIM dan password.
     */
    public function checkResult(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string', // This input can be NIM or NIK
            'password' => 'required|string',
        ]);

        $identifier = $validated['nim'];
        $password = $validated['password'];

        // 1. Try to find as Mahasiswa
        $mahasiswa = Mahasiswa::where('nim', $identifier)->first();

        if ($mahasiswa) {
            // Found as Mahasiswa, validate password
            if (!Hash::check($password, $mahasiswa->password)) {
                return back()->withErrors(['individu' => 'Password salah!'])->withInput();
            }

            // Fetch Mahasiswa EPT results
            $results = EptResultPesertaMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->orderByDesc('tahun')
                ->get(['tahun', 'semester', 'listening', 'structure', 'reading', 'total_score', 'sertifikat_pdf', 'level']);

            $user_info = [
                'nim' => $mahasiswa->nim, // nim for Mahasiswa
                'nama' => $mahasiswa->nama,
            ];

        } else {
            // 2. If not Mahasiswa, try to find as Publik
            $publik = Publik::where('nik', $identifier)->first();

            if ($publik) {
                // Found as Publik, validate password
                if (!Hash::check($password, $publik->password)) {
                    return back()->withErrors(['individu' => 'Password salah!'])->withInput();
                }

                // Fetch Publik EPT results
                $results = EptResultPublik::where('publik_id', $publik->id)
                    ->orderByDesc('tahun')
                    ->get(['tahun', 'listening', 'structure', 'reading', 'total_score', 'sertifikat_pdf', 'level']);
                
                $user_info = [
                    'nim' => $publik->nik, // nik for Publik
                    'nama' => $publik->nama_lengkap,
                ];

            } else {
                // 3. Not found in either table
                return back()->withErrors(['individu' => 'NIM/NIK tidak ditemukan!'])->withInput();
            }
        }

        // Store results in session and redirect
        return redirect()->route('hasil')->with([
            'eptResults' => $results,
            'mahasiswa' => $user_info,
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
            'tahun' => 'nullable|integer',
            'semester' => 'nullable|integer|in:1,3,5',
        ]);

        $level = $validated['level'];
        $ids = $validated['ids'] ?? [];
        
        $datasets = [];
        $detailed_statistics = [];
        $allMahasiswaIds = collect();
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

                $allMahasiswaIds = $allMahasiswaIds->merge($mahasiswaIds);

                $query = EptResultPesertaMahasiswa::whereIn('mahasiswa_id', $mahasiswaIds);

                if ($request->filled('tahun')) {
                    $query->where('tahun', $validated['tahun']);
                }
                if ($request->filled('semester')) {
                    $query->where('semester', $validated['semester']);
                }

                $stats = $query->select(
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

        $levelPieChart = null;
        if ($allMahasiswaIds->isNotEmpty()) {
            $levelDistributionQuery = DB::table('ept_results_mahasiswa')
                ->whereIn('mahasiswa_id', $allMahasiswaIds->unique());

            if ($request->filled('tahun')) {
                $levelDistributionQuery->where('tahun', $validated['tahun']);
            }
            if ($request->filled('semester')) {
                $levelDistributionQuery->where('semester', $validated['semester']);
            }

            $levelDistribution = $levelDistributionQuery->select('level', DB::raw('count(*) as total'))
                ->groupBy('level')
                ->get();

            if ($levelDistribution->isNotEmpty()) {
                $levelPieChart = [
                    'labels' => $levelDistribution->pluck('level'),
                    'datasets' => [
                        [
                            'data' => $levelDistribution->pluck('total'),
                            'backgroundColor' => ['#EF5350', '#AB47BC', '#FFA726', '#66BB6A', '#42A5F5'],
                        ]
                    ]
                ];
            }
        }

        $responseData = [
            'chart' => [
                'labels' => ['Tertinggi', 'Terendah', 'Rata-rata'],
                'datasets' => $datasets,
            ],
            'detailed_statistics' => $detailed_statistics,
            'levelPieChart' => $levelPieChart,
        ];
        
        $availableYears = EptResultPesertaMahasiswa::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $availableSemesters = [1, 3, 5];

        return Inertia::render('mahasiswa/Hasil', [
            'jurusan' => Jurusan::all(),
            'prodi' => Prodi::all(),
            'kelas' => Kelas::all(),
            'activeTab' => ucfirst($level),
            'stats' => $responseData,
            'availableYears' => $availableYears,
            'availableSemesters' => $availableSemesters,
            'input' => $request->only(['level', 'ids', 'tahun', 'semester']),
        ]);
    }
}
