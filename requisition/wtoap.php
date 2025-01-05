<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../verifications/login.php");
    exit;
}

require "../konak/conn.php";

// Ambil ID dari URL
$idrequest = $_GET['id'] ?? null;

if (!$idrequest) {
    die("Error: Missing request ID.");
}

// Update kolom stat menjadi 'Approved'
$query = "UPDATE request SET stat = 'Approved' WHERE idrequest = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $idrequest);

if (mysqli_stmt_execute($stmt)) {
    // Redirect ke halaman sebelumnya atau halaman utama
    header("Location: index.php");
    exit;
} else {
    die("Error updating request: " . mysqli_stmt_error($stmt));
}
