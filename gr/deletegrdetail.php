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
   $getBarcodeQuery = "SELECT kdbarcode FROM grdetail WHERE idgrdetail = ?";
   $stmtGetBarcode = $conn->prepare($getBarcodeQuery);
   $stmtGetBarcode->bind_param("i", $idgrdetail);
   $stmtGetBarcode->execute();
   $resultBarcode = $stmtGetBarcode->get_result();

   if ($resultBarcode && $rowBarcode = $resultBarcode->fetch_assoc()) {
      $kdbarcode = $rowBarcode['kdbarcode'];

      // Soft delete data dari tabel grdetail (set is_deleted = 1)
      $softDeleteDetailQuery = "UPDATE grdetail SET is_deleted = 1 WHERE idgrdetail = ?";
      $stmtSoftDeleteDetail = $conn->prepare($softDeleteDetailQuery);
      $stmtSoftDeleteDetail->bind_param("i", $idgrdetail);
      $successSoftDelete = $stmtSoftDeleteDetail->execute();

      if ($successSoftDelete) {
         // Hapus data dari tabel stock jika soft delete di grdetail berhasil
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
