<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";

if (isset($_POST['submit'])) {
   $idso = $_POST['idso'];
   $deliverydate = $_POST['deliverydate'];
   $idcustomer = $_POST['idcustomer'];
   $po = $_POST['po'];
   $sonumber = $_POST['sonumber'];
   $notally  = $_POST['notally'];
   $iduser = $_SESSION['idusers']; // Ambil ID user dari sesi yang aktif

   // Cek apakah idso sudah ada di tabel tally
   $check_query = "SELECT COUNT(*) as count FROM tally WHERE idso = ?";
   if ($stmt_check = $conn->prepare($check_query)) {
      $stmt_check->bind_param("i", $idso);
      $stmt_check->execute();
      $result_check = $stmt_check->get_result();
      $row_check = $result_check->fetch_assoc();

      if ($row_check['count'] > 0) {
         // Jika idso sudah ada, kembalikan ke halaman index.php dengan pesan peringatan
         $stmt_check->close();
         $conn->close();
         header("location: index.php?error=Form Tally Sudah Ada");
         exit();
      }
      $stmt_check->close();
   } else {
      echo "Error preparing check query: " . $conn->error;
      exit();
   }

   // Buat query INSERT
   $query_tally = "INSERT INTO tally (idso, sonumber, notally, deliverydate, idcustomer, po) VALUES (?, ?, ?, ?, ?, ?)";
   if ($stmt_tally = $conn->prepare($query_tally)) {
      $stmt_tally->bind_param("isssis", $idso, $sonumber, $notally, $deliverydate, $idcustomer, $po);
      if ($stmt_tally->execute()) {
         // Dapatkan idtally yang baru saja diinput
         $last_id = mysqli_insert_id($conn);

         // Update progress di tabel salesorder
         $updateSql = "UPDATE salesorder SET progress = 'On Process' WHERE idso = '$idso'";
         if (mysqli_query($conn, $updateSql)) {
            // Catat log aktivitas setelah pembuatan data tally berhasil
            $event = "Buat Data Tally";
            $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, NOW())";
            $logStmt = $conn->prepare($logQuery);
            $logStmt->bind_param('iss', $iduser, $event, $notally);
            $logStmt->execute();

            $stmt_tally->close();
            $conn->close();

            // Redirect ke halaman tallydetail.php dengan idtally baru
            header("location: tallydetail.php?id=$last_id&stat=ready");
            exit();
         } else {
            echo "Error updating record: " . mysqli_error($conn);
         }
      } else {
         echo "Error executing query: " . $stmt_tally->error;
      }
      $stmt_tally->close();
   } else {
      echo "Error preparing insert query: " . $conn->error;
   }
   $conn->close();
}
