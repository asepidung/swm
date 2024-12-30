<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";

if (isset($_GET['idgr']) && isset($_GET['idpo'])) {
   $idgr = $_GET['idgr'];
   $idpo = $_GET['idpo'];

   // Mulai transaksi
   $conn->autocommit(false);

   try {
      // Ambil semua kdbarcode dari tabel grdetail berdasarkan idgr
      $query_get_barcodes = "SELECT kdbarcode FROM grdetail WHERE idgr = ?";
      $stmt_get_barcodes = $conn->prepare($query_get_barcodes);
      $stmt_get_barcodes->bind_param("i", $idgr);
      $stmt_get_barcodes->execute();
      $result_barcodes = $stmt_get_barcodes->get_result();

      $barcodes_grdetail = [];
      while ($row = $result_barcodes->fetch_assoc()) {
         $barcodes_grdetail[] = $row['kdbarcode'];
      }
      $stmt_get_barcodes->close();

      if (empty($barcodes_grdetail)) {
         throw new Exception("Tidak ada barcode yang ditemukan di GR Detail.");
      }

      // Cek kecocokan barcode antara grdetail dan stock
      $query_check_stock = "SELECT kdbarcode FROM stock WHERE kdbarcode IN (" . str_repeat('?,', count($barcodes_grdetail) - 1) . "?)";
      $stmt_check_stock = $conn->prepare($query_check_stock);
      $stmt_check_stock->bind_param(str_repeat("s", count($barcodes_grdetail)), ...$barcodes_grdetail);
      $stmt_check_stock->execute();
      $result_stock = $stmt_check_stock->get_result();

      $barcodes_stock = [];
      while ($row = $result_stock->fetch_assoc()) {
         $barcodes_stock[] = $row['kdbarcode'];
      }
      $stmt_check_stock->close();

      // Bandingkan barcode grdetail dan stock
      sort($barcodes_grdetail);
      sort($barcodes_stock);
      if ($barcodes_grdetail !== $barcodes_stock) {
         throw new Exception("Penghapusan Gagal, Barang Sudah digunakan.");
      }

      // Update data di tabel gr menjadi soft delete (set is_deleted = 1)
      $query_soft_delete_gr = "UPDATE gr SET is_deleted = 1 WHERE idgr = ?";
      $stmt_soft_delete_gr = $conn->prepare($query_soft_delete_gr);
      if (!$stmt_soft_delete_gr) {
         throw new Exception("Error preparing soft delete gr statement: " . $conn->error);
      }
      $stmt_soft_delete_gr->bind_param("i", $idgr);
      $stmt_soft_delete_gr->execute();
      $stmt_soft_delete_gr->close();

      // Hapus data dari tabel stock berdasarkan barcode
      $query_delete_stock = "DELETE FROM stock WHERE kdbarcode IN (" . str_repeat('?,', count($barcodes_stock) - 1) . "?)";
      $stmt_delete_stock = $conn->prepare($query_delete_stock);
      $stmt_delete_stock->bind_param(str_repeat("s", count($barcodes_stock)), ...$barcodes_stock);
      $stmt_delete_stock->execute();
      $stmt_delete_stock->close();

      // Update kolom 'stat' di tabel poproduct
      $query_update_poproduct = "UPDATE poproduct SET stat = 'Waiting' WHERE idpoproduct = ?";
      $stmt_update_poproduct = $conn->prepare($query_update_poproduct);
      if (!$stmt_update_poproduct) {
         throw new Exception("Error preparing update poproduct statement: " . $conn->error);
      }
      $stmt_update_poproduct->bind_param("i", $idpo);
      $stmt_update_poproduct->execute();
      $stmt_update_poproduct->close();

      // Insert log activity ke tabel logactivity
      $iduser = $_SESSION['idusers']; // Ambil iduser dari session
      $event = "Delete GR"; // Set nilai event sesuai kebutuhan
      $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb) VALUES (?, ?, ?)";
      $stmtLogActivity = $conn->prepare($queryLogActivity);
      if (!$stmtLogActivity) {
          throw new Exception("Error preparing log activity statement: " . $conn->error);
      }
      
      $stmtLogActivity->bind_param('iss', $iduser, $event, $idgr);
      
      if (!$stmtLogActivity->execute()) {
          throw new Exception("Error executing log activity statement: " . $stmtLogActivity->error);
      }
      

      // Commit transaksi jika semua query berhasil dieksekusi
      $conn->commit();
   } catch (Exception $e) {
      // Rollback transaksi jika terjadi kesalahan
      $conn->rollback();
      echo "<p>Error: " . $e->getMessage() . "</p>";
      echo "<a href='javascript:history.back()'>Kembali</a>";
      exit();
   } finally {
      // Set autocommit kembali ke true setelah selesai
      $conn->autocommit(true);
      $conn->close();
   }
}

header("location: index.php"); // Redirect to the list page
exit();
