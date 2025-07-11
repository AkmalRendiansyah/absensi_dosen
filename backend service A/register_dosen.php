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

$email = trim($data->email ?? '');
$password = trim($data->password ?? '');
$password2 = trim($data->password2 ?? '');
$nama = trim($data->nama ?? '');
$nidn = trim($data->nidn ?? '');
$jurusan = trim($data->jurusan ?? '');
$prodi = trim($data->prodi ?? '');

// Validasi
if (!$email || !$password || !$password2 || !$nama || !$nidn || !$jurusan || !$prodi) {
    echo json_encode(["success" => false, "message" => "Semua field wajib diisi."]);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Email tidak valid."]);
    exit;
}
if ($password !== $password2) {
    echo json_encode(["success" => false, "message" => "Password dan konfirmasi tidak cocok."]);
    exit;
}
if (preg_match('/[0-9]/', $nama)) {
    echo json_encode(["success" => false, "message" => "Nama tidak boleh mengandung angka."]);
    exit;
}
if (strlen($nidn) > 10) {
    echo json_encode(["success" => false, "message" => "nidn maksimal 10 karakter."]);
    exit;
}

try {
    // Check database connection first
    if (!$pdo) {
        throw new Exception("Database connection failed");
    }

    // 1. Check if NIDN exists
    $stmt = $pdo->prepare("SELECT id, email FROM dosen WHERE nidn = ?");
    if (!$stmt->execute([$nidn])) {
        throw new Exception("Failed to check NIDN");
    }
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dosen) {
        echo json_encode([
            "success" => false,
            "message" => "NIDN not registered. Please contact admin.",
            "error_type" => "nidn_not_found"
        ]);
        exit;
    }

    // 2. Check if NIDN already registered
    if (!empty($dosen['email'])) {
        echo json_encode([
            "success" => false,
            "message" => "NIDN has already been registered.",
            "error_type" => "nidn_registered"
        ]);
        exit;
    }

    // 3. Check if email is already used
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt->execute([$email])) {
        throw new Exception("Failed to check email");
    }
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Email already registered.",
            "error_type" => "email_exists"
        ]);
        exit;
    }

    // Start transaction
    $pdo->beginTransaction();

    try {
        // 4. Save to users table
        $token = bin2hex(random_bytes(16));
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'dosen')");
        if (!$stmt->execute([$email, $hashed_password])) {
            throw new Exception("Failed to create user account");
        }
        $user_id = $pdo->lastInsertId();

        // 5. Update dosen data
        $stmt = $pdo->prepare("UPDATE dosen SET user_id=?, nama_lengkap=?, jurusan=?, prodi=?, email=? WHERE id=?");
        if (!$stmt->execute([$user_id, $nama, $jurusan, $prodi, $email, $dosen['id']])) {
            throw new Exception("Failed to update lecturer data");
        }

        // Commit transaction
        $pdo->commit();

        echo json_encode([
            "success" => true,
            "message" => "Registration successful. Please login with your credentials.",
            "redirect" => "login"
        ]);

    } catch (Exception $e) {
        // Rollback on error
        $pdo->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection error: " . $e->getMessage(),
        "error_type" => "database_error"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Registration failed: " . $e->getMessage(),
        "error_type" => "registration_error"
    ]);
}
?>