<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../dist/vendor/autoload.php";
require "seriallabelrepack.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // --- Sanitasi dasar ---
   $idbarang  = mysqli_real_escape_string($conn, $_POST['idbarang']);
   $note      = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
   $origin    = mysqli_real_escape_string($conn, $_POST['origin']);
   $idrepack  = mysqli_real_escape_string($conn, $_POST['idrepack']);
   $idgrade   = mysqli_real_escape_string($conn, $_POST['idgrade']);
   $packdate  = mysqli_real_escape_string($conn, $_POST['packdate']); // yyyy-mm-dd
   $exp_input = $_POST['exp'] ?? '';   // yyyy-mm-dd atau ''
   $ph_input  = $_POST['ph'] ?? '';    // "5.4".."5.7" atau ''

   $qtyPcsInput = $_POST['qty'];

   // --- Bentuk barcode ---
   $barcode = $origin . $idrepack . $kodeauto;

   // Flag opsional
   $tenderstreachActive = isset($_POST['tenderstreach']);
   $pembulatan          = isset($_POST['pembulatan']);

   // --- Simpan untuk session (prefill selanjutnya) ---
   $_SESSION['idbarang']      = $idbarang;
   $_SESSION['idgrade']       = $idgrade;
   $_SESSION['packdate']      = $packdate;
   $_SESSION['origin']        = $origin;
   $_SESSION['tenderstreach'] = $tenderstreachActive;
   $_SESSION['pembulatan']    = $pembulatan;

   // Exp bisa kosong -> NULL
   $exp = ($exp_input !== null && $exp_input !== '') ? mysqli_real_escape_string($conn, $exp_input) : null;
   $_SESSION['exp'] = $exp ?? '';

   // --- Normalisasi & validasi pH (opsional) ---
   $phFloat = null;
   if ($ph_input !== null && $ph_input !== '') {
      $rawPh = str_replace(',', '.', (string)$ph_input);
      $phVal = filter_var($rawPh, FILTER_VALIDATE_FLOAT);
      if ($phVal === false) {
         die("Nilai pH tidak valid.");
      }
      if ($phVal < 5.4 || $phVal > 5.7) {
         die("Nilai pH harus antara 5.4 dan 5.7.");
      }
      // truncate ke 1 desimal
      $phFloat = floor($phVal * 10) / 10;
      $_SESSION['ph'] = number_format($phFloat, 1, '.', '');
   } else {
      $_SESSION['ph'] = '';
   }

   // --- Parsing qty/pcs ---
   $qty = null;
   $pcs = null;
   if (strpos($qtyPcsInput, "/") !== false) {
      list($qty, $pcs) = explode("/", $qtyPcsInput, 2);
   } else {
      $qty = $qtyPcsInput;
      $pcs = null;
   }

   // Normalisasi qty (koma→titik, 2 desimal)
   $qty = str_replace(',', '.', trim((string)$qty));
   $qty = is_numeric($qty) ? number_format((float)$qty, 2, '.', '') : null;

   // pcs ke integer (boleh null)
   if ($pcs !== null && $pcs !== '') {
      $pcs = preg_replace('/\D+/', '', (string)$pcs);
      $pcs = ($pcs === '') ? null : (int)$pcs;
   } else {
      $pcs = null;
   }

   if ($qty === null || (float)$qty <= 0) {
      die("Invalid quantity.");
   }

   // --- Siapkan nilai SQL aman untuk NULL ---
   $qtySql = $qty; // angka
   $pcsSql = is_null($pcs) ? "NULL" : (int)$pcs;
   $phSql  = is_null($phFloat) ? "NULL" : number_format($phFloat, 1, '.', '');
   $expSql = is_null($exp) ? "NULL" : "'" . mysqli_real_escape_string($conn, $exp) . "'";

   // --- Insert ke detailhasil: tambahkan kolom ph (dan exp bila ada di schema) ---
   // Jika tabel detailhasil TIDAK punya kolom exp, gunakan query tanpa exp (lihat blok alternatif di bawah)
   $queryDetailhasil = "
    INSERT INTO detailhasil
      (idrepack, kdbarcode, idbarang, idgrade, qty, pcs, packdate, ph, exp, note)
    VALUES
      ('$idrepack', '$barcode', '$idbarang', '$idgrade', $qtySql, $pcsSql, '$packdate', $phSql, $expSql, '$note')
  ";
   if (!mysqli_query($conn, $queryDetailhasil)) {
      die("Error inserting into detailhasil: " . mysqli_error($conn));
   }

   /* --- ALTERNATIF (kalau detailhasil belum punya kolom exp):
  $queryDetailhasil = "
    INSERT INTO detailhasil
      (idrepack, kdbarcode, idbarang, idgrade, qty, pcs, packdate, ph, note)
    VALUES
      ('$idrepack', '$barcode', '$idbarang', '$idgrade', $qtySql, $pcsSql, '$packdate', $phSql, '$note')
  ";
  */

   // --- Insert ke stock (dengan ph & exp) ---
   // Pastikan tabel stock punya kolom exp; jika tidak, hapus $expSql & kolomnya.
   $queryStock = "
    INSERT INTO stock
      (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin, ph)
    VALUES
      ('$barcode', '$idgrade', '$idbarang', $qtySql, $pcsSql, '$packdate', '$origin', $phSql)
  ";
   if (!mysqli_query($conn, $queryStock)) {
      die("Error inserting into stock: " . mysqli_error($conn));
   }
}

