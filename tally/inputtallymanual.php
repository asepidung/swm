<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Ambil nilai dari formulir
   $idtally = $_POST['idtally'];
   $barcode = $_POST['barcode'];
   $idbarang = $_POST['idbarang'][0];
   $weight = $_POST['weight'];
   $pcs = $_POST['pcs'];
   $pod = $_POST['pod'];
   $origin = $_POST['origin'];

   $insertQuery = "INSERT INTO tallydetail (idtally, barcode, idbarang, weight, pcs, pod, origin) VALUES ('$idtally', '$barcode', '$idbarang', '$weight', '$pcs', '$pod', '$origin')";
   mysqli_query($conn, $insertQuery);
   header("location: tallydetail.php?id=$idtally&stat=success");
}
