<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $returnnumber = $_POST["returnnumber"];
   $returdate = $_POST["returdate"];
   $idcustomer = $_POST["idcustomer"];
   $donumber = $_POST["donumber"];
   $note = $_POST["note"];
   $xbox = $_POST["xbox"];
   $xweight = $_POST["xweight"];
   $idusers = $_SESSION['idusers'];

   // Insert data into 'returjual' table
   $insertReturJualQuery = "INSERT INTO returjual (returnnumber, returdate, idcustomer, note, xbox, xweight, donumber, idusers)
                            VALUES ('$returnnumber', '$returdate', $idcustomer, '$note', $xbox, $xweight, $donumber, $idusers)"; // Replace {your_idusers_value} with the actual value

   if (mysqli_query($conn, $insertReturJualQuery)) {
      $idreturjual = mysqli_insert_id($conn);

      // Insert data into 'returjualdetail' table for each item
      if (isset($_POST['idgrade']) && isset($_POST['idbarang']) && isset($_POST['box']) && isset($_POST['weight'])) {
         $idgrades = $_POST['idgrade'];
         $idbarangs = $_POST['idbarang'];
         $boxes = $_POST['box'];
         $weights = $_POST['weight'];
         $notes = $_POST['notes'];

         for ($i = 0; $i < count($idgrades); $i++) {
            $idgrade = $idgrades[$i];
            $idbarang = $idbarangs[$i];
            $box = $boxes[$i];
            $weight = $weights[$i];
            $note = $notes[$i];

            // Insert data into 'returjualdetail' table
            $insertReturJualDetailQuery = "INSERT INTO returjualdetail (idreturjual, idgrade, idbarang, box, weight, notes)
                                          VALUES ($idreturjual, $idgrade, $idbarang, $box, $weight, '$note')";

            mysqli_query($conn, $insertReturJualDetailQuery);
         }
      }

      // Redirect or display a success message
      header("Location: index.php"); // Redirect to a success page
   } else {
      echo "Error: " . mysqli_error($conn);
   }
}
