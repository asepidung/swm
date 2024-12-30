<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location: ../verifications/login.php");
    exit();
}
require "../konak/conn.php";
require "../dist/vendor/autoload.php";
require "seriallabelrepack.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi dan sanitasi input
    $idbarang = mysqli_real_escape_string($conn, $_POST['idbarang']);
    $note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
    $origin = mysqli_real_escape_string($conn, $_POST['origin']);
    $idrepack = mysqli_real_escape_string($conn, $_POST['idrepack']);
    $idgrade = mysqli_real_escape_string($conn, $_POST['idgrade']);
    $packdate = mysqli_real_escape_string($conn, $_POST['packdate']);
    $exp = null; // Tetap null sesuai kebutuhan

    $barcode = $origin . $idrepack . $kodeauto;
    $tenderstreachActive = isset($_POST['tenderstreach']);
    $pembulatan = isset($_POST['pembulatan']);
    $qty = null;
    $pcs = null;

    $qtyPcsInput = $_POST['qty'];

    // Simpan data untuk referensi session
    $_SESSION['idbarang'] = $idbarang;
    $_SESSION['idgrade'] = $idgrade;
    $_SESSION['packdate'] = $packdate;
    $_SESSION['origin'] = $origin;
    $_SESSION['tenderstreach'] = $tenderstreachActive;
    $_SESSION['pembulatan'] = $pembulatan;
    $_SESSION['exp'] = $exp;

    // Mengolah input qty dan pcs
    if (strpos($qtyPcsInput, "/") !== false) {
        list($qty, $pcs) = explode("/", $qtyPcsInput);
    } else {
        $qty = $qtyPcsInput;
    }

    // Format qty menjadi 2 desimal jika valid
    $qty = is_numeric($qty) ? number_format($qty, 2, '.', '') : null;
    $pcs = is_numeric($pcs) ? $pcs : null;

    if ($qty === null || $qty <= 0) {
        die("Invalid quantity.");
    }

    // Query insert untuk tabel detailhasil
    $queryDetailhasil = "INSERT INTO detailhasil (idrepack, kdbarcode, idbarang, idgrade, qty, pcs, packdate, exp, note)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtDetailhasil = mysqli_prepare($conn, $queryDetailhasil);
    mysqli_stmt_bind_param($stmtDetailhasil, "isiiissss", $idrepack, $barcode, $idbarang, $idgrade, $qty, $pcs, $packdate, $exp, $note);

    // Query insert untuk tabel stock
    $queryStock = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtStock = mysqli_prepare($conn, $queryStock);
    mysqli_stmt_bind_param($stmtStock, "siidsss", $barcode, $idgrade, $idbarang, $qty, $pcs, $packdate, $origin);

    // Eksekusi query
    if (mysqli_stmt_execute($stmtDetailhasil) && mysqli_stmt_execute($stmtStock)) {
        // Berhasil menyimpan data
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Tutup statement
    mysqli_stmt_close($stmtDetailhasil);
    mysqli_stmt_close($stmtStock);
}

// Query untuk mendapatkan nama barang
$query = "SELECT nmbarang FROM barang WHERE idbarang = ?";
$stmtBarang = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmtBarang, "i", $idbarang);
mysqli_stmt_execute($stmtBarang);
mysqli_stmt_bind_result($stmtBarang, $nmbarang);
mysqli_stmt_fetch($stmtBarang);
mysqli_stmt_close($stmtBarang);
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
               <img src=" ../dist/img/halal.png" alt="HALAL" height="100" align="absmiddle">
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
                  <strong>
                     <?php
                     if ($idgrade == 1 or $idgrade == 3) {
                        echo "KEEP CHILL 0°C";
                     } else {
                        echo "KEEP FROZEN -18°C";
                     }
                     ?>
                  </strong>
               </span>
            </td>
            <td style="font-size: 10px; text-align: center; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
               ID00110015321510124<br>RPHR 3201170-027
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