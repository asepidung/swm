<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $idso = $_POST["idso"];
   $sonumber = $_POST["sonumber"]; // Pastikan sonumber juga dikirim dari form
   $idcustomer = $_POST["idcustomer"];
   $deliverydate = $_POST["deliverydate"];
   $po = $_POST["po"];
   $alamat = $_POST["alamat"];
   $note = $_POST["note"];
   $idbarang = $_POST["idbarang"];
   $weight = $_POST["weight"];
   $price = $_POST["price"];
   $notes = $_POST["notes"];
   $idusers = $_SESSION['idusers'];

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
      $updatePlandevQuery = "UPDATE plandev SET idcustomer = $idcustomer, plandelivery = '$deliverydate', weight = $weighttotal WHERE idso = $idso";
      mysqli_query($conn, $updatePlandevQuery);

      // Insert log activity into logactivity table
      $event = "Edit Sales Order";
      $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                   VALUES (?, ?, ?, NOW())";
      $stmt_log = $conn->prepare($logQuery);
      $stmt_log->bind_param("iss", $idusers, $sonumber, $event);
      $stmt_log->execute();
      $stmt_log->close();

      echo "Data Sales Order berhasil diperbarui, dan data PlanDev juga diperbarui.";
   } else {
      echo "Gagal memperbarui data Sales Order: " . mysqli_error($conn);
   }
}

header("location: index.php");
exit();
