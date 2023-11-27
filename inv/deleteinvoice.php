<?php
session_start();

if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit;
}

require "../konak/conn.php";

$idinvoice = isset($_GET['idinvoice']) ? intval($_GET['idinvoice']) : 0;
$iddo = isset($_GET['iddo']) ? intval($_GET['iddo']) : 0;

// Periksa apakah $idinvoice adalah angka yang valid
if ($idinvoice <= 0) {
   die("ID Invoice tidak valid.");
}

// Menghapus data dari tabel dodetail berdasarkan iddo
$sqlDeleteDetail = "DELETE FROM invoicedetail WHERE idinvoice = ?";
$stmtDeleteDetail = $conn->prepare($sqlDeleteDetail);
$stmtDeleteDetail->bind_param("i", $idinvoice);

// Jika penghapusan invoicedetail berhasil, lanjutkan menghapus data dari tabel piutang
$sqlDeletePiutang = "DELETE FROM piutang WHERE idinvoice = ?";
$stmtDeletePiutang = $conn->prepare($sqlDeletePiutang);
$stmtDeletePiutang->bind_param("i", $idinvoice);

// Jika penghapusan piutang berhasil, lanjutkan menghapus data dari tabel invoice
$sqlDeleteInvoice = "DELETE FROM invoice WHERE idinvoice = ?";
$stmtDeleteInvoice = $conn->prepare($sqlDeleteInvoice);
$stmtDeleteInvoice->bind_param("i", $idinvoice);

if ($stmtDeletePiutang->execute()) {
   // Setelah penghapusan piutang berhasil, lanjutkan menghapus data dari tabel invoice
   if ($stmtDeleteInvoice->execute()) {
      // Lakukan UPDATE status di tabel do menjadi "Approved" berdasarkan ID DO
      $sqlUpdateDoStatus = "UPDATE do SET status = 'Approved' WHERE iddo = ?";
      $stmtUpdateDoStatus = $conn->prepare($sqlUpdateDoStatus);
      $stmtUpdateDoStatus->bind_param("i", $iddo);

      if ($stmtUpdateDoStatus->execute()) {
         // Lakukan UPDATE status di tabel doreceipt menjadi "Approved" berdasarkan ID DO
         $sqlUpdateDoReceiptStatus = "UPDATE doreceipt SET status = 'Approved' WHERE iddo = ?";
         $stmtUpdateDoReceiptStatus = $conn->prepare($sqlUpdateDoReceiptStatus);
         $stmtUpdateDoReceiptStatus->bind_param("i", $iddo);

         if ($stmtUpdateDoReceiptStatus->execute()) {
            echo "<script>alert('Invoice berhasil di Reject.'); window.location='invoice.php';</script>";
         } else {
            die("Terjadi kesalahan saat mengupdate status doreceipt: " . $stmtUpdateDoReceiptStatus->error);
         }
      } else {
         die("Terjadi kesalahan saat mengupdate status DO: " . $stmtUpdateDoStatus->error);
      }
   } else {
      die("Terjadi kesalahan saat menghapus invoice: " . $stmtDeleteInvoice->error);
   }
} else {
   die("Terjadi kesalahan saat menghapus piutang: " . $stmtDeletePiutang->error);
}

// Menutup koneksi database
$stmtDeleteDetail->close();
$stmtDeletePiutang->close();
$stmtDeleteInvoice->close();
$stmtUpdateDoStatus->close();
$stmtUpdateDoReceiptStatus->close();
$conn->close();
