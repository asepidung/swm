<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
   $iddo = $_POST['iddo'];
   $noinvoice = $_POST['noinvoice'];
   $top = $_POST['top'];
   $invoice_date = $_POST['invoice_date'];
   $xamount = str_replace(',', '', $_POST['xamount']);
   $tax = str_replace(',', '', $_POST['tax']);
   $downpayment = str_replace(',', '', $_POST['downpayment']);
   $balance = str_replace(',', '', $_POST['balance']);
   $idcustomer = $_POST['idcustomer'];
   $idsegment = $_POST['idsegment'];
   $xdiscount = str_replace(',', '', $_POST['xdiscount']);
   $donumber = $_POST['donumber'];
   $pocustomer = $_POST['pocustomer'];
   $note = $_POST['note'];
   $xweight = $_POST['xweight'];
   $charge = str_replace(',', '', $_POST['charge']);

   // Displaying data from $_POST
   echo "iddo: " . $iddo . "<br>";
   echo "noinvoice: " . $noinvoice . "<br>";
   echo "top: " . $top . "<br>";
   echo "invoice_date: " . $invoice_date . "<br>";
   echo "xamount: " . $xamount . "<br>";
   echo "tax: " . $tax . "<br>";
   echo "downpayment: " . $downpayment . "<br>";
   echo "balance: " . $balance . "<br>";
   echo "idcustomer: " . $idcustomer . "<br>";
   echo "idsegment: " . $idsegment . "<br>";
   echo "xdiscount: " . $xdiscount . "<br>";
   echo "donumber: " . $donumber . "<br>";
   echo "pocustomer: " . $pocustomer . "<br>";
   echo "note: " . $note . "<br>";
   echo "xweight: " . $xweight . "<br>";
   echo "charge: " . $charge . "<br>";

   // Proses input ke tabel "invoicedetail"
   $idgrades = $_POST['idgrade'];
   $idbarangs = $_POST['idbarang'];
   $prices = $_POST['price'];
   $discounts = $_POST['discount'];
   $discountrps = $_POST['discountrp'];
   $amounts = $_POST['amount'];

   for ($i = 0; $i < count($idgrades); $i++) {
      $idgrade = $idgrades[$i];
      $idbarang = $idbarangs[$i];
      $price = str_replace(',', '', $prices[$i]);
      $discount = $discounts[$i];
      $discountrp = str_replace(',', '', $discountrps[$i]);
      $amount = str_replace(',', '', $amounts[$i]);

      // Displaying data from $_POST for invoice detail
      echo "idgrade[" . $i . "]: " . $idgrade . "<br>";
      echo "idbarang[" . $i . "]: " . $idbarang . "<br>";
      echo "price[" . $i . "]: " . $price . "<br>";
      echo "discount[" . $i . "]: " . $discount . "<br>";
      echo "discountrp[" . $i . "]: " . $discountrp . "<br>";
      echo "amount[" . $i . "]: " . $amount . "<br>";

      // Query INSERT ke tabel "invoicedetail"
      // ...
   }
}
