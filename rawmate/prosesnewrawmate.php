<?php
require "../konak/conn.php";

// mengambil data dari form
$kdrawmate = $_POST['kdrawmate'];
$nmrawmate = $_POST['nmrawmate'];
$tampilkan_stock = $_POST['tampilkan_stock'];
$idrawcategory = $_POST['idrawcategory'];
// Mengecek apakah nama rawmate sudah ada dalam database
$checkQuery = "SELECT nmrawmate FROM rawmate WHERE nmrawmate = '$nmrawmate'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
   // Nama rawmate sudah ada dalam database, tampilkan peringatan
   echo "<script>alert('Nama rawmate sudah ada dalam database.');</script>";
   // Redirect atau lakukan tindakan lain sesuai kebutuhan
   echo "<script>window.location='newrawmate.php';</script>";
} else {
   // Nama rawmate belum ada dalam database, lanjutkan dengan penyimpanan

   // membuat query untuk menyimpan data ke database
   $sql = "INSERT INTO rawmate (kdrawmate, nmrawmate, idrawcategory, stock) VALUES ('$kdrawmate', '$nmrawmate', '$idrawcategory', '$tampilkan_stock')";

   // mengeksekusi query
   if (mysqli_query($conn, $sql)) {
      echo "<script>alert('Data berhasil disimpan.'); window.location='index.php';</script>";
   } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
   }
}

// menutup koneksi ke database
mysqli_close($conn);
