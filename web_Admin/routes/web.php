<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PresensiController;

// Route halaman login awal
Route::get('/', function () {
    return view('login');
});
// Proses login admin
Route::post('/login-admin', [AuthController::class, 'loginAdmin'])->name('login.admin');

// ==========================
// ROUTE UNTUK DATA DOSEN
// ==========================
Route::get('/dosen', [DosenController::class, 'index']);
Route::get('/dosen/create', [DosenController::class, 'create']);
Route::post('/dosen/store', [DosenController::class, 'store']);
Route::get('/dosen/{id}/edit', [DosenController::class, 'edit']);
Route::put('/dosen/{id}', [DosenController::class, 'update']);
Route::delete('/dosen/{id}', [DosenController::class, 'destroy']);

// ==========================
// ROUTE UNTUK DATA USER
// ==========================
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/create', [UserController::class, 'create']);
Route::post('/users/store', [UserController::class, 'store']);
Route::get('/users/{id}/edit', [UserController::class, 'edit']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// ==========================
// ROUTE UNTUK JADWAL
// ==========================
Route::get('/jadwal', [JadwalController::class, 'index']);
Route::get('/jadwal/create', [JadwalController::class, 'create']);
Route::post('/jadwal/store', [JadwalController::class, 'store']);
Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit']); // NEW
Route::put('/jadwal/{id}', [JadwalController::class, 'update']);   // NEW
Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy']); // NEW


Route::get('/presensi', [PresensiController::class, 'index']);
// (Opsional) Redirect jika user buka /jadwal/store dengan GET (mencegah error)
Route::get('/jadwal/store', function () {
    return redirect('/jadwal/create')->with('error', 'Akses tidak valid!');
});
