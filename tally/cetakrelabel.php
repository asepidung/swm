<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../dist/vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   http_response_code(405);
   exit('Method Not Allowed');
}

// --- Ambil parameter minimal ---
$idusers       = (int)($_SESSION['idusers'] ?? 0);
$kdbarcode     = trim($_POST['kdbarcode'] ?? '');
$idtally       = (int)($_POST['idtally'] ?? 0);
$idtallydetail = (int)($_POST['idtallydetail'] ?? 0);
$packdate      = $_POST['packdate'] ?? null;     // yyyy-mm-dd
$xpackdate     = $_POST['xpackdate'] ?? null;    // pod lama (opsional)
$exp_input     = $_POST['exp'] ?? null;          // yyyy-mm-dd atau kosong
$ph_input      = $_POST['ph'] ?? null;           // "5.4" .. "5.7" atau kosong

$tenderstreachActive = isset($_POST['tenderstreach']);
$pembulatan          = isset($_POST['pembulatan']);

if ($idusers <= 0 || $kdbarcode === '' || $idtally <= 0 || $idtallydetail <= 0 || !$packdate) {
   exit('Parameter tidak lengkap.');
}

// --- Ambil record resmi dari tallydetail (kebenaran data) ---
$sql = "
SELECT td.idtallydetail, td.idtally, td.barcode,
       td.idbarang, td.idgrade, td.weight, td.pcs, td.pod,
       b.nmbarang
FROM tallydetail td
LEFT JOIN barang b ON b.idbarang = td.idbarang
WHERE td.idtallydetail = ? AND td.idtally = ? AND td.barcode = ?
LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) exit('DB Error (prepare)');
$stmt->bind_param('iis', $idtallydetail, $idtally, $kdbarcode);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) exit('Record tidak valid.');
$rec = $res->fetch_assoc();

// --- Nilai dari DB (dipakai untuk cetak & relabel) ---
$idbarang = (int)$rec['idbarang'];
$idgrade  = (int)$rec['idgrade'];
$nmbarang = (string)($rec['nmbarang'] ?? '');
$qty      = (float)$rec['weight'];   // DECIMAL(6,2)
$xpcs     = (int)$rec['pcs'];        // pcs lama (xpcs)

// --- Normalisasi nilai input yang boleh berubah: exp & ph ---
$exp = ($exp_input !== null && $exp_input !== '') ? $exp_input : null;

$phFloat = null; // NULL jika dikosongkan
if ($ph_input !== null && $ph_input !== '') {
   $rawPh = str_replace(',', '.', (string)$ph_input);
   $phVal = filter_var($rawPh, FILTER_VALIDATE_FLOAT);
   if ($phVal === false) {
      exit('Nilai pH tidak valid.');
   }
   if ($phVal < 5.4 || $phVal > 5.7) {
      exit('Nilai pH harus antara 5.4 dan 5.7.');
   }
   // Truncate ke 1 desimal (bukan pembulatan)
   $phFloat = floor($phVal * 10) / 10;
}

// --- Update tallydetail: pod + ph + exp (ph/exp bisa NULL) ---
if (is_null($phFloat)) {
   // pH kosong -> set NULL
   $upd = $conn->prepare("UPDATE tallydetail SET pod = ?, ph = NULL, exp = ? WHERE idtallydetail = ? LIMIT 1");
   if (!$upd) exit('Gagal prepare update tallydetail (NULL ph): ' . $conn->error);
   $upd->bind_param('ssi', $packdate, $exp, $idtallydetail); // $exp bisa NULL
} else {
   // pH ada -> simpan angka 1 desimal
   $upd = $conn->prepare("UPDATE tallydetail SET pod = ?, ph = ?, exp = ? WHERE idtallydetail = ? LIMIT 1");
   if (!$upd) exit('Gagal prepare update tallydetail (ph angka): ' . $conn->error);
   $upd->bind_param('sdsi', $packdate, $phFloat, $exp, $idtallydetail); // $exp bisa NULL
}
if (!$upd->execute()) exit('Gagal update tallydetail: ' . $upd->error);
$upd->close();

