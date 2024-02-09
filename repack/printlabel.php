<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "../dist/vendor/autoload.php";
require "seriallabelrepack.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Query untuk mendapatkan nama barang
   $idbarang = $_POST['idbarang'];
   $note  = $_POST['note'];
   $origin = $_POST['origin'];
   $idrepack = $_POST['idrepack'];
   $idgrade = $_POST['idgrade'];
   $packdate = $_POST['packdate'];
   $exp = null;
   $barcode = $origin . $kodeauto;
   $tenderstreachActive = isset($_POST['tenderstreach']) ? true : false;
   $pembulatan = isset($_POST['pembulatan']) ? true : false;
   $qty = null;
   $pcs = null;
   $qtyPcsInput = $_POST['qty'];
   $_SESSION['idbarang'] = $_POST['idbarang'];
   $_SESSION['idgrade'] = $_POST['idgrade'];
   $_SESSION['packdate'] = $packdate;
   $_SESSION['origin'] = $_POST['origin'];
   $_SESSION['tenderstreach'] = $tenderstreachActive;
   $_SESSION['pembulatan'] = $pembulatan;
   $_SESSION['exp'] = $exp;

   if (strpos($qtyPcsInput, "/") !== false) {
      list($qty, $pcs) = explode("/", $qtyPcsInput . "-Pcs");
   } else {
      $qty = $qtyPcsInput;
   }

   // Memformat qty menjadi 2 digit desimal di belakang koma
   $qty = number_format($qty, 2, '.', '');

   // Query insert untuk tabel detailhasil
   $queryDetailhasil = "INSERT INTO detailhasil (idrepack, kdbarcode, idbarang, idgrade, qty, pcs, packdate, exp, note)
            VALUES ('$idrepack', '$barcode', '$idbarang', '$idgrade', '$qty', '$pcs', '$packdate', '$exp', '$note')";

   // Query insert untuk tabel stock
   $queryStock = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) 
                      VALUES ('$barcode', '$idgrade', '$idbarang', '$qty', '$pcs', '$packdate', '$origin')"; // Sesuaikan 'origin' sesuai kebutuhan

   // Eksekusi query
   if (!mysqli_query($conn, $queryDetailhasil) || !mysqli_query($conn, $queryStock)) {
      echo "Error: " . mysqli_error($conn);
   }
}

$query = "SELECT nmbarang FROM barang WHERE idbarang = $idbarang";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$nmbarang = $row['nmbarang'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Label</title>
</head>

<body>
   <table width="365" height="270" cellpadding="0">
      <tbody>
         <tr>
            <td height="23" colspan="4">
               <span style="font-size: 18px; color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  <strong>*YP*</strong>
               </span>
            </td>
         </tr>
         <tr>
            <td height="21" colspan="4">
               <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif; font-size: 14px;">
                  <strong>Prod By: PT. SANTI WIJAYA MEAT</strong>
               </span>
            </td>
         </tr>
         <tr>
            <td height="20" colspan="4">
               <span style="color: #000000; font-size: 10px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  Perum Asabri Blok B No 20 Rt. 01/05 Ds. Sukasirna Kec. Jonggol Kab. Bogor
               </span>
            </td>
         </tr>
         <tr>
            <td height="20" colspan="2">
               <span style="font-size: 18px; color: #000000;  font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  <strong><?= $nmbarang; ?></strong>
               </span>
            </td>
            <td colspan="2" rowspan="5" align="center" valign="middle">
               <img src=" ../dist/img/hi2.svg" alt="HALAL" height="100" align="absmiddle">
            </td>
         </tr>
         <tr>
            <td colspan=" 1" rowspan="2">
               <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  <?php
                  if ($pembulatan == true) { ?>
                     <span style="font-size: 30px"><strong><?= number_format($qty, 1); ?></strong></span>
                  <?php } else { ?>
                     <span style="font-size: 30px"><strong><?= number_format($qty, 2); ?></strong></span>
                  <?php } ?>
               </span>
            </td>
            <td height="20" style="font-size: 12px font-family 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
               <strong><i><?= $pcs; ?></i></strong>
            </td>
         </tr>
         <tr>
            <td height="20" style="font-style: normal; font-size: 12px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
               <?php if ($tenderstreachActive && (strpos($nmbarang, 'TENDERLOIN') !== false || strpos($nmbarang, 'SHORTLOIN') !== false || strpos($nmbarang, 'STRIPLOIN') !== false || strpos($nmbarang, 'RUMP') !== false || strpos($nmbarang, 'Cube roll') !== false || strpos($nmbarang, 'Operib') !== false)) { ?><strong><i>Tenderstreach</i></strong>
               <?php } else { ?>
                  &nbsp;
               <?php } ?>
            </td>
         </tr>
         <tr>
            <td height="20" style="font-size: 11px">
               <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">Packed Date&nbsp; :</span>
            </td>
            <td style="font-size: 11px">
               <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;"><?= date('d-M-Y', strtotime($packdate)); ?></span>
            </td>
         </tr>
         <?php if ($exp == null) { ?>
            <tr>
               <td style="font-size: 11px">
                  <span style="color: #000000; ">&nbsp;</span>
               </td>
               <td style="font-size: 11px">
                  <span style="color: #000000; ">&nbsp;</span>
               </td>
            </tr>
         <?php } else { ?>
            <tr>
               <td style="font-size: 11px">
                  <span style="color: #000000; ">Expired Date :</span>
               </td>
               <td style="font-size: 11px">
                  <span style="color: #000000; "><?= date('d-M-Y', strtotime($exp)); ?></span>
               </td>
            </tr>
         <?php }  ?>
         <tr>
            <td height="20" colspan="2">
               <span style="color: #000000; font-size: 12px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  <strong>KEEP CHILL / FROZEN</strong>
               </span>
            </td>
            <td style="font-size: 10px; text-align: center; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
               NO. 01011263450821<br>NKV CS-3201170-027
            </td>
         </tr>
         <tr>
            <!-- <td colspan="3">&nbsp;</td> -->
         </tr>
         <tr>
            <td height="20" colspan="4" align="center" valign="middle">
               <?php
               $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
               $label = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);
               echo '<img src="data:image/jpeg;base64,' . base64_encode($label) . '" alt="Barcode">';
               // echo $kdbarcode;
               ?>
            </td>
         </tr>
         <tr>
            <td colspan="4" align="center">
               <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  <?= $barcode; ?>
               </span>
            </td>
         </tr>
      </tbody>
   </table>
   <script>
      window.onload = function() {
         window.print();
         window.onafterprint = function() {
            window.location.href = 'detailhasil.php?id=<?= $idrepack ?>';
         };
         setTimeout(function() {
            window.close();
         }, 500); // Menunda penutupan jendela setelah 0,5 detik
      };
   </script>
</body>

</html>