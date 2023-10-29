<?php
require "../konak/conn.php";

// mengambil data dari form
$kdbarang = $_POST['kdbarang'];
$nmbarang = $_POST['nmbarang'];
$cut = $_POST['cut'];
// Mengecek apakah nama barang sudah ada dalam database
$checkQuery = "SELECT nmbarang FROM barang WHERE nmbarang = '$nmbarang'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
   echo "<script>alert('Nama barang sudah ada dalam database.');</script>";
   echo "<script>window.location='newbarang.php';</script>";
} else {

   $sql = "INSERT INTO barang (kdbarang, nmbarang, idcut) VALUES ('$kdbarang', '$nmbarang', '$cut')";
   if (mysqli_query($conn, $sql)) {
      echo "<script>alert('Data berhasil disimpan.'); window.location='barang.php';</script>";
   } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
   }
}

// menutup koneksi ke database
mysqli_close($conn);
