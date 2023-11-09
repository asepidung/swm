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
   $hapusdata = mysqli_query($conn, "DELETE FROM detailbahan WHERE iddetailbahan = '$iddetail'");

   // Periksa apakah penghapusan data berhasil dilakukan
   if ($hapusdata) {
      header("Location: detailbahan.php?id=$id&stat=deleted");
   } else {
      // Jika gagal, tampilkan pesan error
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='tallydetail.php?id=$id';</script>";
   }
}
