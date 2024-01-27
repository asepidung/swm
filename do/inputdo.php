<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "donumber.php";

if (isset($_POST['submit'])) {
   $donumber =  $kodeauto;
   $deliverydate = $_POST['deliverydate'];
   $idcustomer = $_POST['idcustomer'];
   // $alamat = $_POST['alamat'];
   $po = $_POST['po'];
   $driver = $_POST['driver'];
   $plat = $_POST['plat'];
   $xbox = $_POST['xbox'];
   $xweight = $_POST['xweight'];
   $status = "Unapproved";
   $note = $_POST['note'];
   $idso = $_POST['idso'];
   $idtally = $_POST['idtally'];
   $idusers = $_SESSION['idusers'];

   $query_do = "INSERT INTO do (donumber, idso, idtally, deliverydate, idcustomer, po, driver, plat, note, xbox, xweight, status, idusers) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
   $stmt_do = $conn->prepare($query_do);
   $stmt_do->bind_param("siisissssidsi", $donumber, $idso, $idtally, $deliverydate, $idcustomer, $po, $driver, $plat, $note, $xbox, $xweight, $status, $idusers);
   if ($stmt_do->execute()) {
      // Eksekusi berhasil
      $last_id = $stmt_do->insert_id;
   } else {
      // Eksekusi gagal, tampilkan pesan kesalahan
      echo "Error: " . $stmt_do->error;
   }

   $idbarang = $_POST['idbarang'];
   $box = $_POST['box'];
   $weight = $_POST['weight'];
   $notes = $_POST['notes'];

   $query_dodetail = "INSERT INTO dodetail (iddo, idbarang, box, weight, notes) VALUES (?,?,?,?,?)";
   $stmt_dodetail = $conn->prepare($query_dodetail);

   for ($i = 0; $i < count($idbarang); $i++) {
      $stmt_dodetail->bind_param("iiids", $last_id, $idbarang[$i], $box[$i], $weight[$i], $notes[$i]);
      $stmt_dodetail->execute();
   }

   $query_update_salesorder = "UPDATE salesorder SET progress = 'On Delivery' WHERE idso = ?";
   $stmt_update_salesorder = $conn->prepare($query_update_salesorder);
   $stmt_update_salesorder->bind_param("i", $idso);
   $stmt_update_salesorder->execute();
   $stmt_update_salesorder->close();
   $stmt_dodetail->close();
   $stmt_do->close();
   $conn->close();

   header("location: do.php");
}
