<?php
// Koneksi ke database
require "../konak/conn.php";

// Periksa apakah parameter idbarang telah diterima
if (isset($_GET['id'])) {
   $idlabelboning = $_GET['id'];

   // Lakukan penghapusan data dari tabel products
   $hapusdata = mysqli_query($conn, "DELETE FROM labelboning WHERE idlabelboning = '$idlabelboning'");

   // Periksa apakah penghapusan data berhasil dilakukan
   if ($hapusdata) {
      // Jika berhasil, arahkan kembali ke halaman sebelumnya dengan pesan sukses
      echo "<script>alert('Data berhasil dihapus.'); window.location='labelboning.php';</script>";
   } else {
      // Jika gagal, tampilkan pesan error
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='labelboning.php';</script>";
   }
} else {
   // Jika parameter idbarang tidak diterima, arahkan kembali ke halaman sebelumnya
   header("Location: labelboning.php");
}
