<?php
include 'db.php';
header("Content-Type: application/json");

$dosen_id = isset($_GET['dosen_id']) ? intval($_GET['dosen_id']) : 0;

if ($dosen_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid dosen_id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            p.id AS presensi_id,
            p.tanggal,
            p.waktu_absen,
            p.latitude AS absen_latitude,
            p.longitude AS absen_longitude,
            j.tanggal AS jadwal_tanggal,
            j.jam_mulai,
            j.batas_absen,
            j.latitude AS jadwal_latitude,
            j.longitude AS jadwal_longitude,
            j.radius
        FROM presensi p
        JOIN jadwal j ON p.jadwal_id = j.id
        WHERE p.dosen_id = ?
        ORDER BY p.tanggal DESC, p.waktu_absen DESC
    ");
    $stmt->execute([$dosen_id]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "history" => $history
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
