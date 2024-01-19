<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}
require "../konak/conn.php";

if (isset($_GET['id'])) {
   $id = $_GET['id'];

   // Hapus data dari tabel stock berdasarkan id
   $deleteSql = "DELETE FROM stock WHERE id = ?";
   $deleteStmt = $conn->prepare($deleteSql);
   $deleteStmt->bind_param("i", $id);
   $deleteStmt->execute();
   $deleteStmt->close();

   // Kembalikan ke halaman index.php
   header("Location: index.php");
   exit();
} else {
   echo "Parameter id tidak valid.";
   exit();
}
