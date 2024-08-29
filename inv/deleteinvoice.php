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

// Ambil nomor invoice sebelum dihapus untuk keperluan log activity
$sqlGetInvoiceNumber = "SELECT noinvoice FROM invoice WHERE idinvoice = ?";
$stmtGetInvoiceNumber = $conn->prepare($sqlGetInvoiceNumber);
$stmtGetInvoiceNumber->bind_param("i", $idinvoice);
$stmtGetInvoiceNumber->execute();
$stmtGetInvoiceNumber->bind_result($noinvoice);
$stmtGetInvoiceNumber->fetch();
$stmtGetInvoiceNumber->close();

// Jangan lupa untuk menutup koneksi database setelah selesai menggunakannya

// Hapus data dari tabel invoicedetail berdasarkan idinvoice
$sqlDeleteDetail = "DELETE FROM invoicedetail WHERE idinvoice = ?";
$stmtDeleteDetail = $conn->prepare($sqlDeleteDetail);
$stmtDeleteDetail->bind_param("i", $idinvoice);

if ($stmtDeleteDetail->execute()) {
   // Hapus data dari tabel piutang berdasarkan idinvoice
   $sqlDeletePiutang = "DELETE FROM piutang WHERE idinvoice = ?";
   $stmtDeletePiutang = $conn->prepare($sqlDeletePiutang);
   $stmtDeletePiutang->bind_param("i", $idinvoice);

   if ($stmtDeletePiutang->execute()) {
      // Hapus data dari tabel invoice berdasarkan idinvoice
      $sqlDeleteInvoice = "DELETE FROM invoice WHERE idinvoice = ?";
      $stmtDeleteInvoice = $conn->prepare($sqlDeleteInvoice);
      $stmtDeleteInvoice->bind_param("i", $idinvoice);

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
               // Insert log activity into logactivity table
               $idusers = $_SESSION['idusers'];
               $event = "Delete Invoice";
               $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                            VALUES (?, ?, ?, NOW())";
               $stmt_log = $conn->prepare($logQuery);
               $stmt_log->bind_param("iss", $idusers, $noinvoice, $event);
               $stmt_log->execute();
               $stmt_log->close();

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
} else {
   die("Terjadi kesalahan saat menghapus invoicedetail: " . $stmtDeleteDetail->error);
}

// Menutup koneksi database
$stmtDeleteDetail->close();
$stmtDeletePiutang->close();
$stmtDeleteInvoice->close();
$stmtUpdateDoStatus->close();
$stmtUpdateDoReceiptStatus->close();
$conn->close();
