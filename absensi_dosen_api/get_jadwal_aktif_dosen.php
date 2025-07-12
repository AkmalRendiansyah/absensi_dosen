<?php
include 'db.php';
header("Content-Type: application/json");

$dosen_id = $_GET['dosen_id'] ?? 0;

try {
    $stmt = $pdo->prepare("
        SELECT 
            j.*,
            EXISTS (
                SELECT 1 
                FROM presensi p 
                WHERE p.jadwal_id = j.id AND p.dosen_id = ?
            ) AS sudah_absen,
            (
                SELECT waktu_absen 
                FROM presensi p 
                WHERE p.jadwal_id = j.id AND p.dosen_id = ?
                ORDER BY p.id DESC
                LIMIT 1
            ) AS waktu_absen
        FROM jadwal j
        WHERE j.dosen_id = ?
          AND j.status = 'aktif'
        ORDER BY j.tanggal DESC, j.jam_mulai DESC
    ");

    $stmt->execute([$dosen_id, $dosen_id, $dosen_id]);
    $jadwal = array_map(function($j) {
        $j['sudah_absen'] = (bool)$j['sudah_absen']; // FIX di sini
        return $j;
    }, $stmt->fetchAll(PDO::FETCH_ASSOC));

    echo json_encode([
        "success" => true,
        "jadwal" => $jadwal
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
