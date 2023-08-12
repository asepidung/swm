<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
   $returnnumber = $_POST['returnnumber'];
   $returdate = $_POST['returdate'];
   $xweight = $_POST['xweight'];
   $xbox = $_POST['xbox'];
   $note = $_POST['note'];
   $iddo = $_POST['iddo'];
   $idcustomer = $_POST['idcustomer'];
   $idusers = $_SESSION['idusers'];

   $query_returjual = "INSERT INTO returjual (returnnumber, returdate, xweight, xbox, note, iddo, idusers, idcustomer)
                        VALUES (?,?,?,?,?,?,?,?)";
   $stmt_returjual = $conn->prepare($query_returjual);
   if ($stmt_returjual === false) {
      die("Error preparing returjual query: " . $conn->error);
   }
   $stmt_returjual->bind_param("ssdisiii", $returnnumber, $returdate, $xweight, $xbox, $note, $iddo, $idusers, $idcustomer);
   if ($stmt_returjual->execute() === false) {
      die("Error executing returjual query: " . $stmt_returjual->error);
   }
   $last_id = $stmt_returjual->insert_id;
   $stmt_returjual->close();

   // Data returjual
   // echo "Return Number: $returnnumber<br>";
   // echo "Retur Date: $returdate<br>";
   // echo "X Weight: $xweight<br>";
   // echo "X Box: $xbox<br>";
   // echo "Note: $note<br>";
   // echo "ID DO: $iddo<br>";
   // echo "ID Customer: $idcustomer<br>";
   // echo "ID Users: $idusers<br>";

   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $box = $_POST['box'];
   $weight = $_POST['weight'];
   $notes = $_POST['notes'];

   // Kueri returjualdetail (dikomentari)
   $query_returjualdetail = "INSERT INTO returjualdetail (idreturjual, idgrade, idbarang, weight, box, notes) VALUES (?,?,?,?,?,?)";
   $stmt_returjualdetail = $conn->prepare($query_returjualdetail);
   if ($stmt_returjualdetail === false) {
      die("Error preparing returjualdetail query: " . $conn->error);
   }

   for ($i = 0; $i < count($idgrade); $i++) {
      $stmt_returjualdetail->bind_param("iiidis", $last_id, $idgrade[$i], $idbarang[$i], $weight[$i], $box[$i], $notes[$i]);
      if ($stmt_returjualdetail->execute() === false) {
         die("Error executing returjualdetail query: " . $stmt_returjualdetail->error);
      }
   }

   $stmt_returjualdetail->close();

   // Data returjualdetail
   // for ($i = 0; $i < count($idgrade); $i++) {
   //    echo "<br>Retur Jual Detail $i:";
   //    echo "<br>ID Grade: " . $idgrade[$i];
   //    echo "<br>ID Barang: " . $idbarang[$i];
   //    echo "<br>Box: " . $box[$i];
   //    echo "<br>Weight: " . $weight[$i];
   //    echo "<br>Notes: " . $notes[$i];
   // }

   $conn->close();

   header("location: index.php");
}
