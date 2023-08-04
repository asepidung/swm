<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['idinvoice'])) {
   $idinvoice = $_GET['idinvoice'];
   echo "ID Invoice: " . $idinvoice . "<br>"; // Pastikan nilai idinvoice sudah benar
} else {
   echo "ID invoice tidak dikenal";
   exit; // Hentikan eksekusi jika idinvoice tidak dikenali
}

$userid = $_SESSION['userid'];
$query = "SELECT invoice.*, customers.nama_customer, users.userid
          FROM invoice 
          INNER JOIN customers ON invoice.idcustomer = customers.idcustomer 
          INNER JOIN users ON invoice.idusers = users.idusers
          WHERE invoice.idinvoice = '$idinvoice'";
$result = mysqli_query($conn, $query);
if (!$result) {
   die('Error: ' . mysqli_error($conn)); // Cetak pesan error jika query gagal
}

$row_invoice = mysqli_fetch_assoc($result);

if (!$row_invoice) {
   echo "Data invoice dengan ID Invoice $idinvoice tidak ditemukan.";
   exit; // Hentikan eksekusi jika data invoice tidak ditemukan
}

$query_detail = "SELECT invoicedetail.*, barang.kdbarang, barang.nmbarang, grade.nmgrade
                FROM invoicedetail
                INNER JOIN grade ON invoicedetail.idgrade = grade.idgrade
                INNER JOIN barang ON invoicedetail.idbarang = barang.idbarang
                WHERE invoicedetail.idinvoice = '$idinvoice'";
$result_detail = mysqli_query($conn, $query_detail);

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
</head>

<body>
   <img src="../dist/img/pib.png" alt="headerinvoice" width="100%">
   <p align="right">Invoice Number : <?= $row_invoice['noinvoice']; ?></p>
   <table width="100%" border="1">
      <tr>
         <td></td>
         <td></td>
      </tr>
   </table>
</body>

</html>