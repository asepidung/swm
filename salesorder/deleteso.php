<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['idso'])) {
   $idsalesorder = $_GET['idso'];

   // Delete data dari tabel salesorderdetail
   $query_delete_salesorderdetail = "DELETE FROM salesorderdetail WHERE idso = ?";
   $stmt_delete_salesorderdetail = $conn->prepare($query_delete_salesorderdetail);
   $stmt_delete_salesorderdetail->bind_param("i", $idsalesorder);
   $stmt_delete_salesorderdetail->execute();
   $stmt_delete_salesorderdetail->close();

   // Delete data dari tabel salesorder
   $query_delete_salesorder = "DELETE FROM salesorder WHERE idso = ?";
   $stmt_delete_salesorder = $conn->prepare($query_delete_salesorder);
   $stmt_delete_salesorder->bind_param("i", $idsalesorder);
   $stmt_delete_salesorder->execute();
   $stmt_delete_salesorder->close();
}

header("location: index.php"); // Redirect to the list page
