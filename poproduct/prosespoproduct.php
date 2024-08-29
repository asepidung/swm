<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}
require "../konak/conn.php";

// Check if the form is submitted
if (isset($_POST['submit'])) {
   // Retrieve data from the form and sanitize inputs
   $nopoproduct = $_POST['nopoproduct'];
   $tglpoproduct = $_POST['tglpoproduct'];
   $deliveryat = $_POST['deliveryat'];
   $idsupplier = $_POST['idsupplier'];
   $note = $_POST['note'];
   $xweight = str_replace(',', '', $_POST['xweight']);
   $xamount = str_replace(',', '', $_POST['xamount']);
   $idusers = $_SESSION['idusers'];
   $stat = "Waiting";
   if ($_POST['terms'] == "custom") {
      $terms = $_POST['custom_terms'];
   } else {
      $terms = $_POST['terms'];
   }
   // Insert data into the 'poproduct' table
   $sql = "INSERT INTO poproduct (nopoproduct, idsupplier, tglpoproduct, deliveryat, note, idusers, stat, xweight, xamount, terms) 
         VALUES ('$nopoproduct', '$idsupplier', '$tglpoproduct', '$deliveryat', '$note', '$idusers', '$stat', '$xweight', '$xamount', '$terms')";

   // Execute the SQL query
   if (mysqli_query($conn, $sql)) {
      // Retrieve the last inserted poproduct ID
      $poproductID = mysqli_insert_id($conn);

      // Insert data into the 'poproductdetail' table
      $idbarang = $_POST['idbarang'];
      $weight = str_replace(',', '', $_POST['weight']);
      $price = str_replace(',', '', $_POST['price']);
      $amount = str_replace(',', '', $_POST['amount']);
      $notes = $_POST['notes'];

      for ($i = 0; $i < count($idbarang); $i++) {
         $idbarang[$i] = $idbarang[$i];
         $weight[$i] = $weight[$i];
         $price[$i] = str_replace(',', '', $price[$i]);
         $amount[$i] = str_replace(',', '', $amount[$i]);

         $sql = "INSERT INTO poproductdetail (idpoproduct, idbarang, qty, price, amount, notes) 
                 VALUES ('$poproductID', '{$idbarang[$i]}', '{$weight[$i]}', '{$price[$i]}', '{$amount[$i]}', '{$notes[$i]}')";

         // Execute the SQL query
         mysqli_query($conn, $sql);
      }

      // Insert log activity into logactivity table
      $event = "Buat PO Product";
      $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                   VALUES ('$idusers', '$nopoproduct', '$event', NOW())";
      mysqli_query($conn, $logQuery);

      // Redirect to a success page or perform any other actions
      header("location: index.php");
      exit();
   } else {
      // Handle the case where the SQL query fails
      echo "Error: " . mysqli_error($conn);
   }
}
