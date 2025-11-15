<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'tanggal_lahir',
        'email',
        'password',
        'jurusan_id',
        'prodi_id',
        'kelas_id',
    ];

    protected $hidden = ['password'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function eptResults()
    {
        return $this->hasMany(EptResult::class);
    }
}
