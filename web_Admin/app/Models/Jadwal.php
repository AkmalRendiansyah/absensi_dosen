<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'tanggal',
        'jam_mulai',
        'batas_absen',
        'latitude',
        'longitude',
        'radius',
        'dosen_id'
    ];

    public $timestamps = false; // jika tabel tidak memiliki created_at / updated_at

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
