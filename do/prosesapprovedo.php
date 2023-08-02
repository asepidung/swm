<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['approve'])) {
   $iddo = $_POST['iddo'];
   $donumber = $_POST['donumber'];
   $deliverydate = $_POST['deliverydate'];
   $idcustomer = $_POST['idcustomer'];
   $po = $_POST['po'];
   $driver = $_POST['driver'];
   $plat = $_POST['plat'];
   $xbox = $_POST['xbox'];
   $xweight = $_POST['xweight'];
   $status = "Approved";
   $note = $_POST['note'];
   $idusers = $_SESSION['idusers'];

   $query_do = "INSERT INTO doreceipt (iddo, donumber, deliverydate, idcustomer, po, driver, plat, note, xbox, xweight, status, idusers) 
               VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
   $stmt_do = $conn->prepare($query_do);
   $stmt_do->bind_param("ississssidsi", $iddo, $donumber, $deliverydate, $idcustomer, $po, $driver, $plat, $note, $xbox, $xweight, $status, $idusers);
   $stmt_do->execute();

   $last_id = $stmt_do->insert_id;

   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $box = $_POST['box'];
   $weight = $_POST['weight'];
   $notes = $_POST['notes'];

   $query_dodetail = "INSERT INTO doreceiptdetail (iddoreceipt, idgrade, idbarang, box, weight, notes) VALUES (?,?,?,?,?,?)";
   $stmt_dodetail = $conn->prepare($query_dodetail);

   for ($i = 0; $i < count($idgrade); $i++) {
      $stmt_dodetail->bind_param("iiiids", $last_id, $idgrade[$i], $idbarang[$i], $box[$i], $weight[$i], $notes[$i]);
      $stmt_dodetail->execute();
   }

   $stmt_dodetail->close();
   $stmt_do->close();
   $conn->close();

   header("location: do.php");
}
