<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$data = json_decode(file_get_contents("php://input"));
$identifier = trim($data->email ?? '');  // Bisa email atau nidn
$password = trim($data->password ?? '');

if (!$identifier || !$password) {
    echo json_encode(["success" => false, "message" => "Email/NIDN and password are required."]);
    exit;
}

try {
    // Check if input is email
    $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

    if ($isEmail) {
        $stmt = $pdo->prepare("
            SELECT u.id AS user_id, u.password, u.role, 
                   d.id AS dosen_id, d.nama_lengkap, d.nidn, d.jurusan, d.prodi
            FROM users u 
            JOIN dosen d ON u.id = d.user_id 
            WHERE u.email = ?
        ");
        $stmt->execute([$identifier]);
    } else {
        $stmt = $pdo->prepare("
            SELECT u.id AS user_id, u.password, u.role, 
                   d.id AS dosen_id, d.nama_lengkap, d.nidn, d.jurusan, d.prodi
            FROM users u 
            JOIN dosen d ON u.id = d.user_id 
            WHERE d.nidn = ?
        ");
        $stmt->execute([$identifier]);
    }

    if ($stmt->rowCount() === 0) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $user['password'])) {
        echo json_encode(["success" => false, "message" => "Incorrect password."]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "message" => "Login successful.",
        "dosen_id" => $user['dosen_id'],
        "role" => $user['role'],
        "nama_lengkap" => $user['nama_lengkap'],
        "nidn" => $user['nidn'],
        "jurusan" => $user['jurusan'],
        "prodi" => $user['prodi']
    ]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Server error: " . $e->getMessage()]);
}