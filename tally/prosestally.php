<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
if (isset($_POST['submit'])) {
   $idso = $_POST['idso'];
   $deliverydate = $_POST['deliverydate'];
   $idcustomer = $_POST['idcustomer'];
   $po = $_POST['po'];
   $sonumber = $_POST['sonumber'];
   $notally  = $_POST['notally'];

   // Buat query INSERT
   $query_tally = "INSERT INTO tally (idso, sonumber, notally, deliverydate, idcustomer, po) VALUES (?, ?, ?, ?, ?, ?)";
   $stmt_tally = $conn->prepare($query_tally);
   $stmt_tally->bind_param("isssis", $idso, $sonumber, $notally, $deliverydate, $idcustomer, $po);
   $stmt_tally->execute();

   // Dapatkan idtally yang baru saja diinput
   $last_id = mysqli_insert_id($conn);

   $updateSql = "UPDATE salesorder SET progress = 'On Process' WHERE idso = '$idso'";
   mysqli_query($conn, $updateSql);

   $stmt_tally->close();
   $conn->close();

   // Redirect ke halaman tallydetail.php dengan idtally baru
   header("location: tallydetail.php?id=$last_id&stat=ready");
}
