<?php
session_start();
require "../konak/conn.php";
require "../dist/vendor/autoload.php";
if (isset($_POST['submit'])) {
  // Query untuk mendapatkan nama barang
  $product = $_POST['product'];
  $query = "SELECT nmbarang FROM barang WHERE idbarang = $product";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $idbarang = $_POST['product'];
  $nmbarang = $row['nmbarang'];
  // $exp = $_POST['exp'];
  // $expadd = isset($_POST['exp']) && !empty($_POST['exp']) ? date('d-M-Y', strtotime($_POST['exp'])) : null;
  $packdate = $_POST['packdate'];
  $packdateadd = date('d-M-Y', strtotime($_POST['packdate']));
  $idboning = $_POST['idboning'];
  $idboningWithPrefix = $_POST['idboningWithPrefix'];
  $kdbarcode = $_POST['kdbarcode'];
  $tenderstreachActive = isset($_POST['tenderstreach']) && $_POST['tenderstreach'] === 'on';
  // Memeriksa dan memecah nilai qty dan pcs
  $qty = null;
  $pcs = null;
  $qtyPcsInput = $_POST['qty'];
  $_SESSION['product'] = $product;
  $_SESSION['packdate'] = $packdate;
  // $_SESSION['exp'] = $exp;
  if (strpos($qtyPcsInput, "/") !== false) {
    list($qty, $pcs) = explode("/", $qtyPcsInput . "-Pcs");
  } else {
    $qty = $qtyPcsInput;
  }
  // Memformat qty menjadi 2 digit desimal di belakang koma
  $qty = number_format($qty, 2);
}
$query = mysqli_query($conn, "INSERT INTO labelboning (idboning, idbarang, qty, pcs, packdate, kdbarcode)
VALUES ('$idboningWithPrefix', '$idbarang', $qty, '$pcs', '$packdate', '$kdbarcode')");
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

  <table width="380" border="0" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td width="114" style="width: 112px">
          <span style="font-size: 18px; color: #000000; font-family: Tahoma, Geneva, sans-serif">
            <strong>*YP*</strong>
          </span>
        </td>
        <td width="149" style="width: 149px">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" style="width: 264px">
          <span style="color: #000000; font-family: Tahoma">
            <strong>Prod By: PT. SANTI WIJAYA MEAT</strong>
          </span>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="white-space: nowrap; width: 373px">
          <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif; font-size: 12px;">
            Jl. Perum Asabri Blok B No 20 Rt. 01/05 Ds. Sukasirna<br>Kec. Jonggol Kab. Bogor
          </span>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="width: 264px">
          <span style="font-size: 22px; color: #000000; font-family: Tahoma, Geneva, sans-serif">
            <strong><?= $nmbarang; ?></strong>
          </span>
        </td>
        <td colspan="2" rowspan="7" align="center">
          <img src=" ../dist/img/hi.svg" alt="HALAL" height="145">
        </td>
      </tr>
      <tr>
        <td colspan=" 1" rowspan="2" style="width: 112px">
          <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">
            <span style="font-size: 24px"><strong><?= $qty; ?></strong></span>
          </span>
        </td>
        <td>
          <strong><i><?= $pcs; ?></i></strong>
        </td>
      </tr>
      <tr>
        <td style="width: 149px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif; font-style: normal; font-size: 14px;">
          <?php if ($tenderstreachActive && (strpos($nmbarang, 'TENDERLOIN') !== false || strpos($nmbarang, 'SHORTLOIN') !== false || strpos($nmbarang, 'STRIPLOIN') !== false || strpos($nmbarang, 'RUMP') !== false || strpos($nmbarang, 'Cube roll') !== false || strpos($nmbarang, 'Operib') !== false)) { ?>
            &#9733;<strong><i>Tenderstreach</i></strong>
          <?php } else { ?>
            &nbsp;
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td style="font-size: 12px">
          <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">Packed Date&nbsp; :</span>
        </td>
        <td style="font-size: 12px">
          <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif"><?= $packdateadd; ?></span>
        </td>
      </tr>
      <!-- <?php if ($exp !== null) { ?> -->
      <!-- <tr>
          <td style="font-size: 12px">
            <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">Expired Date :</span>
          </td>
          <td style="font-size: 12px">
            <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif"><?= $expadd; ?></span>
          </td>
        </tr> -->
      <!-- <?php } else { ?> -->
      <tr>
        <td style="font-size: 12px; height: 15px;">&nbsp;</td>
        <td style="font-size: 12px; height: 15px;">&nbsp;</td>
      </tr>
      <!-- <?php } ?> -->
      <tr>
      <tr>
        <td colspan="2" rowspan="1" style="width: 264px">
          <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">
            <strong>KEEP CHILL/FROZEN</strong>
          </span>
        </td>
      </tr>
      <tr>
        <td colspan="4" align="center">
          <?php
          $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
          $barcode = $generator->getBarcode($kdbarcode, $generator::TYPE_CODE_128);
          echo '<img src="data:image/jpeg;base64,' . base64_encode($barcode) . '" alt="Barcode">';
          // echo $kdbarcode;
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="4" align="center">
          <span style="color: #000000; font-family: Tahoma, Geneva, sans-serif">
            <?= $kdbarcode; ?>
          </span>
        </td>
      </tr>
    </tbody>
  </table>
  <script>
    window.onload = function() {
      window.print();
      window.onafterprint = function() {
        window.location.href = 'labelboning.php?id=<?php echo $idboning; ?>';
      };
      setTimeout(function() {
        window.close();
      }, 500); // Menunda penutupan jendela setelah 0,5 detik
    };
  </script>
</body>

</html>