<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   require "../konak/conn.php";

   // Ambil data dari form
   $adjustment_id = $_POST['idadjustment'];
   $noadjustment = $_POST['noadjustment'];
   $tgladjustment = $_POST['tgladjustment'];
   $eventadjustment = $_POST['eventadjustment'];

   // Update data di tabel adjustment
   $update_adjustment_query = "UPDATE adjustment SET noadjustment = '$noadjustment', tgladjustment = '$tgladjustment', eventadjustment = '$eventadjustment' WHERE idadjustment = $adjustment_id";
   $update_adjustment_result = mysqli_query($conn, $update_adjustment_query);

   if ($update_adjustment_result) {
      // Hapus data di tabel adjustmentdetail yang terkait
      $delete_adjustmentdetail_query = "DELETE FROM adjustmentdetail WHERE idadjustment = $adjustment_id";
      $delete_adjustmentdetail_result = mysqli_query($conn, $delete_adjustmentdetail_query);

      // Insert data baru ke tabel adjustmentdetail
      $idgrade = $_POST['idgrade'];
      $idbarang = $_POST['idbarang'];
      $weight = $_POST['weight'];
      $notes = $_POST['notes'];

      for ($i = 0; $i < count($idgrade); $i++) {
         $insert_adjustmentdetail_query = "INSERT INTO adjustmentdetail (idadjustment, idgrade, idbarang, weight, notes) VALUES ($adjustment_id, {$idgrade[$i]}, {$idbarang[$i]}, {$weight[$i]}, '{$notes[$i]}')";
         $insert_adjustmentdetail_result = mysqli_query($conn, $insert_adjustmentdetail_query);
      }

      if ($insert_adjustmentdetail_result) {
         // Redirect ke halaman daftaradjustment.php atau halaman lain yang sesuai
         header("location: index.php");
         exit();
      } else {
         // Handle kesalahan saat memasukkan data baru ke tabel adjustmentdetail
         echo "Error: " . mysqli_error($conn);
      }
   } else {
      // Handle kesalahan saat mengupdate data di tabel adjustment
      echo "Error: " . mysqli_error($conn);
   }

   mysqli_close($conn);
}
