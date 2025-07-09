<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';
    public $timestamps = false;
     protected $fillable = [
        'nama_lengkap',
        'nidn',
        'jurusan',
        'prodi',
        'email',
    ];
}

