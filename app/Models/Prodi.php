<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodis';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'jurusan_id',
        'nama_prodi'
    ];

    public $timestamps = false;
    /**
     * Relasi: Satu Prodi milik satu Jurusan
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id');
    }
}