<?php
session_start();

if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";

if (isset($_POST['idpoproduct'], $_POST['tglpoproduct'], $_POST['deliveryat'], $_POST['idsupplier'], $_POST['terms'], $_POST['note'], $_POST['xweight'], $_POST['xamount'], $_POST['idbarang'], $_POST['weight'], $_POST['price'], $_POST['amount'], $_POST['notes'])) {
   $idpoproduct = $_POST['idpoproduct'];
   $tglpoproduct = $_POST['tglpoproduct'];
   $deliveryat = $_POST['deliveryat'];
   $idsupplier = $_POST['idsupplier'];
   $terms = $_POST['terms'];
   $note = $_POST['note'];
   $xweight = str_replace(',', '', $_POST['xweight']);
   $xamount = str_replace(',', '', $_POST['xamount']);


   // Update data di tabel poproduct
   $update_poproduct_query = "UPDATE poproduct SET tglpoproduct='$tglpoproduct', deliveryat='$deliveryat', idsupplier='$idsupplier', Terms='$terms', note='$note', xweight='$xweight', xamount='$xamount'  WHERE idpoproduct='$idpoproduct'";

   if (mysqli_query($conn, $update_poproduct_query)) {
      // Hapus data lama di tabel poproductdetail
      $delete_poproductdetail_query = "DELETE FROM poproductdetail WHERE idpoproduct='$idpoproduct'";

      if (mysqli_query($conn, $delete_poproductdetail_query)) {
         // Input ulang data ke tabel poproductdetail berdasarkan data dari form
         $idbarang = $_POST['idbarang'];
         $weight = str_replace(',', '', $_POST['weight']);
         $price = str_replace(',', '', $_POST['price']);
         $amount = str_replace(',', '', $_POST['amount']);
         $notes = $_POST['notes'];

         $count = count($idbarang);

         for ($i = 0; $i < $count; $i++) {
            $item_idbarang = mysqli_real_escape_string($conn, $idbarang[$i]);
            $item_weight = mysqli_real_escape_string($conn, $weight[$i]);
            $item_price = mysqli_real_escape_string($conn, $price[$i]);
            $item_amount = mysqli_real_escape_string($conn, $amount[$i]);
            $item_notes = mysqli_real_escape_string($conn, $notes[$i]);

            $insert_poproductdetail_query = "INSERT INTO poproductdetail (idpoproduct, idbarang, qty, price, amount, notes) VALUES ('$idpoproduct', '$item_idbarang', '$item_weight', '$item_price', '$item_amount', '$item_notes')";

            mysqli_query($conn, $insert_poproductdetail_query);
         }

         header("location: index.php");
         exit(); // Pastikan keluar dari skrip setelah mengalihkan
      } else {
         echo "Error deleting record: " . mysqli_error($conn);
      }
   } else {
      echo "Error updating record: " . mysqli_error($conn);
   }
} else {
   echo "Data not received";
}
