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
      // Ambil grnumber dari tabel gr sebelum menghapus data
      $query_select_gr = "SELECT grnumber FROM gr WHERE idgr = ?";
      $stmt_select_gr = $conn->prepare($query_select_gr);
      $stmt_select_gr->bind_param("i", $idgr);
      $stmt_select_gr->execute();
      $stmt_select_gr->bind_result($grnumber);
      $stmt_select_gr->fetch();
      $stmt_select_gr->close();

      // Delete data dari tabel grdetail
      $query_delete_grdetail = "DELETE FROM grdetail WHERE idgr = ?";
      $stmt_delete_grdetail = $conn->prepare($query_delete_grdetail);
      if (!$stmt_delete_grdetail) {
         throw new Exception("Error preparing delete grdetail statement: " . $conn->error);
      }
      $stmt_delete_grdetail->bind_param("i", $idgr);
      $stmt_delete_grdetail->execute();
      $stmt_delete_grdetail->close();

      // Delete data dari tabel gr
      $query_delete_gr = "DELETE FROM gr WHERE idgr = ?";
      $stmt_delete_gr = $conn->prepare($query_delete_gr);
      if (!$stmt_delete_gr) {
         throw new Exception("Error preparing delete gr statement: " . $conn->error);
      }
      $stmt_delete_gr->bind_param("i", $idgr);
      $stmt_delete_gr->execute();
      $stmt_delete_gr->close();

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
      $event = "Hapus Good Receipt";
      $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb) VALUES (?, ?, ?)";
      $stmtLogActivity = $conn->prepare($queryLogActivity);
      if (!$stmtLogActivity) {
         throw new Exception("Error preparing log activity statement: " . $conn->error);
      }

      $stmtLogActivity->bind_param('iss', $iduser, $event, $grnumber);

      if (!$stmtLogActivity->execute()) {
         throw new Exception("Error executing log activity statement: " . $stmtLogActivity->error);
      }

      // Commit transaksi jika semua query berhasil dieksekusi
      $conn->commit();
   } catch (Exception $e) {
      // Rollback transaksi jika terjadi kesalahan
      $conn->rollback();
      echo "Error: " . $e->getMessage();
   } finally {
      // Set autocommit kembali ke true setelah selesai
      $conn->autocommit(true);
      $conn->close();
   }
}

header("location: index.php"); // Redirect to the list page
exit();
