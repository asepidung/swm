<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Memeriksa apakah data yang diperlukan telah dikirimkan melalui formulir
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idplandev'])) {
   $idplandev = mysqli_real_escape_string($conn, $_POST['idplandev']);
   $plandelivery = mysqli_real_escape_string($conn, $_POST['plandelivery']);
   $idcustomer = mysqli_real_escape_string($conn, $_POST['idcustomer']);
   $weight = mysqli_real_escape_string($conn, $_POST['weight']);
   $driver_name = mysqli_real_escape_string($conn, $_POST['driver_name']);
   $armada = mysqli_real_escape_string($conn, $_POST['armada']);
   $loadtime = mysqli_real_escape_string($conn, $_POST['loadtime']);
   $note = mysqli_real_escape_string($conn, $_POST['note']);

   // Query SQL untuk melakukan pembaruan data rencana pengiriman
   $query = "UPDATE plandev SET
              plandelivery = '$plandelivery',
              idcustomer = '$idcustomer',
              weight = '$weight',
              driver_name = '$driver_name',
              armada = '$armada',
              loadtime = '$loadtime',
              note = '$note'
              WHERE idplandev = $idplandev";

   // Eksekusi query SQL
   if (mysqli_query($conn, $query)) {
      header("location: index.php"); // Redirect ke halaman rencana pengiriman setelah pembaruan berhasil
   } else {
      echo "Error: " . mysqli_error($conn);
   }
} else {
   echo "Data tidak lengkap.";
}
