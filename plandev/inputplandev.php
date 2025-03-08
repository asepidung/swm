<?php
require "../verifications/auth.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   require "../konak/conn.php";

   $plandelivery = $_POST['plandelivery'];
   $idcustomer = $_POST['idcustomer'];
   $weight = $_POST['weight'];
   $driver_name = $_POST['driver_name'];
   $armada = $_POST['armada'];
   $loadtime = $_POST['loadtime'];
   $note = $_POST['note'];

   // Query untuk menyimpan data ke database
   $insert_query = "INSERT INTO plandev (plandelivery, idcustomer, weight, driver_name, armada, loadtime, note) VALUES (?, ?, ?, ?, ?, ?, ?)";

   $stmt = mysqli_prepare($conn, $insert_query);
   if ($stmt === false) {
      die("Preparation failed: " . mysqli_error($conn));
   }

   mysqli_stmt_bind_param($stmt, "siissss", $plandelivery, $idcustomer, $weight, $driver_name, $armada, $loadtime, $note);

   if (mysqli_stmt_execute($stmt)) {
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      header("location: index.php"); // Redirect ke halaman sukses atau halaman lain sesuai kebutuhan
   } else {
      echo "Error: " . mysqli_error($conn);
   }
}
