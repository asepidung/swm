<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
// Koneksi ke database
require "../konak/conn.php";

// Periksa apakah parameter idlabelboning dan idboning telah diterima
if (isset($_GET['id']) && isset($_GET['idboning'])) {
   $idlabelboning = $_GET['id'];
   $idboning = $_GET['idboning'];
   $kdbarcode = $_GET['kdbarcode'];

   // Lakukan penghapusan data dari tabel labelboning
   $hapusdata = mysqli_query($conn, "DELETE FROM labelboning WHERE idlabelboning = '$idlabelboning'");
   $hapusstock = mysqli_query($conn, "DELETE FROM stock WHERE kdbarcode = '$kdbarcode'");
   // Periksa apakah penghapusan data berhasil dilakukan
   if ($hapusstock) {
      // Jika berhasil, arahkan kembali ke halaman sebelumnya dengan pesan sukses dan idboning yang dilewatkan sebagai parameter query string
      echo "<script>alert('Data berhasil dihapus.'); window.location='labelboning.php?id=$idboning';</script>";
   } else {
      // Jika gagal, tampilkan pesan error
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='labelboning.php?id=$idboning';</script>";
   }
} else {
   // Jika parameter idlabelboning atau idboning tidak diterima, arahkan kembali ke halaman sebelumnya
   header("Location: labelboning.php?id=$idboning");
}
