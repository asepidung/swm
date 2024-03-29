<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "invnumber.php";
if (isset($_POST['submit'])) {
   $iddo = $_POST['iddo'];
   $iddoreceipt = $_POST['iddoreceipt'];
   $idsegment = $_POST['idsegment'];
   $top = $_POST['top'];
   $invoice_date = $_POST['invoice_date'];
   $idcustomer = $_POST['idcustomer'];
   $idgroup = $_POST['idgroup'];
   $pocustomer = $_POST['pocustomer'];
   $donumber = $_POST['donumber'];
   $note = $_POST['note'];
   $pajak = $_POST['pajak'];

   $xweight = $_POST['xweight'];
   $xamount = str_replace(',', '', $_POST['xamount']);
   $xdiscount = str_replace(',', '', $_POST['xdiscount']);
   $tax = str_replace(',', '', $_POST['tax']);
   $charge = str_replace(',', '', $_POST['charge']);
   $downpayment = str_replace(',', '', $_POST['downpayment']);
   $balance = str_replace(',', '', $_POST['balance']);
   $tukarfaktur = $_POST['tukarfaktur'];
   // hitung duedate
   $invoice_date_obj = new DateTime($invoice_date);
   $duedate_obj = clone $invoice_date_obj; // Duplikasi objek tanggal invoice_date_obj
   $duedate_obj->modify("+" . $top . " days"); // Tambahkan TOP (jangka waktu pembayaran) ke objek tanggal
   $duedate = $duedate_obj->format('Y-m-d');
   //  akhir hitung duedate

   if ($tukarfaktur == 'YES') {
      $status = 'Belum TF';
   } else {
      $status = '-';
   }

   // Insert data into the 'invoice' table
   $sql = "INSERT INTO invoice (noinvoice, iddoreceipt, idsegment, top, duedate, invoice_date, status, tgltf, idcustomer, pocustomer, donumber, note, xweight, xamount, xdiscount, tax, charge, downpayment, balance) 
           VALUES ('$noinvoice', '$iddoreceipt', '$idsegment', '$top', '$duedate', '$invoice_date', '$status', NULL, '$idcustomer', '$pocustomer', '$donumber', '$note', '$xweight', '$xamount', '$xdiscount', '$tax', '$charge', '$downpayment', '$balance')";
   // Execute the SQL query
   mysqli_query($conn, $sql);

   // Retrieve the last inserted invoice ID
   $invoiceID = mysqli_insert_id($conn);

   // Insert data into the 'piutang' table
   $sql2 = "INSERT INTO piutang (idgroup, idinvoice, idcustomer, balance, duedate, progress) 
           VALUES ('$idgroup', '$invoiceID', '$idcustomer', '$balance', '$duedate', '$status')";
   // Execute the SQL query
   mysqli_query($conn, $sql2);


   // Insert data into the 'invoicedetail' table
   $idbarang = $_POST['idbarang'];
   $weight = $_POST['weight'];
   $price = $_POST['price'];
   $discount = $_POST['discount'];
   $discountrp = $_POST['discountrp'];
   $amount = $_POST['amount'];

   for ($i = 0; $i < count($idbarang); $i++) {
      $idbarang[$i] = $idbarang[$i];
      $weight[$i] = $weight[$i];
      $price[$i] = str_replace(',', '', $price[$i]);
      $discount[$i] = $discount[$i];
      $discountrp[$i] = str_replace(',', '', $discountrp[$i]);
      $amount[$i] = str_replace(',', '', $amount[$i]);

      $sql = "INSERT INTO invoicedetail (idinvoice, idbarang, weight, price, discount, discountrp, amount) 
              VALUES ('$invoiceID', '{$idbarang[$i]}', '{$weight[$i]}', '{$price[$i]}', '{$discount[$i]}', '{$discountrp[$i]}', '{$amount[$i]}')";

      // Execute the SQL query with error handling
      if (mysqli_query($conn, $sql)) {
         echo "Record inserted successfully into invoicedetail<br>";
      } else {
         echo "Error inserting record into invoicedetail: " . mysqli_error($conn) . "<br>";
      }
   }

   $updateSql1 = "UPDATE doreceipt SET status = 'Invoiced' WHERE iddoreceipt = '$iddoreceipt'";
   mysqli_query($conn, $updateSql1);

   $updateSql = "UPDATE do SET status = 'Invoiced' WHERE iddo = '$iddo'";
   mysqli_query($conn, $updateSql);

   // Redirect to a success page or perform any other actions
   header("location: invoice.php");
   exit();
}
