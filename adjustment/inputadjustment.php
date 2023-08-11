<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
   $noadjustment = $_POST['noadjustment'];
   $tgladjustment = $_POST['tgladjustment'];
   $xweight = $_POST['xweight'];
   $eventadjustment = $_POST['eventadjustment'];
   $idusers = $_SESSION['idusers'];

   $query_adjustment = "INSERT INTO adjustment (noadjustment, tgladjustment, xweight, eventadjustment, idusers) VALUES (?,?,?,?,?)";
   $stmt_adjustment = $conn->prepare($query_adjustment);
   $stmt_adjustment->bind_param("ssdsi", $noadjustment, $tgladjustment, $xweight, $eventadjustment, $idusers);
   $stmt_adjustment->execute();

   $last_id = $stmt_adjustment->insert_id;

   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $weight = $_POST['weight'];
   $notes = $_POST['notes'];

   $query_adjustmentdetail = "INSERT INTO adjustmentdetail (idadjustment, idgrade, idbarang, weight, notes) VALUES (?,?,?,?,?)";
   $stmt_adjustmentdetail = $conn->prepare($query_adjustmentdetail);

   for ($i = 0; $i < count($idgrade); $i++) {
      $stmt_adjustmentdetail->bind_param("iiids", $last_id, $idgrade[$i], $idbarang[$i], $weight[$i], $notes[$i]);
      $stmt_adjustmentdetail->execute();
   }

   $stmt_adjustmentdetail->close();
   $stmt_adjustment->close();
   $conn->close();

   header("location: index.php");
}
