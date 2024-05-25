<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";

if (isset($_GET['id'])) {
   $idtally = intval($_GET['id']);

   // Prepare statement untuk update tabel tally
   $stmt = $conn->prepare("UPDATE tally SET stat = ? WHERE idtally = ?");
   if (!$stmt) {
      die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
   }

   $status = "Approved";
   $stmt->bind_param("si", $status, $idtally);

   if ($stmt->execute()) {
      // Redirect ke halaman index.php setelah update berhasil
      header("location: index.php");
      exit();
   } else {
      echo "Error: " . $stmt->error;
      exit();
   }
} else {
   echo "ID tally tidak ditemukan.";
   exit();
}

$conn->close();
