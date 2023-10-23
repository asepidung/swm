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

   $updateSql = "UPDATE salesorder SET progress = 'On Process' WHERE idso = '$idso'";
   mysqli_query($conn, $updateSql);

   $stmt_tally->close();
   $conn->close();

   // header("location: tallydetail.php?idtally=$last_id");
   header("location: index.php");
}
