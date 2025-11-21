<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EptSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'prodi_id',
        'kelas_id',
        'tempat',
        'gedung',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
    ];

    public function prodi() {
        return $this->belongsTo(Prodi::class);
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }
}
