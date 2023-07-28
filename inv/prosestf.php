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

   // Update status invoice menjadi "Sudah TF"
   $queryUpdateStatus = "UPDATE invoice SET status = 'Sudah TF' WHERE idinvoice = $idinvoice";
   $resultUpdateStatus = mysqli_query($conn, $queryUpdateStatus);

   if ($resultUpdateStatus) {
      // Update tanggal transfer (termasuk jika kolom 'tgltf' bernilai null)
      $queryUpdateTglTF = "UPDATE invoice SET tgltf = '$tgltf' WHERE idinvoice = $idinvoice";
      $resultUpdateTglTF = mysqli_query($conn, $queryUpdateTglTF);

      if ($resultUpdateTglTF) {
         echo "Data berhasil diperbarui.";
      } else {
         echo "Gagal mengupdate tanggal transfer.";
      }
   } else {
      echo "Gagal mengupdate status.";
   }
}
