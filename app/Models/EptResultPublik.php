<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EptResultPublik extends Model
{
    use HasFactory;

    protected $table = 'ept_results_publik';

    protected $fillable = [
        'publik_id',
        'tahun',
        'listening',
        'structure',
        'reading',
        'total_score',
        'sertifikat_pdf',
    ];

    /**
     * Relasi ke Publik
     */
    public function publik()
    {
        return $this->belongsTo(Publik::class);
    }
}
