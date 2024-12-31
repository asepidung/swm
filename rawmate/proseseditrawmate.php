<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// mengambil data dari form
$idrawmate = $_POST['idrawmate'];
$nmrawmate = $_POST['nmrawmate'];
$idrawcategory = $_POST['idrawcategory'];

// membuat query untuk memperbarui data rawmate di database
$sql = "UPDATE rawmate SET nmrawmate = '$nmrawmate' WHERE idrawmate = '$idrawmate'";

// mengeksekusi query
if (mysqli_query($conn, $sql)) {
  echo "<script>alert('Data rawmate berhasil diperbarui.'); window.location='index.php';</script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// menutup koneksi ke database
mysqli_close($conn);
