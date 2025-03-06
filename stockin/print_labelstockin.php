<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location: ../verifications/login.php");
    exit;
}

require "../konak/conn.php";
require "../dist/vendor/autoload.php"; // Untuk generator barcode

// Validasi dan sanitasi input
if (!isset($_GET['idstockin']) || empty($_GET['idstockin'])) {
    die('Error: idstockin is missing or invalid.');
}
$idstockin = intval($_GET['idstockin']);

// Ambil data label dan barang berdasarkan idstockin
$query = "SELECT si.*, b.nmbarang 
          FROM stockin si 
          JOIN barang b ON si.idbarang = b.idbarang 
          WHERE si.id = $idstockin";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Pastikan data ditemukan
if (!$data) {
    header("Location: stockin.php");
    exit;
}

// Variabel untuk digunakan di halaman cetak
$nmbarang = $data['nmbarang'];
$qty = $data['qty'];
$pcs = $data['pcs'];
$pod = $data['pod'];
$idgrade = $data['idgrade'];
$kdbarcode = $data['kdbarcode'];
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
    <table width="365" height="270" cellpadding="0" border="0">
        <tr>
            <td colspan="3">
                <span style="font-size: 18px; color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                    <strong>*YP*</strong>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif; font-size: 14px;">
                    <strong>Prod By: PT. SANTI WIJAYA MEAT</strong>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span style="color: #000000; font-size: 10px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                    Perum Asabri Blok B No 20 Rt. 01/05 Ds. Sukasirna Kec. Jonggol Kab. Bogor
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span style="font-size: 18px; color: #000000;  font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                    <strong><?= $nmbarang; ?></strong>
                </span>
            </td>
            <td rowspan="5" align="center">
                <img src="../dist/img/halal.png" alt="HALAL" height="100" align="absmiddle">
            </td>
        </tr>
        <tr>
            <td rowspan="2">
                <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                    <span style="font-size: 30px"><strong><?= number_format($qty, 2); ?></strong></span>
                </span>
            </td>
            <td>
                <?php if ($pcs > 0) { ?>
                    <strong><i><?= $pcs . "-Pcs"; ?></i></strong>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>
                <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">Packed Date&nbsp; :
                </span>
            </td>
            <td>
                <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;"><?= date('d-M-Y', strtotime($pod)); ?></span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">
                <span style="color: #000000; font-size: 14px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
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
            <td align="center">
                <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                    ID00110015321510124<br>RPHR 3201170-027
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <?php
                $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
                $barcode = $generator->getBarcode($kdbarcode, $generator::TYPE_CODE_128);
                echo '<img src="data:image/jpeg;base64,' . base64_encode($barcode) . '" alt="Barcode">';
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                    <?= $kdbarcode; ?>
                </span>
            </td>
        </tr>
    </table>
    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.location.href = 'index.php';
            };
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>
</body>

</html>