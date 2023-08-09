<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
   $grnumber = $_POST['grnumber'];
   $receivedate = $_POST['receivedate'];
   $idsupplier = $_POST['idsupplier'];
   $idnumber = $_POST['idnumber'];
   $xbox = $_POST['xbox'];
   $xweight = $_POST['xweight'];
   $note = $_POST['note'];
   $idusers = $_SESSION['idusers'];

   // Query INSERT untuk tabel gr
   $query_gr = "INSERT INTO gr (grnumber, receivedate, idsupplier, idnumber, note, xbox, xweight, iduser) VALUES (?,?,?,?,?,?,?,?)";
   $stmt_gr = $conn->prepare($query_gr);
   $stmt_gr->bind_param("ssissidi", $grnumber, $receivedate, $idsupplier, $idnumber, $note, $xbox, $xweight, $idusers);
   $stmt_gr->execute();

   $last_id = $stmt_gr->insert_id;

   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $box = $_POST['box'];
   $weight = $_POST['weight'];
   $notes = $_POST['notes'];

   // Query INSERT untuk tabel grdetail
   $query_grdetail = "INSERT INTO grdetail (idgr, idgrade, idbarang, box, weight, notes) VALUES (?,?,?,?,?,?)";
   $stmt_grdetail = $conn->prepare($query_grdetail);

   for ($i = 0; $i < count($idgrade); $i++) {
      $stmt_grdetail->bind_param("iiiids", $last_id, $idgrade[$i], $idbarang[$i], $box[$i], $weight[$i], $notes[$i]);
      $stmt_grdetail->execute();
   }

   $stmt_grdetail->close();
   $stmt_gr->close();
   $conn->close();

   header("location: index.php");
}
