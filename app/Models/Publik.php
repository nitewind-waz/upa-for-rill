<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publik extends Model
{
    use HasFactory;

    protected $table = 'publiks'; // Ensure the model uses the correct table name

    protected $fillable = [
        'nik',
        'nama_lengkap',
    ];

    /**
     * Get the public test schedules for the publik.
     */
    public function jadwalTestPubliks()
    {
        return $this->hasMany(JadwalTestPublik::class);
    }
}