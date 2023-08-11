<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['idoutbound'])) {
  $idoutbound = $_GET['idoutbound'];

  // Delete data dari tabel outbounddetail
  $query_delete_outbounddetail = "DELETE FROM outbounddetail WHERE idoutbound = ?";
  $stmt_delete_outbounddetail = $conn->prepare($query_delete_outbounddetail);
  $stmt_delete_outbounddetail->bind_param("i", $idoutbound);
  $stmt_delete_outbounddetail->execute();
  $stmt_delete_outbounddetail->close();

  // Delete data dari tabel outbound
  $query_delete_outbound = "DELETE FROM outbound WHERE idoutbound = ?";
  $stmt_delete_outbound = $conn->prepare($query_delete_outbound);
  $stmt_delete_outbound->bind_param("i", $idoutbound);
  $stmt_delete_outbound->execute();
  $stmt_delete_outbound->close();
}

header("location: index.php"); // Redirect to the list page
