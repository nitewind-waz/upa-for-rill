<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusans';
    
    // Kolom yang boleh diisi massal
    protected $fillable = [
        'nama_jurusan'
    ];
    
    public $timestamps = false;
    /**
     * Relasi: Satu Jurusan memiliki banyak Prodi
     */
    public function prodi(): HasMany
    {
        return $this->hasMany(Prodi::class, 'jurusan_id', 'id');
    }
}