// --- Ambil nama barang untuk label ---
$nmbarang = '';
if (!empty($idbarang)) {
   $stmtBarang = mysqli_prepare($conn, "SELECT nmbarang FROM barang WHERE idbarang = ?");
   mysqli_stmt_bind_param($stmtBarang, "i", $idbarang);
   mysqli_stmt_execute($stmtBarang);
   mysqli_stmt_bind_result($stmtBarang, $nmbarang);
   mysqli_stmt_fetch($stmtBarang);
   mysqli_stmt_close($stmtBarang);
}
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
                  <strong><?= htmlspecialchars($nmbarang, ENT_QUOTES); ?></strong>
               </span>
            </td>
            <td colspan="2" rowspan="5" align="center" valign="middle">
               <img src="../dist/img/halal.png" alt="HALAL" height="100" align="absmiddle">
            </td>
         </tr>

         <tr>
            <td colspan="1" rowspan="2">
               <span style="color:#000;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
                  <?php if (!empty($pembulatan)) { ?>
                     <span style="font-size:30px"><strong><?= number_format((float)$qty, 1); ?></strong></span>
                  <?php } else { ?>
                     <span style="font-size:30px"><strong><?= number_format((float)$qty, 2); ?></strong></span>
                  <?php } ?>
               </span>
            </td>
            <td height="20" style="font-size: 12px font-family 'Gill Sans', 'Gill Sans MT', 'Myriad Pro', 'DejaVu Sans Condensed', Helvetica, Arial, sans-serif;">
               <?php if ($pcs > 0) { ?>
                  <strong><i><?= $pcs . "-Pcs"; ?></i></strong>
               <?php } ?>
            </td>
         </tr>

         <tr>
            <td height="20" style="font-style:normal;font-size:12px;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
               <?php if ($phFloat !== null) : ?>
                  <span style="font-size:12px">pH <?= number_format($phFloat, 1); ?></span>
               <?php else: ?>
                  &nbsp;
               <?php endif; ?>
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

         <?php if (!empty($exp)) { ?>
            <tr>
               <td style="font-size:11px"><span style="color:#000;">Expired Date :</span></td>
               <td style="font-size:11px"><span style="color:#000;"><?= date('d-M-Y', strtotime($exp)); ?></span></td>
            </tr>
         <?php } else { ?>
            <tr>
               <td style="font-size:11px"><span style="color:#000;">&nbsp;</span></td>
               <td style="font-size:11px"><span style="color:#000;">&nbsp;</span></td>
            </tr>
         <?php } ?>

         <tr>
            <td height="20" colspan="2">
               <span style="color:#000;font-size:12px;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
                  <strong><?= ($idgrade == 1 || $idgrade == 3) ? "KEEP CHILL 0°C" : "KEEP FROZEN -18°C"; ?></strong>
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
               $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
               $label = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);
               echo '<img src="data:image/jpeg;base64,' . base64_encode($label) . '" alt="Barcode">';
               ?>
            </td>
         </tr>
         <tr>
            <td colspan="4" align="center">
               <span style="color:#000;font-family:'Gill Sans','Gill Sans MT','Myriad Pro','DejaVu Sans Condensed',Helvetica,Arial,sans-serif;">
                  <?= htmlspecialchars($barcode, ENT_QUOTES); ?>
               </span>
            </td>
         </tr>
      </tbody>
   </table>

   <script>
      window.onload = function() {
         window.print();
         window.onafterprint = function() {
            window.location.href = 'detailhasil.php?id=<?= (int)$idrepack ?>';
         };
         setTimeout(function() {
            window.close();
         }, 500);
      };
   </script>
</body>

</html>