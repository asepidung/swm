<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['idso'])) {
   $idsalesorder = $_GET['idso'];

   // Hapus data dari tabel plandev terlebih dahulu
   $query_delete_plandev = "DELETE FROM plandev WHERE idso = ?";
   $stmt_delete_plandev = $conn->prepare($query_delete_plandev);
   $stmt_delete_plandev->bind_param("i", $idsalesorder);
   $stmt_delete_plandev->execute();
   $stmt_delete_plandev->close();

   // Hapus data dari tabel salesorderdetail
   $query_delete_salesorderdetail = "DELETE FROM salesorderdetail WHERE idso = ?";
   $stmt_delete_salesorderdetail = $conn->prepare($query_delete_salesorderdetail);
   $stmt_delete_salesorderdetail->bind_param("i", $idsalesorder);
   $stmt_delete_salesorderdetail->execute();
   $stmt_delete_salesorderdetail->close();

   // Hapus data dari tabel salesorder
   $query_delete_salesorder = "DELETE FROM salesorder WHERE idso = ?";
   $stmt_delete_salesorder = $conn->prepare($query_delete_salesorder);
   $stmt_delete_salesorder->bind_param("i", $idsalesorder);
   $stmt_delete_salesorder->execute();
   $stmt_delete_salesorder->close();
}

header("location: index.php"); // Redirect to the list page
