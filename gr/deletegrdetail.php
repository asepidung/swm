<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

// Koneksi ke database
require "../konak/conn.php";

if (isset($_GET['idgr']) && isset($_GET['idgrdetail'])) {
   $idgr = $_GET['idgr'];
   $idgrdetail = $_GET['idgrdetail'];
   $from = $_GET['from'] ?? 'grdetail';  // Set default ke 'grdetail' jika 'from' tidak diset

   // Ambil kdbarcode dari tabel grdetail
   $getBarcodeQuery = "SELECT kdbarcode FROM grdetail WHERE idgrdetail = '$idgrdetail'";
   $getBarcodeResult = mysqli_query($conn, $getBarcodeQuery);

   if ($getBarcodeResult && $rowBarcode = mysqli_fetch_assoc($getBarcodeResult)) {
      $kdbarcode = $rowBarcode['kdbarcode'];

      // Hapus data dari tabel grdetail
      $hapusDataDetail = mysqli_query($conn, "DELETE FROM grdetail WHERE idgrdetail = '$idgrdetail'");

      if ($hapusDataDetail) {
         // Hapus data dari tabel stock jika penghapusan di grdetail berhasil
         $hapusDataStock = mysqli_query($conn, "DELETE FROM stock WHERE kdbarcode = '$kdbarcode'");

         if ($hapusDataStock) {
            $_SESSION['success'] = "Data berhasil dihapus dari GR Detail dan Stock.";
         } else {
            $_SESSION['error'] = "Data berhasil dihapus dari GR Detail, tetapi gagal dihapus dari Stock.";
         }
      } else {
         $_SESSION['error'] = "Maaf, terjadi kesalahan saat menghapus data dari GR Detail.";
      }

      // Redirect sesuai halaman asal
      if ($from === 'grscan') {
         header("Location: grscan.php?idgr=$idgr");
      } else {
         header("Location: grdetail.php?idgr=$idgr");
      }
      exit();
   } else {
      $_SESSION['error'] = "Data tidak ditemukan atau terjadi kesalahan saat menghapus data.";
      header("Location: grdetail.php?idgr=$idgr");
      exit();
   }
}
