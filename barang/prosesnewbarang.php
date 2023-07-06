<?php
require "../konak/conn.php";

// mengambil data dari form
$kdbarang = $_POST['kdbarang'];
$nmbarang = $_POST['nmbarang'];

// Mengecek apakah nama barang sudah ada dalam database
$checkQuery = "SELECT nmbarang FROM barang WHERE nmbarang = '$nmbarang'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
   // Nama barang sudah ada dalam database, tampilkan peringatan
   echo "<script>alert('Nama barang sudah ada dalam database.');</script>";
   // Redirect atau lakukan tindakan lain sesuai kebutuhan
   echo "<script>window.location='newbarang.php';</script>";
} else {
   // Nama barang belum ada dalam database, lanjutkan dengan penyimpanan

   // membuat query untuk menyimpan data ke database
   $sql = "INSERT INTO barang (kdbarang, nmbarang) VALUES ('$kdbarang', '$nmbarang')";

   // mengeksekusi query
   if (mysqli_query($conn, $sql)) {
      echo "<script>alert('Data berhasil disimpan.'); window.location='barang.php';</script>";
   } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
   }
}

// menutup koneksi ke database
mysqli_close($conn);
