<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['submit'])) {
   $donumber = $_POST['donumber'];
   $deliverydate = $_POST['deliverydate'];
   $idcustomer = $_POST['idcustomer'];
   $po = $_POST['po'];
   $driver = $_POST['driver'];
   $plat = $_POST['plat'];
   $xbox = $_POST['xbox'];
   $xweight = $_POST['xweight'];
   $note = $_POST['note'];
   $idusers = $_SESSION['idusers'];

   // Query INSERT ke tabel "do"
   $query_do = "INSERT INTO do (donumber, deliverydate, idcustomer, po, driver, plat, note, xbox, xweight, idusers) VALUES (?,?,?,?,?,?,?,?,?,?)";
   $stmt_do = $conn->prepare($query_do);
   $stmt_do->bind_param("ssissssidi", $donumber, $deliverydate, $idcustomer, $po, $driver, $plat, $note, $xbox, $xweight, $idusers);
   $stmt_do->execute();

   // Mendapatkan ID terakhir yang di-generate dalam tabel "do"
   $last_id = $stmt_do->insert_id;

   // Query INSERT ke tabel "dodetail"
   $idgrade = $_POST['idgrade'];
   $idbarang = $_POST['idbarang'];
   $box = $_POST['box'];
   $weight = $_POST['weight'];
   $notes = $_POST['notes'];

   $query_dodetail = "INSERT INTO dodetail (iddo, idgrade, idbarang, box, weight, notes) VALUES (?,?,?,?,?,?)";
   $stmt_dodetail = $conn->prepare($query_dodetail);

   // Bind parameter dan eksekusi query INSERT sebanyak item yang ada
   for ($i = 0; $i < count($idgrade); $i++) {
      $stmt_dodetail->bind_param("iiiids", $last_id, $idgrade[$i], $idbarang[$i], $box[$i], $weight[$i], $notes[$i]);
      $stmt_dodetail->execute();
   }

   $stmt_dodetail->close();
   $stmt_do->close();
   $conn->close();

   // Redirect ke halaman lain setelah proses INSERT selesai
   // header("location: do.php");
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
</head>

<body>
   <p>Delivery Order<br />
      <strong>PT. SANTI WIJAYA MEAT</strong><br />
      Jl. Perum Asabri Blok B Desa Sukasirna Kec. Jonggol Kab. Bogor Telp. 021-89935103
   </p>

   <hr />
   <table border="0" cellpadding="0" cellspacing="0">
      <tbody>
         <tr>
            <td style="width:137px">DO Numb</td>
            <td style="width:383px">: DO-SWM/23/VII/00001</td>
            <td style="width:155px">Delivery Date</td>
            <td style="width:513px">: Wed, 06 Juli 2023</td>
         </tr>
         <tr>
            <td style="width:137px">SO NUmb</td>
            <td style="height:0px; width:383px">: -</td>
            <td style="height:0px; width:155px">PO Number</td>
            <td style="height:0px; width:513px">: P0-000-000-1234</td>
         </tr>
         <tr>
            <td style="width:137px">Sales Ref</td>
            <td style="width:383px">: MURYANI</td>
            <td style="width:155px">Customer</td>
            <td style="width:513px">: Lion Superindo DCA Cikarang</td>
         </tr>
         <tr>
            <td style="width:137px">Driver</td>
            <td style="width:383px">: TOPIK</td>
            <td style="width:155px">Address</td>
            <td colspan="1" rowspan="3" style="width:513px">: Karyadeka Industri Cikarang</td>
         </tr>
         <tr>
            <td style="width:137px">No POL</td>
            <td style="width:383px">: F 2111 FAB</td>
            <td style="width:155px">&nbsp;</td>
         </tr>
      </tbody>
   </table>

</body>

</html>