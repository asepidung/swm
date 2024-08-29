<?php
session_start();

if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   require "../konak/conn.php";

   // Ambil data dari formulir
   $idinvoice = $_POST['idinvoice'];
   $invoice_date = $_POST['invoice_date'];
   $noinvoice = $_POST['noinvoice'];
   $note = $_POST['note'];
   $charge = $_POST['charge'];
   $downpayment = str_replace(',', '', $_POST['downpayment']);
   $xamount = str_replace(',', '', $_POST['xamount']);
   $balance = str_replace(',', '', $_POST['balance']);

   $delete_invoicedetail_query = "DELETE FROM invoicedetail WHERE idinvoice = '$idinvoice'";
   mysqli_query($conn, $delete_invoicedetail_query);

   $update_invoice_query = "UPDATE invoice 
                            SET invoice_date = '$invoice_date',
                                note = '$note',
                                charge = '$charge',
                                xamount = '$xamount',
                                balance = '$balance',
                                downpayment = '$downpayment'
                            WHERE idinvoice = '$idinvoice'";
   mysqli_query($conn, $update_invoice_query);

   $update_piutang_query = "UPDATE piutang 
                            SET balance = '$balance'
                            WHERE idinvoice = '$idinvoice'";
   mysqli_query($conn, $update_piutang_query);

   // Menambahkan data baru ke tabel invoicedetail (Anda harus mengambil data dari formulir dan melakukan loop untuk memasukkan setiap detail)
   $idbarang = $_POST['idbarang']; // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.
   $weight = $_POST['weight']; // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.
   $price = str_replace(',', '', $_POST['price']); // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.
   $discount = $_POST['discount']; // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.

   for ($i = 0; $i < count($idbarang); $i++) {
      $idbarang_value = $idbarang[$i];
      $weight_value = $weight[$i];
      $price_value = $price[$i];
      $discount_value = $discount[$i];

      $amount = ($price[$i] - $discount[$i]) * $weight[$i];
      echo "amount: " . $amount . "<br>";

      $amount = ($price_value - $discount_value) * $weight_value;

      $insert_invoicedetail_query = "INSERT INTO invoicedetail (idinvoice, idbarang, weight, price, discount, discountrp, amount)
                                     VALUES ('$idinvoice', '$idbarang_value', '$weight_value', '$price_value', '$discount_value', '0', '$amount')";
      mysqli_query($conn, $insert_invoicedetail_query);
   }

   // Insert log activity into logactivity table
   $idusers = $_SESSION['idusers'];
   $event = "Edit Invoice";
   $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                VALUES (?, ?, ?, NOW())";
   $stmt_log = $conn->prepare($logQuery);
   $stmt_log->bind_param("iss", $idusers, $noinvoice, $event);
   $stmt_log->execute();
   $stmt_log->close();

   // Redirect ke halaman lain atau lakukan tindakan lain sesuai kebutuhan
   header("location: invoice.php");
   exit();
}
