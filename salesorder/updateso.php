<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $idso = $_POST["idso"];
   $idcustomer = $_POST["idcustomer"];
   $deliverydate = $_POST["deliverydate"];
   $po = $_POST["po"];
   $alamat = $_POST["alamat"];
   $note = $_POST["note"];
   $idbarang = $_POST["idbarang"];
   $weight = $_POST["weight"];
   $price = $_POST["price"];
   $notes = $_POST["notes"];

   // Lakukan validasi data sesuai kebutuhan Anda di sini

   // Update data salesorder
   $updateSalesOrderQuery = "UPDATE salesorder SET idcustomer = $idcustomer, deliverydate = '$deliverydate', po = '$po', alamat = '$alamat', note = '$note' WHERE idso = $idso";
   if (mysqli_query($conn, $updateSalesOrderQuery)) {
      // Hapus item salesorderdetail yang sudah ada
      $deleteSalesOrderDetailQuery = "DELETE FROM salesorderdetail WHERE idso = $idso";
      mysqli_query($conn, $deleteSalesOrderDetailQuery);

      // Tambahkan item salesorderdetail yang baru
      $weighttotal = 0;
      for ($i = 0; $i < count($idbarang); $i++) {
         $insertSalesOrderDetailQuery = "INSERT INTO salesorderdetail (idso, idbarang, weight, price, notes) VALUES ($idso, " . $idbarang[$i] . ", " . $weight[$i] . ", " . $price[$i] . ", '" . $notes[$i] . "')";
         mysqli_query($conn, $insertSalesOrderDetailQuery);
         $weighttotal += $weight[$i];
      }

      // Update data plandev
      $updatePlandevQuery = "UPDATE plandev SET idcustomer = $idcustomer, plandelivery = '$deliverydate', weight = $weighttotal  WHERE idso = $idso";
      mysqli_query($conn, $updatePlandevQuery);

      echo "Data Sales Order berhasil diperbarui, dan data PlanDev juga diperbarui.";
   } else {
      echo "Gagal memperbarui data Sales Order: " . mysqli_error($conn);
   }
}

header("location: index.php");
