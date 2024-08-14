<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";
include "grnumber.php";

if (isset($_POST['submit'])) {
   $deliveryat = $_POST['deliveryat'];
   $idsupplier = $_POST['idsupplier'];
   $idnumber = $_POST['idnumber'];
   $note = $_POST['note'];
   $idpo = $_POST['idpoproduct']; // Pastikan nama kolom ini benar di tabel gr
   $idusers = $_SESSION['idusers'];

   // Query INSERT untuk tabel gr
   $query_gr = "INSERT INTO gr (grnumber, receivedate, idsupplier, idnumber, note, iduser, idpo) VALUES (?,?,?,?,?,?,?)";
   $stmt_gr = $conn->prepare($query_gr);

   // Tambahkan pemeriksaan error untuk memastikan prepare berhasil
   if ($stmt_gr === false) {
      die("Error preparing statement: " . $conn->error);
   }

   // Bind parameter dan eksekusi
   $stmt_gr->bind_param("ssissii", $gr, $deliveryat, $idsupplier, $idnumber, $note, $idusers, $idpo);

   if ($stmt_gr->execute()) {
      // Setelah berhasil melakukan insert, lakukan update pada tabel poproduct
      $query_update = "UPDATE poproduct SET stat = 'BTB' WHERE idpoproduct = ?";
      $stmt_update = $conn->prepare($query_update);

      if ($stmt_update === false) {
         die("Error preparing update statement: " . $conn->error);
      }

      $stmt_update->bind_param("i", $idpo);

      if ($stmt_update->execute()) {
         // Redirect ke halaman index jika berhasil
         header("location: index.php");
         exit();
      } else {
         echo "Error executing update statement: " . $stmt_update->error;
      }

      $stmt_update->close();
   } else {
      echo "Error executing insert statement: " . $stmt_gr->error;
   }

   $stmt_gr->close();
   $conn->close();
}
