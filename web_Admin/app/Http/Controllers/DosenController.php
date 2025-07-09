<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    // Menampilkan semua dosen
    public function index()
    {
        $dosen = Dosen::all(); // Tanpa orderBy created_at karena tidak digunakan
        return view('dosen', compact('dosen'));
    }

    // Form tambah dosen
    public function create()
    {
        return view('create_dosen');
    }

    // Simpan dosen baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nidn'         => 'required|string|max:100|unique:dosen,nidn',
            'jurusan'      => 'required|string|max:255',
            'prodi'        => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:dosen,email',
        ]);

        Dosen::create($validated);

        return redirect('/dosen')->with('success', '✅ Dosen berhasil ditambahkan.');
    }

    // Form edit dosen
    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('edit_dosen', compact('dosen'));
    }

    // Proses update dosen
    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nidn'         => 'required|string|max:100|unique:dosen,nidn,' . $id,
            'jurusan'      => 'required|string|max:255',
            'prodi'        => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:dosen,email,' . $id,
        ]);

        $dosen->update($validated);

        return redirect('/dosen')->with('success', '✅ Data dosen berhasil diperbarui.');
    }

    // Hapus dosen
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect('/dosen')->with('success', '🗑️ Data dosen berhasil dihapus.');
    }
}
