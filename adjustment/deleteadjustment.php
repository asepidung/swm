<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

if (isset($_GET['idadjustment'])) {
   require "../konak/conn.php";

   $adjustment_id = $_GET['idadjustment'];

   // Hapus data di tabel adjustmentdetail yang terkait
   $delete_adjustmentdetail_query = "DELETE FROM adjustmentdetail WHERE idadjustment = $adjustment_id";
   $delete_adjustmentdetail_result = mysqli_query($conn, $delete_adjustmentdetail_query);

   if ($delete_adjustmentdetail_result) {
      // Hapus data di tabel adjustment
      $delete_adjustment_query = "DELETE FROM adjustment WHERE idadjustment = $adjustment_id";
      $delete_adjustment_result = mysqli_query($conn, $delete_adjustment_query);

      if ($delete_adjustment_result) {
         // Redirect ke halaman index.php atau halaman lain yang sesuai
         header("location: index.php");
         exit();
      } else {
         // Handle kesalahan saat menghapus data di tabel adjustment
         echo "Error: " . mysqli_error($conn);
      }
   } else {
      // Handle kesalahan saat menghapus data di tabel adjustmentdetail
      echo "Error: " . mysqli_error($conn);
   }

   mysqli_close($conn);
} else {
   // Redirect jika parameter ID tidak ada
   header("location: index.php");
   exit();
}
