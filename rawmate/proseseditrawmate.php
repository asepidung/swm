<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Mengambil data dari form
$idrawmate = intval($_POST['idrawmate']); // Validasi sebagai integer
$nmrawmate = mysqli_real_escape_string($conn, $_POST['nmrawmate']); // Escape input untuk keamanan
$idrawcategory = intval($_POST['idrawcategory']); // Validasi sebagai integer
$stock = intval($_POST['stock']); // Validasi nilai stock sebagai integer (1 atau 0)

// Membuat query untuk memperbarui data rawmate di database
$sql = "UPDATE rawmate 
        SET nmrawmate = '$nmrawmate', idrawcategory = $idrawcategory, stock = $stock 
        WHERE idrawmate = $idrawmate";

// Mengeksekusi query
if (mysqli_query($conn, $sql)) {
  echo "<script>alert('Data rawmate berhasil diperbarui.'); window.location='index.php';</script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Menutup koneksi ke database
mysqli_close($conn);
