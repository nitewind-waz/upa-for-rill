<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EptResultPesertaMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'ept_results_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'tahun',
        'listening',
        'structure',
        'reading',
        'total_score',
        'sertifikat_pdf',
    ];

    /**
     * Relasi ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
