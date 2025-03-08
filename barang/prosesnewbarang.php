<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Mengambil data dari form
$kdbarang = $_POST['kdbarang'];
$nmbarang = $_POST['nmbarang'];
$cut = $_POST['cut'];

// Mengecek apakah nama barang sudah ada dalam database
$checkQuery = "SELECT nmbarang FROM barang WHERE nmbarang = '$nmbarang'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
   // Jika nama barang sudah ada, tampilkan pesan dan kembali ke halaman barang.php
   echo "<script>alert('Nama barang sudah ada dalam database.');</script>";
   echo "<script>window.location='barang.php';</script>";
} else {
   // Jika nama barang belum ada, lanjutkan proses insert
   $sql = "INSERT INTO barang (kdbarang, nmbarang, idcut) VALUES ('$kdbarang', '$nmbarang', '$cut')";
   if (mysqli_query($conn, $sql)) {
      echo "<script>alert('Data berhasil disimpan.'); window.location='barang.php';</script>";
   } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
   }
}

// Menutup koneksi ke database
mysqli_close($conn);
