<?php
require_once 'db.php'; 

$queries = [

"CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('dosen', 'mahasiswa') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS dosen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    nama_lengkap VARCHAR(255),
    nidn VARCHAR(50),
    jurusan VARCHAR(255),
    prodi VARCHAR(255),
    email VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)",

"CREATE TABLE IF NOT EXISTS jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    batas_absen TIME NOT NULL,
    latitude DOUBLE,
    longitude DOUBLE,
    dosen_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('aktif', 'tidak aktif') DEFAULT 'tidak aktif',
    radius INT,
    FOREIGN KEY (dosen_id) REFERENCES dosen(id) ON DELETE SET NULL
)",

"CREATE TABLE IF NOT EXISTS presensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dosen_id INT,
    jadwal_id INT,
    tanggal DATE,
    waktu_absen DATETIME,
    latitude DOUBLE,
    longitude DOUBLE,
    FOREIGN KEY (dosen_id) REFERENCES dosen(id) ON DELETE SET NULL,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal(id) ON DELETE SET NULL
)"
];

// Eksekusi migrasi
foreach ($queries as $query) {
    try {
        $pdo->exec($query);
        echo "✅ Tabel berhasil dibuat.\n";
    } catch (PDOException $e) {
        echo "❌ Gagal membuat tabel: " . $e->getMessage() . "\n";
    }
}
