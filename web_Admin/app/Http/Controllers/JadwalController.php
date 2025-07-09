<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Dosen;

class JadwalController extends Controller
{
    // Menampilkan daftar jadwal
    public function index()
    {
        try {
            $jadwal = Jadwal::with('dosen')->orderBy('id', 'desc')->get();
            $error = null;
        } catch (\Exception $e) {
            $jadwal = [];
            $error = 'Terjadi kesalahan: ' . $e->getMessage();
        }

        return view('jadwal', compact('jadwal', 'error'));
    }

    // Menampilkan form tambah jadwal
    public function create()
    {
        $dosen = Dosen::all();
        return view('create_jadwal', compact('dosen'));
    }

    // Menyimpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'jam_mulai'    => 'required',
            'batas_absen'  => 'required',
            'latitude'     => 'required',
            'longitude'    => 'required',
            'radius'       => 'required|numeric',
            'dosen_id'     => 'required|exists:dosen,id'
        ]);

        try {
            Jadwal::create($request->all());
            return redirect('/jadwal')->with('success', '✅ Jadwal berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $jadwal = Jadwal::find($id);
        $dosen = Dosen::all();

        if (!$jadwal) {
            return redirect('/jadwal')->with('error', 'Jadwal tidak ditemukan.');
        }

        return view('edit_jadwal', compact('jadwal', 'dosen'));
    }

    // Menyimpan hasil edit jadwal
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'jam_mulai'    => 'required',
            'batas_absen'  => 'required',
            'latitude'     => 'required',
            'longitude'    => 'required',
            'radius'       => 'required|numeric',
            'dosen_id'     => 'required|exists:dosen,id'
        ]);

        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan');
        }

        try {
            $jadwal->update($request->all());
            return redirect('/jadwal')->with('success', '✅ Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menghapus jadwal
    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::find($id);

            if (!$jadwal) {
                return redirect('/jadwal')->with('error', 'Jadwal tidak ditemukan');
            }

            $jadwal->delete();
            return redirect('/jadwal')->with('success', '🗑️ Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/jadwal')->with('error', '❌ Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}
