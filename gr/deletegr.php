<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['idgr'])) {
   $idgr = $_GET['idgr'];

   // Delete data dari tabel grdetail
   $query_delete_grdetail = "DELETE FROM grdetail WHERE idgr = ?";
   $stmt_delete_grdetail = $conn->prepare($query_delete_grdetail);
   $stmt_delete_grdetail->bind_param("i", $idgr);
   $stmt_delete_grdetail->execute();
   $stmt_delete_grdetail->close();

   // Delete data dari tabel gr
   $query_delete_gr = "DELETE FROM gr WHERE idgr = ?";
   $stmt_delete_gr = $conn->prepare($query_delete_gr);
   $stmt_delete_gr->bind_param("i", $idgr);
   $stmt_delete_gr->execute();
   $stmt_delete_gr->close();
}

header("location: index.php"); // Redirect to the list page
