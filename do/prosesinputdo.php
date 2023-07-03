<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Mengambil data dari form
   $deliverydate = $_POST['deliverydate'];
   $customer = $_POST['customer'];
   $po = $_POST['po'];
   $driver = $_POST['driver'];
   $policenumb = $_POST['policenumb'];
   $kdarea = $_POST['kdarea'];
   $idbarang = $_POST['idbarang'];
   $box = $_POST['box'];
   $qty = $_POST['qty'];
   $note = $_POST['note'];

   // Proses penyimpanan data atau cetak DO

   // Contoh akses data yang dikirim dari form
   echo "Tgl Kirim: " . $deliverydate . "<br>";
   echo "Customer: " . $customer . "<br>";
   echo "Cust PO: " . $po . "<br>";
   echo "Driver: " . $driver . "<br>";
   echo "Plat Number: " . $policenumb . "<br>";

   // Contoh akses data item
   for ($i = 0; $i < count($kdarea); $i++) {
      $itemKdArea = $kdarea[$i];
      $itemIdBarang = $idbarang[$i];
      $itemBox = $box[$i];
      $itemQty = $qty[$i];
      $itemNote = $note[$i];

      echo "Item " . ($i + 1) . ":<br>";
      echo "Kode Area: " . $itemKdArea . "<br>";
      echo "ID Barang: " . $itemIdBarang . "<br>";
      echo "Box: " . $itemBox . "<br>";
      echo "Weight: " . $itemQty . "<br>";
      echo "Notes: " . $itemNote . "<br>";
      echo "<br>";
   }
}
