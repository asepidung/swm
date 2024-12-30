<?php

session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";
$iddo = intval($_GET['iddo']);

// Mulai transaksi
$conn->begin_transaction();

try {
   // Dapatkan nomor DO (donumber) sebelum dihapus
   $sqlGetDonumber = "SELECT donumber FROM do WHERE iddo = ?";
   $stmtGetDonumber = $conn->prepare($sqlGetDonumber);
   $stmtGetDonumber->bind_param("i", $iddo);
   $stmtGetDonumber->execute();
   $stmtGetDonumber->bind_result($donumber);
   $stmtGetDonumber->fetch();
   $stmtGetDonumber->close();

   // Update kolom stat di tabel tally menjadi 'Approved'
   $sqlUpdateTally = "UPDATE tally 
                       INNER JOIN do ON tally.idtally = do.idtally
                       SET tally.stat = 'Approved' 
                       WHERE do.iddo = ?";
   $stmtUpdateTally = $conn->prepare($sqlUpdateTally);
   $stmtUpdateTally->bind_param("i", $iddo);
   if (!$stmtUpdateTally->execute()) {
      throw new Exception("Error updating tally: " . $stmtUpdateTally->error);
   }

   // Menghapus data dari tabel doreceiptdetail berdasarkan iddoreceipt dari tabel doreceipt
   $sqlDeleteDoreceiptdetail = "DELETE doreceiptdetail 
                                 FROM doreceiptdetail
                                 INNER JOIN doreceipt ON doreceiptdetail.iddoreceipt = doreceipt.iddoreceipt
                                 WHERE doreceipt.iddo = ?";
   $stmtDeleteDoreceiptdetail = $conn->prepare($sqlDeleteDoreceiptdetail);
   $stmtDeleteDoreceiptdetail->bind_param("i", $iddo);
   if (!$stmtDeleteDoreceiptdetail->execute()) {
      throw new Exception("Error deleting doreceiptdetail: " . $stmtDeleteDoreceiptdetail->error);
   }

   // Menghapus data dari tabel doreceipt
   $sqlDeleteDoreceipt = "DELETE FROM doreceipt WHERE iddo = ?";
   $stmtDeleteDoreceipt = $conn->prepare($sqlDeleteDoreceipt);
   $stmtDeleteDoreceipt->bind_param("i", $iddo);
   if (!$stmtDeleteDoreceipt->execute()) {
      throw new Exception("Error deleting doreceipt: " . $stmtDeleteDoreceipt->error);
   }

   // Soft delete data dari tabel do
   $sqlSoftDeleteDO = "UPDATE do SET is_deleted = 1 WHERE iddo = ?";
   $stmtSoftDeleteDO = $conn->prepare($sqlSoftDeleteDO);
   $stmtSoftDeleteDO->bind_param("i", $iddo);
   if (!$stmtSoftDeleteDO->execute()) {
      throw new Exception("Error soft deleting do: " . $stmtSoftDeleteDO->error);
   }

   // Log activity untuk Hapus DO
   $idusers = $_SESSION['idusers'];
   $event = "Delete DO";
   $query_log = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, NOW())";
   $stmt_log = $conn->prepare($query_log);
   $stmt_log->bind_param("iss", $idusers, $event, $donumber);
   $stmt_log->execute();
   $stmt_log->close();

   // Commit transaksi
   $conn->commit();
   echo "<script>alert('Delivery Order berhasil dihapus.'); window.location='do.php';</script>";
} catch (Exception $e) {
   // Rollback transaksi jika terjadi kesalahan
   $conn->rollback();
   echo "Terjadi kesalahan: " . $e->getMessage();
}

// Menutup prepared statements dan koneksi database
$stmtUpdateTally->close();
$stmtDeleteDoreceiptdetail->close();
$stmtDeleteDoreceipt->close();
$stmtSoftDeleteDO->close();
$conn->close();
