<?php
require "../verifications/auth.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   require "../konak/conn.php";

   // Ambil data dari formulir
   $idinvoice = $_POST['idinvoice'];
   $invoice_date = $_POST['invoice_date'];
   $noinvoice = $_POST['noinvoice'];
   $note = $_POST['note'];
   $charge = str_replace(',', '', $_POST['charge']);
   $downpayment = str_replace(',', '', $_POST['downpayment']);
   $xamount = str_replace(',', '', $_POST['xamount']);
   $balance = str_replace(',', '', $_POST['balance']);
   $discountrp = $_POST['discountrp']; // Diskon total per item

   // Hapus data lama dari tabel invoicedetail
   $delete_invoicedetail_query = "DELETE FROM invoicedetail WHERE idinvoice = '$idinvoice'";
   mysqli_query($conn, $delete_invoicedetail_query);

   // Update data invoice utama
   $update_invoice_query = "UPDATE invoice 
                            SET invoice_date = '$invoice_date',
                                note = '$note',
                                charge = '$charge',
                                xamount = '$xamount',
                                balance = '$balance',
                                downpayment = '$downpayment'
                            WHERE idinvoice = '$idinvoice'";
   mysqli_query($conn, $update_invoice_query);

   // Update data piutang
   $update_piutang_query = "UPDATE piutang 
                            SET balance = '$balance'
                            WHERE idinvoice = '$idinvoice'";
   mysqli_query($conn, $update_piutang_query);

   // Ambil data detail dari form
   $idbarang = $_POST['idbarang'];
   $weight = $_POST['weight'];
   $price = str_replace(',', '', $_POST['price']);
   $discount = $_POST['discount']; // Diskon dalam persentase
   $discountrp = str_replace(',', '', $_POST['discountrp']); // Diskon total dalam Rupiah

   // Loop untuk memasukkan data baru ke tabel invoicedetail
   for ($i = 0; $i < count($idbarang); $i++) {
      $idbarang_value = $idbarang[$i];
      $weight_value = (float) str_replace(',', '', $weight[$i]);
      $price_value = (float) str_replace(',', '', $price[$i]);
      $discount_value = (float) $discount[$i];
      $discountrp_value = (float) str_replace(',', '', $discountrp[$i]);

      // Hitung diskon per unit (diskon total dibagi berat)
      $discountrp_per_unit = $discountrp_value / $weight_value;

      // Hitung total amount untuk item ini
      $amount = ($price_value - $discountrp_per_unit) * $weight_value;

      // Masukkan data baru ke tabel invoicedetail
      $insert_invoicedetail_query = "INSERT INTO invoicedetail (idinvoice, idbarang, weight, price, discount, discountrp, amount)
                                     VALUES ('$idinvoice', '$idbarang_value', '$weight_value', '$price_value', '$discount_value', '$discountrp_value', '$amount')";
      mysqli_query($conn, $insert_invoicedetail_query);
   }

   // Catat log aktivitas
   $idusers = $_SESSION['idusers'];
   $event = "Edit Invoice";
   $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                VALUES (?, ?, ?, NOW())";
   $stmt_log = $conn->prepare($logQuery);
   $stmt_log->bind_param("iss", $idusers, $noinvoice, $event);
   $stmt_log->execute();
   $stmt_log->close();

   // Redirect ke halaman invoice
   header("location: invoice.php");
   exit();
}
