<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['idreturjual'])) {
  $idreturjual = $_GET['idreturjual'];

  // Delete data dari tabel returjualdetail
  $query_delete_returjualdetail = "DELETE FROM returjualdetail WHERE idreturjual = ?";
  $stmt_delete_returjualdetail = $conn->prepare($query_delete_returjualdetail);
  $stmt_delete_returjualdetail->bind_param("i", $idreturjual);
  $stmt_delete_returjualdetail->execute();
  $stmt_delete_returjualdetail->close();

  // Delete data dari tabel returjual
  $query_delete_returjual = "DELETE FROM returjual WHERE idreturjual = ?";
  $stmt_delete_returjual = $conn->prepare($query_delete_returjual);
  $stmt_delete_returjual->bind_param("i", $idreturjual);
  $stmt_delete_returjual->execute();
  $stmt_delete_returjual->close();
}

header("location: index.php"); // Redirect to the list page
