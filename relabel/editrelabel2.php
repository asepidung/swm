<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

$idusers = $_SESSION['idusers'];

if (isset($_GET['kdbarcode'])) {
   $kdbarcode = $_GET['kdbarcode'];

   // Query untuk mengambil data dari tabel stock berdasarkan kdbarcode
   $selectStockQuery = "SELECT * FROM stock WHERE kdbarcode = '$kdbarcode'";
   $selectStockResult = mysqli_query($conn, $selectStockQuery);

   if ($selectStockResult && mysqli_num_rows($selectStockResult) > 0) {
      $stockData = mysqli_fetch_assoc($selectStockResult);

      // Tampilkan data dari tabel stock
      echo "Barcode: " . $stockData['kdbarcode'] . "<br>";
      echo "ID Barang: " . $stockData['idbarang'] . "<br>";
      echo "ID Grade: " . $stockData['idgrade'] . "<br>";
      echo "Qty: " . $stockData['qty'] . "<br>";
      echo "PCS: " . $stockData['pcs'] . "<br>";
      echo "POD: " . $stockData['pod'] . "<br>";
      echo "Origin: " . $stockData['origin'] . "<br>";
      // Tambahan informasi lainnya sesuai kebutuhan
   } else {
      echo "Data tidak ditemukan.";
   }
}
