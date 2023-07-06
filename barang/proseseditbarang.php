<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// mengambil data dari form
$kdbarang = $_POST['kdbarang'];
$nmbarang = $_POST['nmbarang'];

// membuat query untuk memperbarui data barang di database
$sql = "UPDATE barang SET nmbarang = '$nmbarang' WHERE kdbarang = '$kdbarang'";

// mengeksekusi query
if (mysqli_query($conn, $sql)) {
  echo "<script>alert('Data barang berhasil diperbarui.'); window.location='barang.php';</script>";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// menutup koneksi ke database
mysqli_close($conn);
