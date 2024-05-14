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

// Persiapkan dan jalankan query penghapusan dengan prepared statement
$stmt = mysqli_prepare($conn, "DELETE FROM repack WHERE idrepack = ?");
mysqli_stmt_bind_param($stmt, "i", $idrepack);
$hapusData = mysqli_stmt_execute($stmt);

// Periksa apakah query eksekusi berhasil atau tidak
if ($hapusData) {
   // Redirect ke halaman index setelah penghapusan berhasil
   header("Location: index.php");
} else {
   // Jika terjadi kesalahan saat menghapus data
   echo "Terjadi kesalahan saat menghapus data.";
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($conn);
