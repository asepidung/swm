<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['idinbound'])) {
  $idinbound = $_GET['idinbound'];

  // Delete data dari tabel inbounddetail
  $query_delete_inbounddetail = "DELETE FROM inbounddetail WHERE idinbound = ?";
  $stmt_delete_inbounddetail = $conn->prepare($query_delete_inbounddetail);
  $stmt_delete_inbounddetail->bind_param("i", $idinbound);
  $stmt_delete_inbounddetail->execute();
  $stmt_delete_inbounddetail->close();

  // Delete data dari tabel inbound
  $query_delete_inbound = "DELETE FROM inbound WHERE idinbound = ?";
  $stmt_delete_inbound = $conn->prepare($query_delete_inbound);
  $stmt_delete_inbound->bind_param("i", $idinbound);
  $stmt_delete_inbound->execute();
  $stmt_delete_inbound->close();
}

header("location: index.php"); // Redirect to the list page
