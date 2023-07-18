<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
   $iddo = $_POST['iddo'];
   $invoice_number = $_POST['invoice_number'];
   $invoice_date = $_POST['invoice_date'];
   $xamount = str_replace(',', '', $_POST['xamount']);
   $tax = str_replace(',', '', $_POST['tax']);
   $downpayment = str_replace(',', '', $_POST['downpayment']);
   $balance = str_replace(',', '', $_POST['balance']);
   $idcustomer = $_POST['idcustomer'];
   $idsegment = $_POST['idsegment'];
   $tukarfaktur = $_POST['tukarfaktur'];
   $donumber = $_POST['donumber'];
   $po = $_POST['po'];
   $note = $_POST['note'];
   $xweight = $_POST['xweight'];
   $charge = str_replace(',', '', $_POST['charge']);


   // Query INSERT ke tabel "invoice"
   $query_invoice = "INSERT INTO invoice (iddo, invoice_number, invoice_date, xamount, tax, downpayment, balance, idcustomer, idsegment, tukarfaktur, donumber, po, note, xweight, charge) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
   $stmt_invoice = $conn->prepare($query_invoice);
   $stmt_invoice->bind_param("issddddiiisssdd", $iddo, $invoice_number, $invoice_date, $xamount, $tax, $downpayment, $balance, $idcustomer, $idsegment, $tukarfaktur, $donumber, $po, $note, $xweight, $charge);
   $stmt_invoice->execute();

   // Mendapatkan ID terakhir yang di-generate dalam tabel "invoice"
   $last_id = $stmt_invoice->insert_id;

   // Query INSERT ke tabel "invoicedetail"
   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $weight = $_POST['weight'];
   $price = $_POST['price'];
   $discount = $_POST['discount'];
   $amount = str_replace(',', '', $_POST['amount']);
   // Query INSERT ke tabel "invoicedetail"
   $query_invoicedetail = "INSERT INTO invoicedetail (idinvoice, idgrade, idbarang, weight, price, amount ) VALUES (?,?,?,?,?,?)";
   $stmt_invoicedetail = $conn->prepare($query_invoicedetail);

   // Bind parameter dan eksekusi query INSERT sebanyak item yang ada
   for ($i = 0; $i < count($idgrade); $i++) {
      $stmt_invoicedetail->bind_param("iiiddd", $last_id, $idgrade[$i], $idbarang[$i], $weight[$i], $price[$i], $amount[$i]);
      $stmt_invoicedetail->execute();
   }

   $stmt_invoicedetail->close();
   $stmt_invoice->close();
   $conn->close();

   // Redirect ke halaman lain setelah proses INSERT selesai
   header("location: index.php");
}
