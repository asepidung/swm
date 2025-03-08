<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "sonumber.php";

if (isset($_POST['submit'])) {
   $sonumber =  $kodeauto;
   $idcustomer = $_POST['idcustomer'];
   $deliverydate = $_POST['deliverydate'];
   $po = $_POST['po'];
   $alamat = $_POST['alamat'];
   $note = $_POST['note'];
   $progress = 'Waiting';
   $idusers = $_SESSION['idusers'];

   $query_so = "INSERT INTO salesorder (sonumber, idcustomer, deliverydate, po, alamat, note, progress, idusers) VALUES (?,?,?,?,?,?,?,?)";
   $stmt_so = $conn->prepare($query_so);
   $stmt_so->bind_param("sisssssi", $sonumber, $idcustomer, $deliverydate, $po, $alamat, $note, $progress, $idusers);
   $stmt_so->execute();

   // Get the last inserted ID
   $last_id = $stmt_so->insert_id;

   // Retrieve data from the form
   $idbarang = $_POST['idbarang'];
   $weight = $_POST['weight'];
   $discount = $_POST['discount'];
   $notes = $_POST['notes'];

   // Remove commas from the 'price' array
   $price = $_POST['price'];
   $price = array_map(function ($value) {
      return str_replace(',', '', $value);
   }, $price);

   // Insert data into 'salesorderdetail' table
   $weighttotal = 0;
   $query_sodetail = "INSERT INTO salesorderdetail (idso, idbarang, weight, price, discount, notes) VALUES (?,?,?,?,?,?)";
   $stmt_sodetail = $conn->prepare($query_sodetail);

   for ($i = 0; $i < count($idbarang); $i++) {
      $stmt_sodetail->bind_param("iiiiis", $last_id, $idbarang[$i], $weight[$i], $price[$i], $discount[$i], $notes[$i]);
      $stmt_sodetail->execute();
      $weighttotal += $weight[$i];
   }
   $query_plandev = "INSERT INTO plandev (plandelivery, idcustomer, weight, idso) VALUES (?,?,?,?)";
   $stmt_plandev = $conn->prepare($query_plandev);
   $stmt_plandev->bind_param("siii", $deliverydate, $idcustomer, $weighttotal, $last_id);
   $stmt_plandev->execute();

   // Insert log activity into logactivity table
   $event = "Buat Sales Order";
   $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                VALUES (?, ?, ?, NOW())";
   $stmt_log = $conn->prepare($logQuery);
   $stmt_log->bind_param("iss", $idusers, $sonumber, $event);
   $stmt_log->execute();

   // Close prepared statements
   $stmt_plandev->close();
   $stmt_sodetail->close();
   $stmt_so->close();
   $stmt_log->close();
   $conn->close();

   header("location: index.php");
   exit();
}
