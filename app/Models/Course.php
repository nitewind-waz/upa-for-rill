<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'judul',
        'deskripsi',
        'ketentuan',
        'sistem_pembelajaran',
        'jadwal',
        'jenis',
    ];

    /**
     * Tipe data untuk kolom 'jenis' (enum) dapat di-cast ke string,
     * tetapi umumnya cukup disimpan sebagai string.
     */
    protected $casts = [
        // Contoh jika Anda ingin 'jenis' diubah menjadi enum object (di PHP >= 8.1)
        // 'jenis' => CourseType::class,
    ];

    // Jika di masa depan course ini memiliki relasi (misalnya ke Mahasiswa yang mendaftar),
    // fungsi relasi akan ditambahkan di sini.
}
