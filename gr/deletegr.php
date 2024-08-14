<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";

if (isset($_GET['idgr']) && isset($_GET['idpo'])) {
   $idgr = $_GET['idgr'];
   $idpo = $_GET['idpo'];

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

   // Update kolom 'stat' di tabel poproduct
   $query_update_poproduct = "UPDATE poproduct SET stat = 'Waiting' WHERE idpoproduct = ?";
   $stmt_update_poproduct = $conn->prepare($query_update_poproduct);
   $stmt_update_poproduct->bind_param("i", $idpo);
   $stmt_update_poproduct->execute();
   $stmt_update_poproduct->close();
}

header("location: index.php"); // Redirect to the list page
exit();
