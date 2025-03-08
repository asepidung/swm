<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "ponumber.php";

// Check if the form is submitted
if (isset($_POST['submit'])) {
   // Retrieve data from the form and sanitize inputs
   $nopomaterial = $kodeauto;
   $tglpomaterial = $_POST['tglpomaterial'];
   $deliveryat = $_POST['deliveryat'];
   $idsupplier = $_POST['idsupplier'];
   $note = $_POST['note'];
   $terms = $_POST['terms'];
   $idusers = $_SESSION['idusers'];
   $stat = "Waiting";

   // Insert data into the 'pomaterial' table
   $sql = "INSERT INTO pomaterial (nopomaterial, idsupplier, tglpomaterial, deliveryat, note, idusers, stat, terms) 
         VALUES ('$nopomaterial', '$idsupplier', '$tglpomaterial', '$deliveryat', '$note', '$idusers', '$stat', '$terms')";

   // Execute the SQL query
   if (mysqli_query($conn, $sql)) {
      // Retrieve the last inserted pomaterial ID
      $pomaterialID = mysqli_insert_id($conn);

      // Insert data into the 'pomaterialdetail' table
      $idrawmate = $_POST['idrawmate'];
      $weight = str_replace(',', '', $_POST['weight']);
      $price = str_replace(',', '', $_POST['price']);
      $amount = str_replace(',', '', $_POST['amount']);
      $notes = $_POST['notes'];

      for ($i = 0; $i < count($idrawmate); $i++) {
         $idrawmate[$i] = $idrawmate[$i];
         $weight[$i] = $weight[$i];
         $price[$i] = str_replace(',', '', $price[$i]);
         $amount[$i] = str_replace(',', '', $amount[$i]);

         $sql = "INSERT INTO pomaterialdetail (idpomaterial, idrawmate, qty, price, amount, notes) 
                 VALUES ('$pomaterialID', '{$idrawmate[$i]}', '{$weight[$i]}', '{$price[$i]}', '{$amount[$i]}', '{$notes[$i]}')";

         // Execute the SQL query
         mysqli_query($conn, $sql);
      }

      // Insert log activity into logactivity table
      $event = "Buat PO Material";
      $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                   VALUES ('$idusers', '$nopomaterial', '$event', NOW())";
      mysqli_query($conn, $logQuery);

      // Redirect to a success page or perform any other actions
      header("location: index.php");
      exit();
   } else {
      // Handle the case where the SQL query fails
      echo "Error: " . mysqli_error($conn);
   }
}
