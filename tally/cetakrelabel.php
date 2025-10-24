<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../dist/vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   http_response_code(405);
   exit('Method Not Allowed');
}

$idusers       = (int)($_SESSION['idusers'] ?? 0);
$kdbarcode     = trim($_POST['kdbarcode'] ?? '');
$idtally       = (int)($_POST['idtally'] ?? 0);
$idtallydetail = (int)($_POST['idtallydetail'] ?? 0);
$idgrade       = (int)($_POST['idgrade'] ?? 0);          // ← bukan array lagi
$packdate      = $_POST['packdate'] ?? null;
$xpackdate     = $_POST['xpackdate'] ?? null;
$exp_input     = $_POST['exp'] ?? null;
$pcs_post      = $_POST['pcs'] ?? null;

$tenderstreachActive = isset($_POST['tenderstreach']);
$pembulatan          = isset($_POST['pembulatan']);

if ($idusers <= 0 || !$kdbarcode || $idtally <= 0 || $idtallydetail <= 0 || !$packdate) {
   exit('Parameter tidak lengkap.');
}

// Ambil record resmi untuk validasi & nmbarang (jangan percaya POST untuk qty, xpcs, idbarang)
$sql = "
SELECT td.idtallydetail, td.idtally, td.barcode, td.idbarang, td.idgrade, td.weight, td.pcs, td.pod,
       b.nmbarang
FROM tallydetail td
LEFT JOIN barang b ON b.idbarang = td.idbarang
WHERE td.idtallydetail = ? AND td.idtally = ? AND td.barcode = ?
LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iis', $idtallydetail, $idtally, $kdbarcode);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
   exit('Record tidak valid.');
}
$rec = $res->fetch_assoc();

$idbarang = (int)$rec['idbarang'];           // kebenaran dari DB
$nmbarang = $rec['nmbarang'] ?? '';
$qty      = (float)$rec['weight'];           // qty asli dari DB
$xpcs     = (int)$rec['pcs'];                // pcs lama (xpcs)
$pcs      = max(0, (int)$pcs_post);          // pcs baru dari input
$exp      = (!empty($exp_input)) ? $exp_input : null;

// Update pcs & packdate di tallydetail
$upd = $conn->prepare("UPDATE tallydetail SET pcs=?, pod=? WHERE idtallydetail=? LIMIT 1");
$upd->bind_param('isi', $pcs, $packdate, $idtallydetail);
if (!$upd->execute()) {
   exit('Gagal update tallydetail');
}

// Insert ke relabel (exp boleh NULL), pcs di tabel relabel saat ini char(5)
$pcs_str = (string)$pcs;
$ins = $conn->prepare("
INSERT INTO relabel (idbarang, qty, xpackdate, idgrade, pcs, xpcs, packdate, exp, kdbarcode, iduser)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$ins->bind_param(
   'idssiisssi',
   $idbarang,   // i
   $qty,        // d
   $xpackdate,  // s
   $idgrade,    // i
   $pcs_str,    // s (char5)
   $xpcs,       // i
   $packdate,   // s (DATE)
   $exp,        // s|null (DATE) → null disimpan NULL
   $kdbarcode,  // s
   $idusers     // i
);
if (!$ins->execute()) {
   exit('Gagal insert relabel');
}

// Helper aman HTML
function h($v)
{
   return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Tenderstreach case-insensitive
$keywords = ['TENDERLOIN', 'SHORTLOIN', 'STRIPLOIN', 'RUMP', 'CUBE ROLL', 'OPERIB'];
$showTender = $tenderstreachActive && preg_match('/(' . implode('|', array_map('preg_quote', $keywords)) . ')/i', $nmbarang);
?>
<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <title>Label</title>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
   <!-- === LAYOUT TETAP: ukuran & susunan baris/kolom tidak diubah === -->
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
                  <strong><?= h($nmbarang); ?></strong>
               </span>
            </td>
            <td colspan="2" rowspan="5" align="center" valign="middle">
               <img src="../dist/img/halal.png" alt="HALAL" height="100" align="absmiddle">
            </td>
         </tr>
         <tr>
            <td colspan="1" rowspan="2">
               <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  <span style="font-size: 30px"><strong><?= number_format($qty, $pembulatan ? 1 : 2); ?></strong></span>
               </span>
            </td>
            <td height="20" style="font-size: 12px font-family 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
               <?php if ($pcs > 0): ?>
                  <strong><i><?= (int)$pcs; ?>-Pcs</i></strong>
               <?php endif; ?>
            </td>
         </tr>
         <tr>
            <td height="20" style="font-style: normal; font-size: 12px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
               <?php if ($showTender): ?><strong><i>Tenderstreach</i></strong><?php else: ?>&nbsp;<?php endif; ?>
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
         <?php if (!empty($exp)): ?>
            <tr>
               <td style="font-size: 11px">
                  <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                     Expired Date&nbsp; :
                  </span>
               </td>
               <td style="font-size: 11px">
                  <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                     <?= date('d-M-Y', strtotime($exp)); ?>
                  </span>
               </td>
            </tr>
         <?php else: ?>
            <tr>
               <td style="font-size: 11px"><span style="color:#000000;">&nbsp;</span></td>
               <td style="font-size: 11px"><span style="color:#000000;">&nbsp;</span></td>
            </tr>
         <?php endif; ?>
         <tr>
            <td height="20" colspan="2">
               <span style="color: #000000; font-size: 12px; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  <strong><?= in_array($idgrade, [1, 3], true) ? "KEEP CHILL 0°C" : "KEEP FROZEN -18°C"; ?></strong>
               </span>
            </td>
            <td style="font-size: 10px; text-align: center; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
               ID00110015321510124<br>RPHR 3201170-027
            </td>
         </tr>
         <tr></tr>
         <tr>
            <td height="20" colspan="4" align="center" valign="middle">
               <?php
               $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
               $barcode = $generator->getBarcode($kdbarcode, $generator::TYPE_CODE_128);
               echo '<img src="data:image/jpeg;base64,' . base64_encode($barcode) . '" alt="Barcode">';
               ?>
            </td>
         </tr>
         <tr>
            <td colspan="4" align="center">
               <span style="color: #000000; font-family: 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
                  <?= h($kdbarcode); ?>
               </span>
            </td>
         </tr>
      </tbody>
   </table>
   <script>
      window.onload = function() {
         window.print();
         window.onafterprint = function() {
            window.location.href = 'tallydetail.php?id=<?= (int)$idtally ?>&stat=updated';
         };
         setTimeout(function() {
            window.close();
         }, 500);
      };
   </script>
</body>

</html>