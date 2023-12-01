<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
// Koneksi ke database
require "../konak/conn.php";

if (isset($_GET['id']) && isset($_GET['iddetail'])) {
   $id = $_GET['id'];
   $iddetail = $_GET['iddetail'];

   // Lakukan penghapusan data dari tabel labelboning
   $hapusdata = mysqli_query($conn, "DELETE FROM stocktakedetail WHERE idstdetail = '$iddetail'");

   // Periksa apakah penghapusan data berhasil dilakukan
   if ($hapusdata) {
      header("Location: starttaking.php?id=$id&stat=deleted");
   } else {
      // Jika gagal, tampilkan pesan error
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='starttaking.php?id=$id';</script>";
   }
}
