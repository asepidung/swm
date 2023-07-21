<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

// Fungsi untuk menghilangkan karakter koma atau titik dari angka dan menyesuaikan pemisah desimal
function removeGroupingDigit($angka)
{
   $decimal_separator = "."; // Sesuaikan dengan pemisah desimal di wilayah pengguna (contoh: "." untuk pemisah desimal titik)
   $angka = str_replace(",", "", $angka); // Menghilangkan karakter koma dari angka
   $angka = str_replace(".", $decimal_separator, $angka); // Mengganti karakter titik dengan pemisah desimal yang sesuai
   return $angka;
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
   $xamount = removeGroupingDigit($_POST["xamount"]); // Menghilangkan karakter koma atau titik dari xamount
   $xdiscount = removeGroupingDigit($_POST["xdiscount"]); // Menghilangkan karakter koma atau titik dari xdiscount
   $tax = removeGroupingDigit($_POST["tax"]);
   $charge = removeGroupingDigit($_POST["charge"]);
   $downpayment = removeGroupingDigit($_POST["downpayment"]);
   $balance = removeGroupingDigit($_POST["balance"]); // Menghilangkan karakter koma atau titik dari balance

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
      // ... (kode sebelumnya tetap sama)

      // Update nilai kolom status di tabel do berdasarkan $iddo
      $queryUpdateStatus = "UPDATE do SET status = 'Invoiced' WHERE iddo = $iddo";
      $resultUpdateStatus = mysqli_query($conn, $queryUpdateStatus);

      if (!$resultUpdateStatus) {
         echo "Error updating status: " . mysqli_error($conn);
         exit; // Menghentikan proses lebih lanjut jika terjadi kesalahan
      }

      header("Location: index.php");
   } else {
      echo "Error: " . mysqli_error($conn);
   }
}
