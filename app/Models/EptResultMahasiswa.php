<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EptResultMahasiswa extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai jika tidak standar (biasanya Laravel mendeteksi otomatis: ept_result_mahasiswas)
    protected $table = 'ept_results';

    protected $fillable = [
        'mahasiswa_id',
        'tahun',          // Sesuai dengan $table->year()
        'listening',
        'structure',
        'reading',
        'total_score',
        'sertifikat_pdf',
    ];

    protected $casts = [
        'listening' => 'integer',
        'structure' => 'integer',
        'reading' => 'integer',
        'total_score' => 'integer',
        // 'tahun' => 'integer' // Opsional: Bisa di-cast ke integer jika ingin dipastikan jadi angka saat diambil
    ];

    /**
     * Relasi ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}