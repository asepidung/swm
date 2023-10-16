<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "sonumber.php";

if (isset($_POST['submit'])) {
   $sonumber =  $kodeauto;
   $idcustomer = $_POST['idcustomer'];
   $deliverydate = $_POST['deliverydate'];
   $po = $_POST['po'];
   $alamat = $_POST['alamat'];
   $note = $_POST['note'];
   $progress = 'Penyiapan';
   $idusers = $_SESSION['idusers'];

   $query_so = "INSERT INTO salesorder (sonumber, idcustomer, deliverydate, po, alamat, note, progress, idusers) VALUES (?,?,?,?,?,?,?,?)";
   $stmt_so = $conn->prepare($query_so);
   $stmt_so->bind_param("sisssssi", $sonumber, $idcustomer, $deliverydate, $po, $alamat, $note, $progress, $idusers);
   $stmt_so->execute();

   $last_id = $stmt_so->insert_id;

   $idbarang = $_POST['idbarang'];
   $weight = $_POST['weight'];
   $price = $_POST['price'];
   $notes = $_POST['notes'];

   $query_sodetail = "INSERT INTO salesorderdetail (idso, idbarang, weight, price, notes) VALUES (?,?,?,?,?)";
   $stmt_sodetail = $conn->prepare($query_sodetail);

   for ($i = 0; $i < count($idbarang); $i++) {
      $stmt_sodetail->bind_param("iiiis", $last_id, $idbarang[$i], $weight[$i], $price[$i], $notes[$i]);
      $stmt_sodetail->execute();
   }

   $stmt_sodetail->close();
   $stmt_so->close();
   $conn->close();

   header("location: index.php");
}
