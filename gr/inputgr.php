<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";
include "grnumber.php";

if (isset($_POST['submit'])) {
   $deliveryat = $_POST['deliveryat'];
   $idsupplier = $_POST['idsupplier'];
   $idnumber = $_POST['idnumber'];
   $note = $_POST['note'];
   $idpo = $_POST['idpoproduct']; // Pastikan nama kolom ini benar di tabel gr
   $idusers = $_SESSION['idusers'];

   // Mulai transaksi
   $conn->autocommit(false);

   try {
      // Query INSERT untuk tabel gr
      $query_gr = "INSERT INTO gr (grnumber, receivedate, idsupplier, idnumber, note, iduser, idpo) VALUES (?,?,?,?,?,?,?)";
      $stmt_gr = $conn->prepare($query_gr);

      // Tambahkan pemeriksaan error untuk memastikan prepare berhasil
      if ($stmt_gr === false) {
         throw new Exception("Error preparing insert statement: " . $conn->error);
      }

      // Bind parameter dan eksekusi
      $stmt_gr->bind_param("ssissii", $gr, $deliveryat, $idsupplier, $idnumber, $note, $idusers, $idpo);

      if (!$stmt_gr->execute()) {
         throw new Exception("Error executing insert statement: " . $stmt_gr->error);
      }

      // Setelah berhasil melakukan insert, lakukan update pada tabel poproduct
      $query_update = "UPDATE poproduct SET stat = 'BTB' WHERE idpoproduct = ?";
      $stmt_update = $conn->prepare($query_update);

      if ($stmt_update === false) {
         throw new Exception("Error preparing update statement: " . $conn->error);
      }

      $stmt_update->bind_param("i", $idpo);

      if (!$stmt_update->execute()) {
         throw new Exception("Error executing update statement: " . $stmt_update->error);
      }

      // Insert log activity ke tabel logactivity
      $event = "Buat Good Receipt";
      $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb) VALUES (?, ?, ?)";
      $stmtLogActivity = $conn->prepare($queryLogActivity);
      if (!$stmtLogActivity) {
         throw new Exception("Error preparing log activity statement: " . $conn->error);
      }

      $stmtLogActivity->bind_param('iss', $idusers, $event, $gr);

      if (!$stmtLogActivity->execute()) {
         throw new Exception("Error executing log activity statement: " . $stmtLogActivity->error);
      }

      // Commit transaksi jika semua query berhasil dieksekusi
      $conn->commit();

      // Redirect ke halaman index jika berhasil
      header("location: index.php");
      exit();
   } catch (Exception $e) {
      // Rollback transaksi jika terjadi kesalahan
      $conn->rollback();
      echo "Error: " . $e->getMessage();
   } finally {
      // Set autocommit kembali ke true setelah selesai
      $conn->autocommit(true);
      $stmt_gr->close();
      if (isset($stmt_update)) {
         $stmt_update->close();
      }
      if (isset($stmtLogActivity)) {
         $stmtLogActivity->close();
      }
      $conn->close();
   }
}
