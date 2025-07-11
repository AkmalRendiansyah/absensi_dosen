<?php

header("Content-Type: application/json");
require "db.php";

$jadwal_id = $_GET['jadwal_id'] ?? null;

if (!$jadwal_id) {
    echo json_encode([
        "success" => false,
        "message" => "Parameter jadwal_id dibutuhkan"
    ]);
    exit;
}

try {
    $sql = "
        SELECT 
            id,
            tanggal,
            jam_mulai,
            batas_absen,
            latitude,
            longitude,
            dosen_id,
            created_at,
            status,
            radius
        FROM jadwal
        WHERE id = ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jadwal_id]);
    $jadwal = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($jadwal) {
        echo json_encode([
            "success" => true,
            "jadwal" => $jadwal
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Jadwal tidak ditemukan"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>