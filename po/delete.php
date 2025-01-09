<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location: ../verifications/login.php");
    exit();
}

require "../konak/conn.php";

// Validasi dan ambil ID PO dari parameter URL
$idpo = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idpo === 0) {
    echo "<script>alert('Invalid PO ID.'); window.location='index.php';</script>";
    exit();
}

// Query untuk mendapatkan idrequest dari tabel po
$getRequestIdQuery = "SELECT idrequest FROM po WHERE idpo = ?";
$stmt = $conn->prepare($getRequestIdQuery);
$stmt->bind_param("i", $idpo);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Pastikan data ditemukan
if (!$row) {
    echo "<script>alert('PO not found.'); window.location='index.php';</script>";
    exit();
}

$idrequest = $row['idrequest'];

// Mulai transaksi
$conn->begin_transaction();

try {
    // Query untuk melakukan soft delete pada tabel po
    $softDeleteQuery = "UPDATE po SET is_deleted = 1 WHERE idpo = ?";
    $stmt = $conn->prepare($softDeleteQuery);
    $stmt->bind_param("i", $idpo);
    $stmt->execute();

    // Query untuk memperbarui status di tabel request
    $updateStatusQuery = "UPDATE request SET stat = 'Ordering' WHERE idrequest = ?";
    $stmt = $conn->prepare($updateStatusQuery);
    $stmt->bind_param("i", $idrequest);
    $stmt->execute();

    // Commit transaksi jika semua berhasil
    $conn->commit();

    // Redirect ke index.php dengan pesan sukses
    echo "<script>alert('PO successfully deleted and request status updated.'); window.location='index.php';</script>";
} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    $conn->rollback();

    // Tampilkan pesan kesalahan
    echo "<script>alert('An error occurred while deleting PO. Please try again.'); window.location='index.php';</script>";
}

// Tutup koneksi
$conn->close();
