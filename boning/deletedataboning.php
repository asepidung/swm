<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Mendapatkan idboning dari parameter URL
$idboning = $_GET['idboning'];

// Hapus data dari tabel labelboning berdasarkan idboning
$hapusLabelBoning = "DELETE FROM labelboning WHERE idboning = $idboning";
if (mysqli_query($conn, $hapusLabelBoning)) {
   // Jika berhasil menghapus data dari tabel labelboning, lanjutkan ke penghapusan data dari tabel boning
   $hapusBoning = "DELETE FROM boning WHERE idboning = $idboning";
   if (mysqli_query($conn, $hapusBoning)) {
      header("Location: databoning.php");
      exit;
   } else {
      echo "Gagal menghapus data dari tabel boning: " . mysqli_error($conn);
   }
} else {
   echo "Gagal menghapus data dari tabel labelboning: " . mysqli_error($conn);
}

// Tutup koneksi database
mysqli_close($conn);
