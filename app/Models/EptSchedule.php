<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EptSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'jurusan_id',
        'prodi_id',
        'kelas_id',
        'ruang_id',
        'gedung_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
    ];

    public function jurusan() {
        return $this->belongsTo(Jurusan::class);
    }

    public function prodi() {
        return $this->belongsTo(Prodi::class);
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }

    public function ruang() {
        return $this->belongsTo(Ruang::class);
    }

    public function gedung() {
        return $this->belongsTo(Gedung::class);
    }
}
