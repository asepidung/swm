<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit;
}

require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $idinvoice = isset($_POST['idinvoice']) ? intval($_POST['idinvoice']) : 0;
   if ($idinvoice <= 0) {
      die("ID Invoice tidak valid.");
   }

   $tgltf = mysqli_real_escape_string($conn, $_POST['tgltf']);
   $top = mysqli_real_escape_string($conn, $_POST['top']);
   $tgltf_date_obj = new DateTime($tgltf);
   // Tambahkan TOP (jangka waktu pembayaran) ke invoice_date_obj
   $duedate_obj = clone $tgltf_date_obj; // Duplikasi objek tanggal tgltf_date_obj
   $duedate_obj->modify("+" . $top . " days"); // Tambahkan TOP (jangka waktu pembayaran) ke objek tanggal
   $duedate = $duedate_obj->format('Y-m-d');
   // Update status invoice menjadi "Sudah TF"
   $queryUpdateStatus = "UPDATE invoice SET status = 'Sudah TF' WHERE idinvoice = $idinvoice";
   $resultUpdateStatus = mysqli_query($conn, $queryUpdateStatus);

   if ($resultUpdateStatus) {
      // Update tanggal transfer (termasuk jika kolom 'tgltf' bernilai null)
      $queryUpdateTglTF = "UPDATE invoice SET tgltf = '$tgltf' WHERE idinvoice = $idinvoice";
      $resultUpdateTglTF = mysqli_query($conn, $queryUpdateTglTF);

      if ($resultUpdateTglTF) {
         // Update duedate column
         $queryUpdateDuedate = "UPDATE invoice SET duedate = '$duedate' WHERE idinvoice = $idinvoice";
         $resultUpdateDuedate = mysqli_query($conn, $queryUpdateDuedate);

         if ($resultUpdateDuedate) {
            header("location: invoice.php");
         } else {
            echo "Gagal mengupdate duedate.";
         }
      } else {
         echo "Gagal mengupdate tanggal transfer.";
      }
   } else {
      echo "Gagal mengupdate status.";
   }
}