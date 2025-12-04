<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTestPublik extends Model
{
    use HasFactory;

    protected $table = 'jadwals_test_publik';

    protected $fillable = [
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'ruang_id',
        'gedung_id',
        'publik_id', // Add publik_id here
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    // Define the relationship to Publik
    public function publik()
    {
        return $this->belongsTo(Publik::class);
    }
}
