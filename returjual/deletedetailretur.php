<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['id']) && isset($_GET['iddetail'])) {
   $id = $_GET['id'];
   $iddetail = $_GET['iddetail'];
   $getBarcodeQuery = "SELECT kdbarcode FROM returjualdetail WHERE idreturjualdetail = ?";
   $stmtGetBarcode = $conn->prepare($getBarcodeQuery);
   $stmtGetBarcode->bind_param("i", $iddetail);
   $stmtGetBarcode->execute();
   $resultBarcode = $stmtGetBarcode->get_result();

   if ($resultBarcode && $rowBarcode = $resultBarcode->fetch_assoc()) {
      $kdbarcode = $rowBarcode['kdbarcode'];

      // Soft delete data dari tabel returjualdetail (set is_deleted = 1)
      $softDeleteDetailQuery = "UPDATE returjualdetail SET is_deleted = 1 WHERE idreturjualdetail = ?";
      $stmtSoftDeleteDetail = $conn->prepare($softDeleteDetailQuery);
      $stmtSoftDeleteDetail->bind_param("i", $iddetail);
      $successSoftDelete = $stmtSoftDeleteDetail->execute();

      if ($successSoftDelete) {
         // Insert ke tabel logactivity
         $idusers = $_SESSION['idusers'];
         $event = "Soft Delete Detail Retur";
         $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) 
                      VALUES (?, ?, ?, NOW())";
         $stmtLogActivity = $conn->prepare($logQuery);
         $stmtLogActivity->bind_param("iss", $idusers, $event, $kdbarcode);
         $stmtLogActivity->execute();

         // Redirect ke halaman detail returjual
         header("Location: detailrj.php?idreturjual=$id");
         exit();
      } else {
         echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='detailrj.php?idreturjual=$id';</script>";
      }
   } else {
      echo "<script>alert('Maaf, terjadi kesalahan saat menghapus data.'); window.location='detailrj.php?idreturjual=$id';</script>";
   }
}
