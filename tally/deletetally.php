<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['id'])) {
   $idtally = $_GET['id'];

   // Delete data dari tabel tallydetail
   $query_delete_tallydetail = "DELETE FROM tallydetail WHERE idtally = ?";
   $stmt_delete_tallydetail = $conn->prepare($query_delete_tallydetail);
   $stmt_delete_tallydetail->bind_param("i", $idtally);
   $stmt_delete_tallydetail->execute();
   $stmt_delete_tallydetail->close();

   // Delete data dari tabel tally
   $query_delete_tally = "DELETE FROM tally WHERE idtally = ?";
   $stmt_delete_tally = $conn->prepare($query_delete_tally);
   $stmt_delete_tally->bind_param("i", $idtally);
   $stmt_delete_tally->execute();
   $stmt_delete_tally->close();
}

header("location: index.php?stat=deleted"); // Redirect to the list page