// --- Insert ke relabel (untuk jejak cetak) ---
$pcs_str = (string)$xpcs; // relabel.pcs = CHAR(5)
$ins = $conn->prepare("
  INSERT INTO relabel
    (idbarang, qty, xpackdate, idgrade, ph, pcs, xpcs, packdate, exp, kdbarcode, iduser)
  VALUES
    (?,        ?,   ?,         ?,       ?,  ?,   ?,    ?,        ?,   ?,         ?)
");
if (!$ins) exit('Gagal prepare insert relabel: ' . $conn->error);
// tipe bind: i d s i d s i s s s i
$ins->bind_param(
   'idsidsisssi',
   $idbarang,   // i
   $qty,        // d
   $xpackdate,  // s (pod lama)
   $idgrade,    // i
   $phFloat,    // d (bisa NULL)
   $pcs_str,    // s
   $xpcs,       // i
   $packdate,   // s (pod baru)
   $exp,        // s (bisa NULL)
   $kdbarcode,  // s
   $idusers     // i
);
if (!$ins->execute()) exit('Gagal insert relabel: ' . $ins->error);
$ins->close();

// --- Helper aman HTML ---
function h($v)
{
   return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// --- Tenderstreach (opsional) ---
$keywords   = ['TENDERLOIN', 'SHORTLOIN', 'STRIPLOIN', 'RUMP', 'CUBE ROLL', 'OPERIB'];
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
   <!-- === LAYOUT TETAP === -->
   <table width="365" height="270" cellpadding="0">
      <tbody>
         <tr>
            <td height="23" colspan="4">
               <span style="font-size:18px;color:#000;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
                  <strong>*YP*</strong>
               </span>
            </td>
         </tr>
         <tr>
            <td height="21" colspan="4">
               <span style="color:#000;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;font-size:14px;">
                  <strong>Prod By: PT. SANTI WIJAYA MEAT</strong>
               </span>
            </td>
         </tr>
         <tr>
            <td height="20" colspan="4">
               <span style="color:#000;font-size:10px;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
                  Perum Asabri Blok B No 20 Rt. 01/05 Ds. Sukasirna Kec. Jonggol Kab. Bogor
               </span>
            </td>
         </tr>

         <tr>
            <td height="20" colspan="2">
               <span style="font-size:18px;color:#000;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
                  <strong><?= h($nmbarang); ?></strong>
               </span>
            </td>
            <td colspan="2" rowspan="5" align="center" valign="middle">
               <img src="../dist/img/halal.png" alt="HALAL" height="100" align="absmiddle">
            </td>
         </tr>

         <tr>
            <td colspan="1" rowspan="2">
               <span style="font-size:30px; font-family: Arial, Helvetica, sans-serif;">
                  <strong><?= number_format($qty, 2); ?></strong>
                  <sup style="font-size:14px;">Kg</sup>
               </span>
            </td>
            <td height="20" style="font-size:12px;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
               <?php if ($xpcs > 0): ?>
                  <strong><i><?= (int)$xpcs; ?>-Pcs</i></strong>
               <?php endif; ?>
            </td>
         </tr>

         <tr>
            <td height="20" style="font-style:normal;font-size:12px;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
               <?php if ($phFloat !== null): ?>
                  <span style="font-size:12px">pH <?= number_format($phFloat, 1); ?></span>
               <?php else: ?>
                  &nbsp;
               <?php endif; ?>
               <?php /* Jika ingin tampil Tenderstreach juga:
          <?php if ($showTender): ?><strong><i>&nbsp;Tenderstreach</i></strong><?php endif; ?>
          */ ?>
            </td>
         </tr>

         <tr>
            <td height="20" style="font-size:11px">
               <span style="color:#000;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">Packed Date&nbsp; :</span>
            </td>
            <td style="font-size:11px">
               <span style="color:#000;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
                  <?= date('d-M-Y', strtotime($packdate)); ?>
               </span>
            </td>
         </tr>

         <?php if (!empty($exp)): ?>
            <tr>
               <td style="font-size:11px"><span style="color:#000;">Expired Date :</span></td>
               <td style="font-size:11px"><span style="color:#000;"><?= date('d-M-Y', strtotime($exp)); ?></span></td>
            </tr>
         <?php else: ?>
            <tr>
               <td style="font-size:11px"><span style="color:#000;">&nbsp;</span></td>
               <td style="font-size:11px"><span style="color:#000;">&nbsp;</span></td>
            </tr>
         <?php endif; ?>

         <tr>
            <td height="20" colspan="2">
               <span style="color:#000;font-size:12px;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
                  <strong><?= in_array($idgrade, [1, 3], true) ? "KEEP CHILL 0°C" : "KEEP FROZEN -18°C"; ?></strong>
               </span>
            </td>
            <td style="font-size:10px;text-align:center;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
               ID00110015321510124<br>RPHR 3201170-027
            </td>
         </tr>

         <tr></tr>

         <tr>
            <td height="20" colspan="4" align="center" valign="middle">
               <?php
               try {
                  $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
                  $barcodeImg = $generator->getBarcode($kdbarcode, $generator::TYPE_CODE_128);
                  echo '<img src="data:image/jpeg;base64,' . base64_encode($barcodeImg) . '" alt="Barcode">';
               } catch (Throwable $e) {
                  echo '<small>Barcode tidak tersedia.</small>';
               }
               ?>
            </td>
         </tr>

         <tr>
            <td colspan="4" align="center">
               <span style="color:#000;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
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