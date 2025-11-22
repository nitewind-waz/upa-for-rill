<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPembelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi_singkat',
        'link_pdf',
        'link_video',
    ];
}
