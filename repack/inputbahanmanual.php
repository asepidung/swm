<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Ambil nilai dari formulir
   $idrepack = $_POST['idrepack'];
   $barcode = $_POST['barcode'];
   $idbarang = $_POST['idbarang'][0];
   $qty = $_POST['qty'];
   $pcs = $_POST['pcs'];
   $pod = $_POST['pod'];
   $origin = $_POST['origin'];

   $insertQuery = "INSERT INTO detailbahan (idrepack, barcode, idbarang, qty, pcs, pod, origin) VALUES ('$idrepack', '$barcode', '$idbarang', '$qty', '$pcs', '$pod', '$origin')";
   mysqli_query($conn, $insertQuery);
   header("location: detailbahan.php?id=$idrepack&stat=success");
}
