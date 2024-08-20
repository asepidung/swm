<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['id']) && isset($_GET['iddetail'])) {
   $id = $_GET['id'];
   $iddetail = $_GET['iddetail'];
   $getBarcodeQuery = "SELECT kdbarcode FROM returjualdetail WHERE idreturjualdetail = '$iddetail'";
   $getBarcodeResult = mysqli_query($conn, $getBarcodeQuery);

   if ($getBarcodeResult && $rowBarcode = mysqli_fetch_assoc($getBarcodeResult)) {
      $kdbarcode = $rowBarcode['kdbarcode'];
      $hapusDataDetail = mysqli_query($conn, "DELETE FROM returjualdetail WHERE idreturjualdetail = '$iddetail'");
      $hapusDataStock = mysqli_query($conn, "DELETE FROM stock WHERE kdbarcode = '$kdbarcode'");

      if ($hapusDataDetail && $hapusDataStock) {
         // Insert ke tabel logactivity
         $idusers = $_SESSION['idusers'];
         $event = "Delete Detail Retur";
         $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) 
                      VALUES ($idusers, '$event', '$kdbarcode', NOW())";
         mysqli_query($conn, $logQuery);

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
