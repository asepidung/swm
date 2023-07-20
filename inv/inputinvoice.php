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
   // $tukarfaktur = $_POST['tukarfaktur'];
   $donumber = $_POST['donumber'];
   $pocustomer = $_POST['pocustomer'];
   $note = $_POST['note'];
   $xweight = $_POST['xweight'];
   $charge = str_replace(',', '', $_POST['charge']);


   // Query INSERT ke tabel "invoice"
   $query_invoice = "INSERT INTO invoice (iddo, noinvoice, top, invoice_date, xamount, tax,downpayment, balance, idcustomer, idsegment, xdiscount, donumber, pocustomer, note, xweight, charge) 
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
   $stmt_invoice = $conn->prepare($query_invoice);
   $stmt_invoice->bind_param("isisddddiidsssdd", $iddo, $noinvoice, $top, $invoice_date, $xamount, $tax, $downpayment, $balance, $idcustomer, $idsegment, $xdiscount, $donumber, $pocustomer, $note, $xweight, $charge);
   $stmt_invoice->execute();
   // Mendapatkan ID terakhir yang di-generate dalam tabel "invoice"
   $last_id = $stmt_invoice->insert_id;

   // Proses input ke tabel "invoicedetail"
   if ($last_id) {
      $idinvoice = $last_id;
      $prices = $_POST['price'];
      $discounts = $_POST['discount'];
      $discountrps = $_POST['discountrp'];
      $amounts = $_POST['amount'];

      // Looping untuk memasukkan data detail faktur ke dalam tabel "invoicedetail"
      for ($i = 0; $i < count($prices); $i++) {
         $idgrade = $_POST['idgrade'][$i];
         $idbarang = $_POST['idbarang'][$i];
         $price = str_replace(',', '', $prices[$i]);
         $discount = $discounts[$i];
         $discountrp = str_replace(',', '', $discountrps[$i]);
         $amount = str_replace(',', '', $amounts[$i]);

         // Query INSERT ke tabel "invoicedetail"
         $query_invoicedetail = "INSERT INTO invoicedetail (idinvoice, idgrade, idbarang, price, discount, discountrp, amount) 
                              VALUES (?,?,?,?,?,?,?)";
         $stmt_invoicedetail = $conn->prepare($query_invoicedetail);
         $stmt_invoicedetail->bind_param("iiididd", $idinvoice, $idgrade, $idbarang, $price, $discount, $discountrp, $amount);
         $stmt_invoicedetail->execute();
      }

      // Redirect atau lakukan tindakan lain setelah proses input berhasil
      if ($stmt_invoicedetail->affected_rows > 0) {
         // Input berhasil, redirect ke halaman lain
         header("Location: index.php");
         exit(); // Penting untuk keluar dari skrip setelah melakukan redirect
      } else {
         header("Location: newinv.php");
      }
   }
}
