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

   // Menghapus data dari tabel dodetail
   $sqlDeleteDodetail = "DELETE FROM dodetail WHERE iddo = ?";
   $stmtDeleteDodetail = $conn->prepare($sqlDeleteDodetail);
   $stmtDeleteDodetail->bind_param("i", $iddo);
   if (!$stmtDeleteDodetail->execute()) {
      throw new Exception("Error deleting dodetail: " . $stmtDeleteDodetail->error);
   }

   // Menghapus data dari tabel do
   $sqlDeleteDO = "DELETE FROM do WHERE iddo = ?";
   $stmtDeleteDO = $conn->prepare($sqlDeleteDO);
   $stmtDeleteDO->bind_param("i", $iddo);
   if (!$stmtDeleteDO->execute()) {
      throw new Exception("Error deleting do: " . $stmtDeleteDO->error);
   }

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
$stmtDeleteDodetail->close();
$stmtDeleteDO->close();
$conn->close();
