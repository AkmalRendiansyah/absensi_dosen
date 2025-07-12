<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'dosen_id',
        'jadwal_id',
        'tanggal',
        'waktu_absen',
        'latitude',
        'longitude',
    ];
    public function dosen()
{
    return $this->belongsTo(Dosen::class, 'dosen_id', 'id');
}

    public $timestamps = false;
}
