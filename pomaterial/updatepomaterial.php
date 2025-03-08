<?php
require "../verifications/auth.php";
require "../konak/conn.php";

if (isset($_POST['idpomaterial'], $_POST['tglpomaterial'], $_POST['deliveryat'], $_POST['nopomaterial'], $_POST['idsupplier'], $_POST['terms'], $_POST['note'], $_POST['xweight'], $_POST['xamount'], $_POST['idrawmate'], $_POST['weight'], $_POST['price'], $_POST['amount'], $_POST['notes'])) {
   $idpomaterial = $_POST['idpomaterial'];
   $nopomaterial = $_POST['nopomaterial'];
   $tglpomaterial = $_POST['tglpomaterial'];
   $deliveryat = $_POST['deliveryat'];
   $idsupplier = $_POST['idsupplier'];
   $terms = $_POST['terms'];
   $note = $_POST['note'];

   // Update data di tabel pomaterial
   $update_pomaterial_query = "UPDATE pomaterial SET tglpomaterial='$tglpomaterial', deliveryat='$deliveryat', idsupplier='$idsupplier', Terms='$terms', note='$note'  WHERE idpomaterial='$idpomaterial'";

   if (mysqli_query($conn, $update_pomaterial_query)) {
      // Hapus data lama di tabel pomaterialdetail
      $delete_pomaterialdetail_query = "DELETE FROM pomaterialdetail WHERE idpomaterial='$idpomaterial'";

      if (mysqli_query($conn, $delete_pomaterialdetail_query)) {
         // Input ulang data ke tabel pomaterialdetail berdasarkan data dari form
         $idrawmate = $_POST['idrawmate'];
         $weight = str_replace(',', '', $_POST['weight']);
         $price = str_replace(',', '', $_POST['price']);
         $amount = str_replace(',', '', $_POST['amount']);
         $notes = $_POST['notes'];

         $count = count($idrawmate);

         for ($i = 0; $i < $count; $i++) {
            $item_idrawmate = mysqli_real_escape_string($conn, $idrawmate[$i]);
            $item_weight = mysqli_real_escape_string($conn, $weight[$i]);
            $item_price = mysqli_real_escape_string($conn, $price[$i]);
            $item_amount = mysqli_real_escape_string($conn, $amount[$i]);
            $item_notes = mysqli_real_escape_string($conn, $notes[$i]);

            $insert_pomaterialdetail_query = "INSERT INTO pomaterialdetail (idpomaterial, idrawmate, qty, price, amount, notes) VALUES ('$idpomaterial', '$item_idrawmate', '$item_weight', '$item_price', '$item_amount', '$item_notes')";

            mysqli_query($conn, $insert_pomaterialdetail_query);
         }

         // Insert log activity into logactivity table
         $idusers = $_SESSION['idusers'];
         $event = "Edit PO Material";
         $logQuery = "INSERT INTO logactivity (iduser, docnumb, event, waktu) 
                      VALUES ('$idusers', '$nopomaterial', '$event', NOW())";
         mysqli_query($conn, $logQuery);

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
