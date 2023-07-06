<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Mendapatkan idbarang dari URL
$idbarang = $_GET['idbarang'];

// Menghapus data barang dari database berdasarkan idbarang
$sql = "DELETE FROM barang WHERE idbarang = '$idbarang'";

// Mengeksekusi query
if (mysqli_query($conn, $sql)) {
  echo "<script>alert('Data barang berhasil dihapus.'); window.location='barang.php';</script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Menutup koneksi ke database
mysqli_close($conn);
