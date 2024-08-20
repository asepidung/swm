<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";

if (isset($_GET['id']) && isset($_GET['idso'])) {
   $idtally = intval($_GET['id']);
   $idso = intval($_GET['idso']);

   // Cek apakah idtally sudah ada di tabel do
   $stmt_check_do = $conn->prepare("SELECT COUNT(*) FROM do WHERE idtally = ?");
   if (!$stmt_check_do) {
      die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
   }
   $stmt_check_do->bind_param("i", $idtally);
   $stmt_check_do->execute();
   $stmt_check_do->bind_result($do_count);
   $stmt_check_do->fetch();
   $stmt_check_do->close();

   if ($do_count > 0) {
      // Jika idtally ditemukan di tabel do, berikan peringatan dan hentikan proses
      echo "<script>alert('DO Sudah Terbit Silahkan Hapus Do Terlebih Dahulu Sebelum Melakukan Perubahan'); window.location.href = 'index.php';</script>";
      exit();
   } else {
      // Lanjutkan proses Unapprove jika idtally belum ada di tabel do
      // Prepare statement untuk update tabel tally
      $stmt = $conn->prepare("UPDATE tally SET stat = ? WHERE idtally = ?");
      if (!$stmt) {
         die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
      }

      $status = "";
      $stmt->bind_param("si", $status, $idtally);

      if ($stmt->execute()) {
         // Prepare statement untuk update tabel salesorder
         $stmt_salesorder = $conn->prepare("UPDATE salesorder SET progress = ? WHERE idso = ?");
         if (!$stmt_salesorder) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
         }

         $progress = "On Process";
         $stmt_salesorder->bind_param("si", $progress, $idso);

         if ($stmt_salesorder->execute()) {
            // Redirect ke halaman index.php setelah kedua update berhasil
            header("location: index.php");
            exit();
         } else {
            echo "Error updating salesorder: " . $stmt_salesorder->error;
            exit();
         }
      } else {
         echo "Error updating tally: " . $stmt->error;
         exit();
      }
   }
} else {
   echo "ID tally atau ID salesorder tidak ditemukan.";
   exit();
}

$conn->close();
