<?php
include 'db.php';          // PDO MySQL connection
include 'redis.php';       // Redis connection

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$dosenId = intval($data['dosen_id'] ?? 0);
$idJadwal = intval($data['jadwal_id'] ?? 0);
$lat = floatval($data['latitude'] ?? 0);
$long = floatval($data['longitude'] ?? 0);


if (!$dosenId || !$idJadwal || !$lat || !$long) {
    echo json_encode([
        "success" => false,
        "message" => "Incomplete data",
        "debug" => [
            "dosen_id" => $dosenId,
            "jadwal_id" => $idJadwal,
            "lat" => $lat,
            "long" => $long
        ]
    ]);
    exit;
}

// Check if schedule exists
$stmt = $pdo->prepare("SELECT * FROM jadwal WHERE id = ?");
$stmt->execute([$idJadwal]);
$jadwal = $stmt->fetch();

if (!$jadwal) {
    echo json_encode(["success" => false, "message" => "Schedule not found"]);
    exit;
}

// Validate attendance time
$nowTs = time();
$batasTs = strtotime($jadwal['tanggal'] . ' ' . $jadwal['batas_absen']);
if ($nowTs > $batasTs) {
    echo json_encode(["success" => false, "message" => "Attendance time has expired"]);
    exit;
}

// Validate lecturer location using Haversine
function haversine($lat1, $lon1, $lat2, $lon2)
{
    $R = 6371000; // meters
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c;
}

$distance = haversine($jadwal['latitude'], $jadwal['longitude'], $lat, $long);
if ($distance > $jadwal['radius']) {
    echo json_encode([
        "success" => false,
        "message" => "You are outside the location range. Distance: " . round($distance, 2) . " m"
    ]);
    exit;
}

// Check for duplicate attendance
$check = $pdo->prepare("SELECT id FROM presensi WHERE dosen_id = ? AND jadwal_id = ?");
$check->execute([$dosenId, $idJadwal]);
if ($check->fetch()) {
    echo json_encode(["success" => false, "message" => "You have already taken attendance"]);
    exit;
}

try {
    $absenData = [
        'dosen_id' => $dosenId,
        'jadwal_id' => $idJadwal,
        'latitude' => $lat,
        'longitude' => $long,
        'timestamp' => date('Y-m-d H:i:s')
    ];


    $redis->rpush('absensi_dosen_queue', json_encode($absenData));

    echo json_encode([
        "success" => true,
        "message" => "Attendance successfully recorded"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
}
