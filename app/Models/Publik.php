<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Publik extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'publiks';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
