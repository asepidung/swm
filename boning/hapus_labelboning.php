<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan eksekusi berhenti setelah redirect
}
// Koneksi ke database
require "../konak/conn.php";

// Periksa apakah parameter idlabelboning dan idboning telah diterima
if (isset($_GET['id']) && isset($_GET['idboning'])) {
   $idlabelboning = $_GET['id'];
   $idboning = $_GET['idboning'];
   $kdbarcode = $_GET['kdbarcode'];

   // Lakukan soft delete data di tabel labelboning dengan mengupdate status is_deleted menjadi 1
   $updateLabelBoning = mysqli_query($conn, "UPDATE labelboning SET is_deleted = 1 WHERE idlabelboning = '$idlabelboning'");

   // Lakukan hard delete data di tabel stock berdasarkan kdbarcode
   $hapusstock = mysqli_query($conn, "DELETE FROM stock WHERE kdbarcode = '$kdbarcode'");

   // Periksa apakah penghapusan data berhasil dilakukan
   if ($hapusstock && $updateLabelBoning) {
      // Catat aktivitas ke logactivity setelah data berhasil dihapus
      $idusers = $_SESSION['idusers'];
      $logSql = "INSERT INTO logactivity (iduser, event, docnumb) VALUES ('$idusers', 'Soft Hapus Label Boning', '$kdbarcode')";
      mysqli_query($conn, $logSql);

      // Jika berhasil, arahkan kembali ke halaman sebelumnya dengan pesan sukses dan idboning yang dilewatkan sebagai parameter query string
      header("Location: labelboning.php?id=$idboning");
      exit;
   } else {
      // Jika gagal, tampilkan pesan error
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='labelboning.php?id=$idboning';</script>";
   }
} else {
   // Jika parameter idlabelboning atau idboning tidak diterima, arahkan kembali ke halaman sebelumnya
   header("Location: labelboning.php?id=$idboning");
   exit;
}
?>
