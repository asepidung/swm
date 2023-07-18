<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
$iddo = $_GET['iddo'];

// Menghapus data dari tabel dodetail berdasarkan iddo
$sqlDeleteDetail = "DELETE FROM dodetail WHERE iddo = $iddo";
if ($conn->query($sqlDeleteDetail) === TRUE) {
   // Jika penghapusan dodetail berhasil, lanjutkan menghapus data dari tabel do
   $sqlDeleteDO = "DELETE FROM do WHERE iddo = $iddo";
   if ($conn->query($sqlDeleteDO) === TRUE) {
      echo "<script>alert('Delivery Order berhasil dihapus.'); window.location='do.php';</script>";
   } else {
      echo "Terjadi kesalahan saat menghapus data dari tabel do: " . $conn->error;
   }
} else {
   echo "Terjadi kesalahan saat menghapus data dari tabel dodetail: " . $conn->error;
}

// Menutup koneksi database
$conn->close();
