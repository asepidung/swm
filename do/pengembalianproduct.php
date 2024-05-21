<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['items']) && isset($_POST['iddo'])) {
   $items = $_POST['items'];
   $iddo = intval($_POST['iddo']);

   foreach ($items as $item) {
      $item = json_decode($item, true);
      $kdbarcode = mysqli_real_escape_string($conn, $item['barcode']);
      $idbarang = intval($item['idbarang']);
      $idgrade = intval($item['idgrade']);
      $qty = floatval($item['weight']);
      $pcs = intval($item['pcs']);
      $pod = mysqli_real_escape_string($conn, $item['pod']);
      $origin = intval($item['origin']);

      $queryInsert = "INSERT INTO stock (kdbarcode, idbarang, idgrade, qty, pcs, pod, origin) VALUES ('$kdbarcode', $idbarang, $idgrade, $qty, $pcs, '$pod', $origin)";
      if (!mysqli_query($conn, $queryInsert)) {
         echo "Error: " . mysqli_error($conn);
         exit();
      }
   }

   // Redirect ke halaman lain setelah selesai
   header("location: approvedo.php?iddo=$iddo");
   exit();
} else {
   echo "No items selected or missing iddo.";
   exit();
}

$conn->close();
