<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk keluar dari skrip setelah mengarahkan pengguna ke halaman login
}

require "../konak/conn.php";

// Validasi input idrepack
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
   // Jika idrepack tidak ditemukan atau tidak valid, arahkan pengguna kembali
   header("Location: index.php");
   exit();
}

$idrepack = $_GET['id'];
$iduser = $_SESSION['idusers']; // Ambil ID user dari sesi yang aktif

// Dapatkan nomor repack (norepack) sebelum dihapus untuk log
$norepackQuery = "SELECT norepack FROM repack WHERE idrepack = ?";
$stmt = mysqli_prepare($conn, $norepackQuery);
mysqli_stmt_bind_param($stmt, "i", $idrepack);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $norepack);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Persiapkan dan jalankan query penghapusan dengan prepared statement
$stmt = mysqli_prepare($conn, "DELETE FROM repack WHERE idrepack = ?");
mysqli_stmt_bind_param($stmt, "i", $idrepack);
$hapusData = mysqli_stmt_execute($stmt);

// Periksa apakah query eksekusi berhasil atau tidak
if ($hapusData) {
   // Catat log aktivitas setelah penghapusan berhasil
   $event = "Hapus Data Repack";
   $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES ('$iduser', '$event', '$norepack', NOW())";
   mysqli_query($conn, $logQuery);

   // Redirect ke halaman index setelah penghapusan berhasil
   header("Location: index.php");
} else {
   // Jika terjadi kesalahan saat menghapus data
   echo "Terjadi kesalahan saat menghapus data.";
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($conn);
