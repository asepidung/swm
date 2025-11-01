<?php
require "../verifications/auth.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   require "../konak/conn.php";
   $conn->set_charset('utf8mb4');

   // ---- Normalizer angka (menerima 1.000, 1,000, 1.000,50, 1,000.50) ----
   function normalizeNumber($number)
   {
      if ($number === null) return 0.0;
      $number = trim((string)$number);
      $number = str_replace(["\xc2\xa0", ' ', "'"], '', $number);
      $lastCommaPos = strrpos($number, ',');
      $lastDotPos   = strrpos($number, '.');

      if ($lastCommaPos !== false && $lastDotPos !== false) {
         if ($lastCommaPos > $lastDotPos) { // koma desimal
            $number = str_replace('.', '', $number);
            $number = str_replace(',', '.', $number);
         } else { // titik desimal
            $number = str_replace(',', '', $number);
         }
      } else {
         if ($lastCommaPos !== false) {
            $number = str_replace('.', '', $number);
            $number = str_replace(',', '.', $number);
         } // jika hanya titik / none: biarkan
      }
      $number = preg_replace('/[^0-9.\-]/', '', $number);
      if ($number === '' || $number === '-' || $number === '.') return 0.0;
      return (float)$number;
   }
   function normalizeArray($arr)
   {
      $out = [];
      foreach ((array)$arr as $v) $out[] = normalizeNumber($v);
      return $out;
   }

   // ---- Header non-angka / referensi ----
   $idinvoice    = isset($_POST['idinvoice']) ? (int) $_POST['idinvoice'] : 0;
   $invoice_date = $_POST['invoice_date'] ?? date('Y-m-d');
   $noinvoice    = $_POST['noinvoice'] ?? '';
   $note         = $_POST['note'] ?? '';
   if ($idinvoice <= 0) {
      http_response_code(400);
      exit('ID invoice tidak valid.');
   }

   // ---- Ambil angka yang TETAP dipakai dari form ----
   $charge      = normalizeNumber($_POST['charge']      ?? 0);
   $downpayment = normalizeNumber($_POST['downpayment'] ?? 0);
   $tax         = normalizeNumber($_POST['tax']         ?? 0);

   // ⚠️ Abaikan xamount & balance dari form:
   // $xamount = normalizeNumber($_POST['xamount'] ?? 0);
   // $balance = normalizeNumber($_POST['balance'] ?? 0);

   // ---- Detail arrays ----
   $idbarang   = $_POST['idbarang']   ?? [];
   $weight     = normalizeArray($_POST['weight']     ?? []);
   $price      = normalizeArray($_POST['price']      ?? []);
   $discount   = normalizeArray($_POST['discount']   ?? []);   // persen (opsional)
   $discountrp = normalizeArray($_POST['discountrp'] ?? []);   // rupiah total per item
   $n = min(count($idbarang), count($weight), count($price), count($discount), count($discountrp));

   try {
      $conn->begin_transaction();

      // 1) Hapus detail lama
      $stmtDel = $conn->prepare("DELETE FROM invoicedetail WHERE idinvoice = ?");
      $stmtDel->bind_param("i", $idinvoice);
      $stmtDel->execute();
      $stmtDel->close();

      // 2) Insert detail baru + hitung ulang subtotal & total weight
      $stmtIns = $conn->prepare("
            INSERT INTO invoicedetail
                (idinvoice, idbarang, weight, price, discount, discountrp, amount)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

      $subtotal = 0.0;
      $totalWeight = 0.0;

      for ($i = 0; $i < $n; $i++) {
         $idbarangVal   = (int) $idbarang[$i];
         $weightVal     = (float) $weight[$i];
         $priceVal      = (float) $price[$i];
         $discountPct   = (float) $discount[$i];
         $discountrpVal = (float) $discountrp[$i];

         if ($weightVal <= 0) {
            $amount = 0.0;
         } else {
            $discPerUnit = $discountrpVal / $weightVal;

            // Jika ingin ikutkan discount%:
            // $priceAfterPct = $priceVal * (1 - ($discountPct / 100));
            // $amount = ($priceAfterPct - $discPerUnit) * $weightVal;

            // Sesuai logika sebelumnya (tanpa discount%):
            $amount = ($priceVal - $discPerUnit) * $weightVal;
         }

         $subtotal += $amount;
         $totalWeight += max(0.0, $weightVal);

         $stmtIns->bind_param(
            "iiididd",
            $idinvoice,
            $idbarangVal,
            $weightVal,
            $priceVal,
            $discountPct,
            $discountrpVal,
            $amount
         );
         $stmtIns->execute();
      }
      $stmtIns->close();

      // 3) Hitung ULANG field header di server
      $xamount = round($subtotal, 2);
      $xweight = round($totalWeight, 2);

      // kalau pajak 11% berbasis subtotal, gunakan ini:
      // $tax = round($xamount * 0.11, 2);

      $balance = round($xamount + $tax + $charge - $downpayment, 2);

      // 4) Update header invoice
      $stmtUpd = $conn->prepare("
            UPDATE invoice
               SET invoice_date = ?,
                   note         = ?,
                   xweight      = ?,
                   xamount      = ?,
                   tax          = ?,
                   charge       = ?,
                   downpayment  = ?,
                   balance      = ?
             WHERE idinvoice    = ?
        ");
      $stmtUpd->bind_param(
         "ssddddddi",
         $invoice_date,
         $note,
         $xweight,
         $xamount,
         $tax,
         $charge,
         $downpayment,
         $balance,
         $idinvoice
      );
      $stmtUpd->execute();
      $stmtUpd->close();

      // 5) Log
      $idusers = isset($_SESSION['idusers']) ? (int) $_SESSION['idusers'] : 0;
      $event   = "Edit Invoice";
      $stmtLog = $conn->prepare("INSERT INTO logactivity (iduser, docnumb, event, waktu) VALUES (?, ?, ?, NOW())");
      $stmtLog->bind_param("iss", $idusers, $noinvoice, $event);
      $stmtLog->execute();
      $stmtLog->close();

      $conn->commit();
      header("Location: invoice.php");
      exit();
   } catch (mysqli_sql_exception $e) {
      $conn->rollback();
      http_response_code(500);
      echo "Terjadi kesalahan saat update invoice: " . htmlspecialchars($e->getMessage());
      exit();
   }
}
