<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Check if the form is submitted
if (isset($_POST['submit'])) {
   // Retrieve data from the form and remove commas
   $iddo = $_POST['iddo'];
   $noinvoice = $_POST['noinvoice'];
   $iddoreceipt = $_POST['iddoreceipt'];
   $idsegment = $_POST['idsegment'];
   $top = $_POST['top'];
   $invoice_date = $_POST['invoice_date'];
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

   // Insert data into the 'invoicedetail' table
   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $idrawmate = $_POST['idrawmate'];
   $weight = $_POST['weight'];
   $price = $_POST['price'];
   $discount = $_POST['discount'];
   $discountrp = $_POST['discountrp'];
   $amount = $_POST['amount'];

   for ($i = 0; $i < count($idgrade); $i++) {
      $idgrade[$i] = $idgrade[$i];
      $idbarang[$i] = $idbarang[$i];
      $idrawmate[$i] = $idrawmate[$i];
      $weight[$i] = $weight[$i];
      $price[$i] = str_replace(',', '', $price[$i]);
      $discount[$i] = $discount[$i];
      $discountrp[$i] = str_replace(',', '', $discountrp[$i]);
      $amount[$i] = str_replace(',', '', $amount[$i]);

      $sql = "INSERT INTO invoicedetail (idinvoice, idgrade, idbarang, idrawmate, weight, price, discount, discountrp, amount) 
              VALUES ('$invoiceID', '{$idgrade[$i]}', '{$idbarang[$i]}', '{$idrawmate[$i]}', '{$weight[$i]}', '{$price[$i]}', '{$discount[$i]}', '{$discountrp[$i]}', '{$amount[$i]}')";
      // Execute the SQL query
      mysqli_query($conn, $sql);
   }

   $updateSql1 = "UPDATE doreceipt SET status = 'Invoiced' WHERE iddoreceipt = '$iddoreceipt'";
   mysqli_query($conn, $updateSql1);

   $updateSql = "UPDATE do SET status = 'Invoiced' WHERE iddo = '$iddo'";
   mysqli_query($conn, $updateSql);

   // Redirect to a success page or perform any other actions
   header("location: invoice.php");
   exit();
}
