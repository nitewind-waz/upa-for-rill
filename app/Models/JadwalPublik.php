<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPublik extends Model
{
    use HasFactory;

    protected $table = 'jadwals_test_publik';

    protected $fillable = [
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'ruang_id',
        'gedung_id',
        'publik_id',
    ];

    public function publik(): BelongsTo
    {
        return $this->belongsTo(Publik::class, 'publik_id');
    }

    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }
}
