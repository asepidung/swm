<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("Location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Mendapatkan data dari form
   $idgr = isset($_POST['idgr']) ? intval($_POST['idgr']) : 0;
   $receivedate = isset($_POST['receivedate']) ? $_POST['receivedate'] : '';
   $idnumber = isset($_POST['idnumber']) ? $_POST['idnumber'] : '';
   $note = isset($_POST['note']) ? $_POST['note'] : '';

   // Validasi input
   if ($idgr > 0 && !empty($receivedate)) {
      // Persiapan query untuk update data
      $query = "UPDATE gr SET receivedate = ?, idnumber = ?, note = ? WHERE idgr = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssi", $receivedate, $idnumber, $note, $idgr);

      if ($stmt->execute()) {
         // Jika update berhasil, redirect ke halaman yang sesuai, misalnya halaman utama atau halaman detail
         header("Location: index.php");
         exit();
      } else {
         // Jika terjadi kesalahan, tampilkan pesan kesalahan
         echo "<div class='alert alert-danger'>Error updating record: " . $stmt->error . "</div>";
      }

      $stmt->close();
   } else {
      echo "<div class='alert alert-danger'>Please fill in all required fields.</div>";
   }
}

$conn->close();
