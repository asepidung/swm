<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Fungsi untuk mengubah format angka menjadi format yang konsisten
function formatAngka($angka)
{
   return str_replace(",", ".", str_replace(".", "", $angka));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $noinvoice = $_POST["noinvoice"];
   $iddo = $_POST["iddo"];
   $idsegment = $_POST["idsegment"];
   $top = $_POST["top"];
   $invoice_date = $_POST["invoice_date"];
   $idcustomer = $_POST["idcustomer"];
   $pocustomer = $_POST["pocustomer"];
   $donumber = $_POST["donumber"];
   $note = $_POST["note"];
   $xweight = $_POST["xweight"];
   $xamount = formatAngka($_POST["xamount"]);
   $xdiscount = formatAngka($_POST["xdiscount"]);
   $tax = formatAngka($_POST["tax"]);
   $charge = formatAngka($_POST["charge"]);
   $downpayment = formatAngka($_POST["downpayment"]);
   $balance = formatAngka($_POST["balance"]);

   // Konversi $invoice_date menjadi timestamp dan tambahkan $top (dalam satuan hari)
   $invoice_timestamp = strtotime($invoice_date);
   $duedate_timestamp = strtotime("+$top day", $invoice_timestamp);
   // Konversi kembali menjadi format tanggal yang diinginkan (misal: 23 Juli 2023)
   $duedate = date('Y-m-d', $duedate_timestamp);

   // Proses penyimpanan data faktur ke tabel invoice
   $queryInvoice = "INSERT INTO invoice (noinvoice, iddo, top, invoice_date, duedate, idsegment, idcustomer, pocustomer, donumber, note, xweight, xamount, xdiscount, tax, charge, downpayment, balance)
VALUES ('$noinvoice', $iddo, $top, '$invoice_date', '$duedate', $idsegment, $idcustomer, '$pocustomer', '$donumber', '$note', $xweight, $xamount, $xdiscount, $tax, $charge, $downpayment, $balance)";
   $resultInvoice = mysqli_query($conn, $queryInvoice);

   // ...
   if ($resultInvoice) {
      // Mendapatkan ID invoice yang baru saja di-generate
      $lastInsertedId = mysqli_insert_id($conn);

      // Proses penyimpanan data detail faktur ke tabel invoicedetail
      $items = $_POST["idbarang"];
      $prices = $_POST["price"];
      $discounts = $_POST["discount"];
      $discountrps = $_POST["discountrp"];
      $weights = $_POST["weight"];
      $idgrades = $_POST["idgrade"];
      $amounts = $_POST["amount"];

      // Periksa apakah data yang diterima adalah array atau bukan sebelum menggunakan count()
      if (is_array($idgrades) && is_array($weights) && count($idgrades) === count($weights)) {
         for ($i = 0; $i < count($idgrades); $i++) {
            $idgrade = $idgrades[$i];
            $idbarang = $items[$i];
            $weight = $weights[$i];
            $price = formatAngka($prices[$i]);
            $discount = formatAngka($discounts[$i]);
            $discountrp = formatAngka($discountrps[$i]);
            $amount = formatAngka($amounts[$i]);

            // Periksa sintaks query, pastikan semua kolom ada dan nilai string menggunakan tanda kutip
            $queryInvoicedetail = "INSERT INTO invoicedetail (idinvoice, idgrade, idbarang, weight, price, discount, discountrp, amount)
                        VALUES ($lastInsertedId, $idgrade, $idbarang, $weight, $price, $discount, $discountrp, $amount)";

            $resultInvoicedetail = mysqli_query($conn, $queryInvoicedetail);

            if (!$resultInvoicedetail) {
               echo "Error: " . mysqli_error($conn);
               exit; // Menghentikan proses lebih lanjut jika terjadi kesalahan
            }
         }
         header("Location: index.php");
      }
   } else {
      echo "Error: " . mysqli_error($conn);
   }
}
