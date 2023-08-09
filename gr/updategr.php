<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $idgr = $_POST['idgr'];
   $receivedate = $_POST['receivedate'];
   $idsupplier = $_POST['idsupplier'];
   $idnumber = $_POST['idnumber'];
   $xbox = $_POST['xbox'];
   $xweight = $_POST['xweight'];
   $note = $_POST['note'];

   // Update data ke tabel gr
   $query_update_gr = "UPDATE gr SET receivedate = ?, idsupplier = ?, idnumber = ?, xbox = ?, xweight = ?, note = ?
                       WHERE idgr = ?";
   $stmt_update_gr = $conn->prepare($query_update_gr);
   $stmt_update_gr->bind_param("sisidsi", $receivedate, $idsupplier, $idnumber, $xbox, $xweight, $note, $idgr);
   $stmt_update_gr->execute();
   $stmt_update_gr->close();

   // Update data ke tabel grdetail
   $idgrades = $_POST['idgrade'];
   $idbarangs = $_POST['idbarang'];
   $boxes = $_POST['box'];
   $weights = $_POST['weight'];
   $notes = $_POST['notes'];

   for ($i = 0; $i < count($idgrades); $i++) {
      $idgrade = $idgrades[$i];
      $idbarang = $idbarangs[$i];
      $box = $boxes[$i];
      $weight = $weights[$i];
      $note = $notes[$i];

      $query_update_grdetail = "UPDATE grdetail SET idgrade = ?, idbarang = ?, box = ?, weight = ?, notes = ?
                                WHERE idgrdetail = ?";
      $stmt_update_grdetail = $conn->prepare($query_update_grdetail);
      $stmt_update_grdetail->bind_param("iiidsi", $idgrade, $idbarang, $box, $weight, $note, $idgrdetail);
      $stmt_update_grdetail->execute();
      $stmt_update_grdetail->close();
   }

   header("location: index.php"); // Redirect to the list page
}

// ...
