<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit;
}
require "../konak/conn.php";

$idst = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idst > 0) {
   // Start a transaction
   mysqli_begin_transaction($conn);

   try {
      // Delete from stocktakedetail
      $query_detail = "DELETE FROM stocktakedetail WHERE idst = ?";
      $stmt_detail = mysqli_prepare($conn, $query_detail);

      if ($stmt_detail) {
         mysqli_stmt_bind_param($stmt_detail, "i", $idst);
         mysqli_stmt_execute($stmt_detail);
         mysqli_stmt_close($stmt_detail);
      } else {
         throw new Exception("Failed to prepare the delete statement for stocktakedetail.");
      }

      // Delete from stocktake
      $query_stocktake = "DELETE FROM stocktake WHERE idst = ?";
      $stmt_stocktake = mysqli_prepare($conn, $query_stocktake);

      if ($stmt_stocktake) {
         mysqli_stmt_bind_param($stmt_stocktake, "i", $idst);
         mysqli_stmt_execute($stmt_stocktake);
         mysqli_stmt_close($stmt_stocktake);
      } else {
         throw new Exception("Failed to prepare the delete statement for stocktake.");
      }

      // Commit the transaction
      mysqli_commit($conn);
      $_SESSION['message'] = "Stock taking record and its details deleted successfully.";
   } catch (Exception $e) {
      // Rollback the transaction
      mysqli_rollback($conn);
      $_SESSION['error'] = $e->getMessage();
   }
} else {
   $_SESSION['error'] = "Invalid ID provided.";
}

header("location: index.php");
exit;
