<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
   $donumber = $_POST['donumber'];
   $deliverydate = $_POST['deliverydate'];
   $idcustomer = $_POST['idcustomer'];
   $po = $_POST['po'];
   $driver = $_POST['driver'];
   $plat = $_POST['plat'];
   $xbox = $_POST['xbox'];
   $xweight = $_POST['xweight'];
   $note = $_POST['note'];
   $idusers = $_SESSION['idusers'];

   // Query INSERT ke tabel "do"
   $query_do = "INSERT INTO do (donumber, deliverydate, idcustomer, po, driver, plat, note, xbox, xweight, idusers) VALUES (?,?,?,?,?,?,?,?,?,?)";
   $stmt_do = $conn->prepare($query_do);
   $stmt_do->bind_param("ssissssidi", $donumber, $deliverydate, $idcustomer, $po, $driver, $plat, $note, $xbox, $xweight, $idusers);
   $stmt_do->execute();

   // Mendapatkan ID terakhir yang di-generate dalam tabel "do"
   $last_id = $stmt_do->insert_id;

   // Query INSERT ke tabel "dodetail"
   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $box = $_POST['box'];
   $weight = $_POST['weight'];
   $notes = $_POST['notes'];

   $query_dodetail = "INSERT INTO dodetail (iddo, idgrade, idbarang, box, weight, notes) VALUES (?,?,?,?,?,?)";
   $stmt_dodetail = $conn->prepare($query_dodetail);

   // Bind parameter dan eksekusi query INSERT sebanyak item yang ada
   for ($i = 0; $i < count($idgrade); $i++) {
      $stmt_dodetail->bind_param("iiiids", $last_id, $idgrade[$i], $idbarang[$i], $box[$i], $weight[$i], $notes[$i]);
      $stmt_dodetail->execute();
   }

   $stmt_dodetail->close();
   $stmt_do->close();
   $conn->close();

   // Redirect ke halaman lain setelah proses INSERT selesai
   header("location: do.php");
}
