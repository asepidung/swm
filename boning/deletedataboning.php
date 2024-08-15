<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan eksekusi berhenti setelah redirect
}
require "../konak/conn.php";

// Mendapatkan idboning dan batchboning dari parameter URL atau database
$idboning = $_GET['idboning'];

// Mendapatkan batchboning dari database sebelum data dihapus
$query = "SELECT batchboning FROM boning WHERE idboning = $idboning";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$batchboning = $row['batchboning'];

// Hapus data dari tabel labelboning berdasarkan idboning
$hapusLabelBoning = "DELETE FROM labelboning WHERE idboning = $idboning";
if (mysqli_query($conn, $hapusLabelBoning)) {
   // Jika berhasil menghapus data dari tabel labelboning, lanjutkan ke penghapusan data dari tabel boning
   $hapusBoning = "DELETE FROM boning WHERE idboning = $idboning";
   if (mysqli_query($conn, $hapusBoning)) {
      // Catat aktivitas ke logactivity setelah data berhasil dihapus
      $idusers = $_SESSION['idusers'];
      $logSql = "INSERT INTO logactivity (iduser, event, docnumb) VALUES ('$idusers', 'Hapus Batch Boning', '$batchboning')";
      mysqli_query($conn, $logSql);

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
