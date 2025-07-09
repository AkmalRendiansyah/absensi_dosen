<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;

class PresensiController extends Controller
{
    // App\Http\Controllers\PresensiController.php
public function index()
{
    try {
        $presensi = Presensi::with('dosen')->orderByDesc('tanggal')->get();
        return view('presensi', compact('presensi'))->with('error', null);
    } catch (\Exception $e) {
        return view('presensi', [
            'presensi' => [],
            'error' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

}
