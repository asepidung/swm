<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Check if the form is submitted
if (isset($_POST['submit'])) {
   // Retrieve data from the form and remove commas
   $noinvoice = $_POST['noinvoice'];
   $iddo = $_POST['iddo'];
   $idsegment = $_POST['idsegment'];
   $idcustomer = $_POST['idcustomer'];
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
   $top = $_POST['top'];
   $invoice_date = $_POST['invoice_date'];
   $tukarfaktur = $_POST['tukarfaktur'];
   if ($tukarfaktur == 'YES') {
      $status = "Belum TF";
      $duedate = NULL;
   } else {
      $status = '-';
      $invoice_date_obj = new DateTime($invoice_date);

      // Tambahkan TOP (jangka waktu pembayaran) ke invoice_date_obj
      $duedate_obj = clone $invoice_date_obj; // Duplikasi objek tanggal invoice_date_obj
      $duedate_obj->modify("+" . $top . " days"); // Tambahkan TOP (jangka waktu pembayaran) ke objek tanggal

      // Format duedate menjadi string dengan format 'Y-m-d' (optional, tergantung kebutuhan)
      $duedate = $duedate_obj->format('Y-m-d');
   }

   // Insert data into the 'invoice' table
   $sql = "INSERT INTO invoice (noinvoice, iddo, idsegment, top, duedate, status, invoice_date, idcustomer, pocustomer, donumber, note, xweight, xamount, xdiscount, tax, charge, downpayment, balance) 
           VALUES ('$noinvoice', '$iddo', '$idsegment', '$top', '$duedate', '$status', '$invoice_date', '$idcustomer', '$pocustomer', '$donumber', '$note', '$xweight', '$xamount', '$xdiscount', '$tax', '$charge', '$downpayment', '$balance')";
   // Execute the SQL query
   mysqli_query($conn, $sql);

   // Retrieve the last inserted invoice ID
   $invoiceID = mysqli_insert_id($conn);

   // Insert data into the 'invoicedetail' table
   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $weight = $_POST['weight'];
   $price = $_POST['price'];
   $discount = $_POST['discount'];
   $discountrp = $_POST['discountrp'];
   $amount = $_POST['amount'];

   for ($i = 0; $i < count($idgrade); $i++) {
      $idgrade[$i] = mysqli_real_escape_string($conn, $idgrade[$i]);
      $idbarang[$i] = mysqli_real_escape_string($conn, $idbarang[$i]);
      $weight[$i] = mysqli_real_escape_string($conn, $weight[$i]);
      $price[$i] = str_replace(',', '', $price[$i]);
      $discount[$i] = mysqli_real_escape_string($conn, $discount[$i]);
      $discountrp[$i] = str_replace(',', '', $discountrp[$i]);
      $amount[$i] = str_replace(',', '', $amount[$i]);

      $sql = "INSERT INTO invoicedetail (idinvoice, idgrade, idbarang, weight, price, discount, discountrp, amount) 
              VALUES ('$invoiceID', '{$idgrade[$i]}', '{$idbarang[$i]}', '{$weight[$i]}', '{$price[$i]}', '{$discount[$i]}', '{$discountrp[$i]}', '{$amount[$i]}')";
      // Execute the SQL query
      mysqli_query($conn, $sql);
   }

   $updateSql = "UPDATE do SET status = 'Invoiced' WHERE iddo = '$iddo'";
   mysqli_query($conn, $updateSql);

   // Redirect to a success page or perform any other actions
   header("location: invoice.php");
   // exit();
}
