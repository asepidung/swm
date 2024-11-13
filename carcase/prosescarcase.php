<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Mendapatkan data dari form
$killdate = $_POST['killdate'];
$idsupplier = $_POST['idsupplier'];
$note = $_POST['note'];

// Mengecek apakah semua field wajib diisi
if (empty($killdate) || empty($idsupplier)) {
   echo "<script>alert('Mohon lengkapi semua field yang wajib diisi.'); window.history.back();</script>";
   exit;
}

// Menyimpan data ke tabel carcase
$query = "INSERT INTO carcase (killdate, idsupplier, note) VALUES ( ?, ?, ?)";
$stmt = $conn->prepare($query);

$stmt->bind_param("sis", $killdate, $idsupplier, $note);

if ($stmt->execute()) {
   // Mendapatkan idcarcase terakhir yang diinputkan
   $idcarcase = $stmt->insert_id;

   // Redirect ke halaman carcasedetail.php dengan parameter idcarcase
   header("Location: carcasedetail.php?idcarcase=" . $idcarcase);
   exit;
} else {
   echo "Error: " . $stmt->error;
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
