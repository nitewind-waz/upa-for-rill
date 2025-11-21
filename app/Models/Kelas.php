<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    // Definisikan nama tabel secara eksplisit
    // (Karena plural dari 'kelas' tidak standar dalam bahasa Inggris)
    protected $table = 'kelas';

    // Penting untuk Import Excel (firstOrCreate)
    protected $fillable = [
        'nama_kelas',
        'prodi_id'
    ];

    /**
     * Relasi: Satu Kelas milik satu Prodi
     * (Inverse dari one-to-many)
     */
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'id');
    }
    
    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'kelas_id', 'id');
    }
}