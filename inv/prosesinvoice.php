<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $invoice_number = $_POST['invoice_number'];
   $iddo = $_POST['iddo'];
   // $pajak = $_POST['pajak'];
   $tukarfaktur = $_POST['tukarfaktur'];
   $invoice_date = $_POST['invoice_date'];
   $idcustomer = $_POST['idcustomer'];
   $po = $_POST['po'];
   $donumber = $_POST['donumber'];
   $note = $_POST['note'];
   $weight = $_POST['weight'];
   $price = $_POST['price'];
   $discount = $_POST['discount'];
   $amount = str_replace(',', '', $_POST['amount']);
   $xweight = $_POST['xweight'];
   $xamount = str_replace(',', '', $_POST['xamount']);
   $tax = str_replace(',', '', $_POST['tax']);
   $charge = str_replace(',', '', $_POST['charge']);
   $dp = str_replace(',', '', $_POST['dp']);
   $balance = str_replace(',', '', $_POST['balance']);

   // Echo $_POST values
   echo "invnumber: " . $invnumber . "<br>";
   echo "iddo: " . $iddo . "<br>";
   echo "pajak: " . $pajak . "<br>";
   echo "tukarfaktur: " . $tukarfaktur . "<br>";
   echo "invoice_date: " . $invoice_date . "<br>";
   echo "idcustomer: " . $idcustomer . "<br>";
   echo "po: " . $po . "<br>";
   echo "donumber: " . $donumber . "<br>";
   echo "note: " . $note . "<br>";
   echo "weight: ";
   foreach ($weight as $w) {
      echo $w . ", ";
   }
   echo "<br>";
   echo "price: ";
   foreach ($price as $p) {
      echo $p . ", ";
   }
   echo "<br>";
   echo "discount: ";
   foreach ($discount as $d) {
      echo $d . ", ";
   }
   echo "<br>";
   echo "amount: ";
   foreach ($amount as $a) {
      echo $a . ", ";
   }
   echo "<br>";
   echo "xweight: " . $xweight . "<br>";
   echo "xamount: " . $xamount . "<br>";
   echo "tax: " . $tax . "<br>";
   echo "charge: " . $charge . "<br>";
   echo "dp: " . $dp . "<br>";
   echo "balance: " . $balance . "<br>";

   // Perform further processing or database operations here
   // ...
}
