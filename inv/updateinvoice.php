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
   $note = $_POST['note'];
   $charge = $_POST['charge'];
   $downpayment = str_replace(',', '', $_POST['downpayment']);
   $xamount = str_replace(',', '', $_POST['xamount']);
   $balance = str_replace(',', '', $_POST['balance']);


   // echo "idinvoice: " . $_POST['idinvoice'] . "<br>";
   // echo "invoice_date: " . $_POST['invoice_date'] . "<br>";
   // echo "note: " . $_POST['note'] . "<br>";
   // echo "charge: " . $_POST['charge'] . "<br>";
   // echo "downpayment: " . $_POST['downpayment'] . "<br>";


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

   // Menambahkan data baru ke tabel invoicedetail (Anda harus mengambil data dari formulir dan melakukan loop untuk memasukkan setiap detail)
   $idgrade = $_POST['idgrade']; // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.
   $idbarang = $_POST['idbarang']; // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.
   $weight = $_POST['weight']; // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.
   $price = str_replace(',', '', $_POST['price']); // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.
   $discount = $_POST['discount']; // Ini adalah contoh. Anda harus mengambil data dari formulir dengan benar.

   for ($i = 0; $i < count($idgrade); $i++) {
      $idgrade_value = $idgrade[$i];
      $idbarang_value = $idbarang[$i];
      $weight_value = $weight[$i];
      $price_value = $price[$i];
      $discount_value = $discount[$i];
      // for ($i = 0; $i < count($idgrade); $i++) {
      //    echo "Data ke-" . ($i + 1) . ":<br>";
      //    echo "idgrade: " . $idgrade[$i] . "<br>";
      //    echo "idbarang: " . $idbarang[$i] . "<br>";
      //    echo "weight: " . $weight[$i] . "<br>";
      //    echo "price: " . $price[$i] . "<br>";
      //    echo "discount: " . $discount[$i] . "<br>";

      $amount = ($price[$i] - $discount[$i]) * $weight[$i];
      echo "amount: " . $amount . "<br>";

      $amount = ($price_value - $discount_value) * $weight_value;

      $insert_invoicedetail_query = "INSERT INTO invoicedetail (idinvoice, idgrade, idbarang, weight, price, discount, discountrp, amount)
                                       VALUES ('$idinvoice', '$idgrade_value', '$idbarang_value', '$weight_value', '$price_value', '$discount_value', '0', '$amount')";
      mysqli_query($conn, $insert_invoicedetail_query);
   }

   // Redirect ke halaman lain atau lakukan tindakan lain sesuai kebutuhan
   header("location: invoice.php");
}
