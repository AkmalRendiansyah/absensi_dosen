<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;

class AuthController extends Controller
{
    public function loginAdmin(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->with('error', 'Admin tidak ditemukan.');
    }

    if ($user->role !== 'admin') {
        return back()->with('error', 'Akses ditolak. Bukan akun admin.');
    }


    if (!Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Password salah.');
    }

    // ✅ Redirect ke halaman /dosen (bukan JSON)
    return redirect('/dosen')->with('success', 'Login admin berhasil.');
}

}
