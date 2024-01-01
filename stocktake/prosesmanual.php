<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Ambil nilai dari formulir
   $idst = $_POST['idst'];
   $kdbarcode = $_POST['kdbarcode'];
   $idbarang = $_POST['idbarang'][0];
   $idgrade = $_POST['idgrade'][0];
   $qty = $_POST['qty'];
   $pcs = $_POST['pcs'];
   $pod = $_POST['pod'];
   $origin = $_POST['origin'];

   $insertQuery = "INSERT INTO stocktakedetail (idst, kdbarcode, idbarang, idgrade, qty, pcs, pod, origin) VALUES ('$idst', '$kdbarcode', '$idbarang', '$idgrade', '$qty', '$pcs', '$pod', '$origin')";
   mysqli_query($conn, $insertQuery);
   header("location: starttaking.php?id=$idst&stat=success");
}
