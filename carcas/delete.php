<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Opsional: aktifkan error mysqli saat dev
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!function_exists('e')) {
    function e($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}

// Ambil id dari GET
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    // kalau param nggak valid, balik saja ke index
    header("Location: index.php");
    exit;
}

$idcarcase = (int)$_GET['id'];

// (opsional) bisa cek user login
$idusers = (int)($_SESSION['idusers'] ?? 0);
if ($idusers <= 0) {
    die("Session user tidak valid.");
}

try {
    // Soft delete → hanya set is_deleted = 1
    $stmt = $conn->prepare("
        UPDATE carcase
        SET is_deleted = 1
        WHERE idcarcase = ? AND is_deleted = 0
    ");
    if (!$stmt) {
        throw new Exception("Gagal menyiapkan statement: " . $conn->error);
    }

    $stmt->bind_param("i", $idcarcase);
    $stmt->execute();
    $stmt->close();

    // Selesai, kembali ke index
    header("Location: index.php");
    exit;
} catch (Exception $ex) {
    // Kalau ada error, tampilkan sederhana
    die("Terjadi kesalahan saat menghapus data: " . e($ex->getMessage()));
}
