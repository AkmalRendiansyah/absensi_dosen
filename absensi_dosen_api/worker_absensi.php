<?php
include 'db.php';
include 'redis.php';

echo "Worker started. Waiting for queue items...\n";
$counter = 0;

while (true) {
    $json = $redis->lpop('absensi_dosen_queue');
    if ($json) {
        $counter++;
        $data = json_decode($json, true);
        echo "\n[" . date('Y-m-d H:i:s') . "] Processing item #$counter\n";
        echo "- Dosen ID: " . $data['dosen_id'] . "\n";
        echo "- Jadwal ID: " . $data['jadwal_id'] . "\n";
        echo "- Location: " . $data['latitude'] . ", " . $data['longitude'] . "\n";

        try {
            // Insert to DB with explicit date formatting
            $stmt = $pdo->prepare("
                INSERT INTO presensi 
                (dosen_id, jadwal_id, tanggal, waktu_absen, latitude, longitude) 
                VALUES (?, ?, DATE(?), ?, ?, ?)
            ");

            $timestamp = date('Y-m-d H:i:s');
            $date = date('Y-m-d');

            $stmt->execute([
                $data['dosen_id'],
                $data['jadwal_id'],
                $date,
                $timestamp,
                $data['latitude'],
                $data['longitude']
            ]);

            echo "✅ Data successfully saved to database\n";
            echo "  - Timestamp: $timestamp\n";
            echo "  - SQL: " . $stmt->queryString . "\n";
        } catch (PDOException $e) {
            echo "❌ Error saving to database: " . $e->getMessage() . "\n";
            echo "  - SQL State: " . $e->getCode() . "\n";
        }
    } else {
        echo "\r[" . date('Y-m-d H:i:s') . "] Waiting for new items... ";
    }
    sleep(1);
}
