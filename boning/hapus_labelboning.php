<?php
// Koneksi ke database
require "../konak/conn.php";

// Periksa apakah parameter idlabelboning dan idboning telah diterima
if (isset($_GET['id']) && isset($_GET['idboning'])) {
   $idlabelboning = $_GET['id'];
   $idboning = $_GET['idboning'];

   // Lakukan penghapusan data dari tabel labelboning
   $hapusdata = mysqli_query($conn, "DELETE FROM labelboning WHERE idlabelboning = '$idlabelboning'");

   // Periksa apakah penghapusan data berhasil dilakukan
   if ($hapusdata) {
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