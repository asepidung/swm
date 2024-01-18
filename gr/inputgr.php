<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "grnumber.php";

if (isset($_POST['submit'])) {
   $grnumber = $_POST['grnumber'];
   $receivedate = $_POST['receivedate'];
   $idsupplier = $_POST['idsupplier'];
   $idnumber = $_POST['idnumber'];
   $note = $_POST['note'];
   $idusers = $_SESSION['idusers'];

   // Query INSERT untuk tabel gr
   $query_gr = "INSERT INTO gr (grnumber, receivedate, idsupplier, idnumber, note, iduser) VALUES (?,?,?,?,?,?)";
   $stmt_gr = $conn->prepare($query_gr);
   $stmt_gr->bind_param("ssissi", $gr, $receivedate, $idsupplier, $idnumber, $note, $idusers);
   $stmt_gr->execute();
   $stmt_gr->close();
   $conn->close();

   header("location: index.php");
}
