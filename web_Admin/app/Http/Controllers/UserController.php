<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('users', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('create_user');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nidn' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required'
    ]);

    // Cek apakah NIDN cocok
    $dosenByNidn = Dosen::where('nidn', $validated['nidn'])->first();

    // Cek apakah Email cocok
    $dosenByEmail = Dosen::where('email', $validated['email'])->first();

    // Jika NIDN dan Email tidak cocok
    if (!$dosenByNidn && !$dosenByEmail) {
        return back()->with('error', 'NIDN dan Email tidak ditemukan sesuai dengan data dosen.');
    }

    // Jika hanya NIDN yang cocok, tapi email tidak
    if ($dosenByNidn && (!$dosenByEmail || $dosenByNidn->email !== $validated['email'])) {
        return back()->with('error', 'Email tidak sesuai dengan NIDN yang di daftarkan.');
    }

    // Jika hanya Email yang cocok, tapi NIDN tidak
    if ($dosenByEmail && (!$dosenByNidn || $dosenByEmail->nidn !== $validated['nidn'])) {
        return back()->with('error', 'NIDN tidak sesuai dengan Email yang di daftarkan.');
    }

    // Jika NIDN dan Email cocok (dosen valid)
    $user = new User();
    $user->email = $validated['email'];
    $user->password = Hash::make($validated['password']);
    $user->role = $validated['role'];
    $user->created_at = now();
    $user->save();

    return redirect('/users')->with('success', 'User berhasil ditambahkan');
}

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nidn' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);

        if (!empty($validated['nidn'])) {
            $dosen = Dosen::where('nidn', $validated['nidn'])->first();
            if (!$dosen) {
                return back()->with('error', 'NIDN tidak ditemukan pada tabel dosen');
            }
        }

        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/users')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/users')->with('success', 'User berhasil dihapus');
    }
}
