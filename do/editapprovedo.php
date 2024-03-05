<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['iddo'])) {
   $iddo = $_GET['iddo'];

   // Delete data dari tabel doreceiptdetail berdasarkan $iddo
   $query_delete_doreceiptdetail = "DELETE FROM doreceiptdetail WHERE iddoreceipt IN (SELECT iddoreceipt FROM doreceipt WHERE iddo = ?)";
   $stmt_delete_doreceiptdetail = $conn->prepare($query_delete_doreceiptdetail);
   $stmt_delete_doreceiptdetail->bind_param("i", $iddo);
   $stmt_delete_doreceiptdetail->execute();
   $stmt_delete_doreceiptdetail->close();

   // Delete data dari tabel doreceipt berdasarkan $iddo
   $query_delete_doreceipt = "DELETE FROM doreceipt WHERE iddo = ?";
   $stmt_delete_doreceipt = $conn->prepare($query_delete_doreceipt);
   $stmt_delete_doreceipt->bind_param("i", $iddo);
   $stmt_delete_doreceipt->execute();
   $stmt_delete_doreceipt->close();

   // Update data di tabel do kolom status menjadi Unapproved
   $query_update_do = "UPDATE do SET status = 'Unapproved' WHERE iddo = ?";
   $stmt_update_do = $conn->prepare($query_update_do);
   $stmt_update_do->bind_param("i", $iddo);
   $stmt_update_do->execute();
   $stmt_update_do->close();
}

header("location: do.php"); // Redirect to the list page
