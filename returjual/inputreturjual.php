<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "returnnumber.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $returdate = $_POST["returdate"];
   $idcustomer = $_POST["idcustomer"];
   $note = $_POST["note"];
   $idusers = $_SESSION['idusers'];

   // Query insert ke tabel returjual
   $insertQuery = "INSERT INTO returjual (returnnumber, returdate, idcustomer, note, idusers) 
                   VALUES ('$returnnumber', '$returdate', $idcustomer, '$note', $idusers)";

   // Eksekusi query
   if (mysqli_query($conn, $insertQuery)) {
      // Jika insert berhasil, ambil idreturjual yang baru saja diinsert
      $idreturjual = mysqli_insert_id($conn);

      // Insert ke tabel logactivity
      $event = "Buat Retur Jual";
      $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) 
                   VALUES ($idusers, '$event', '$returnnumber', NOW())";
      mysqli_query($conn, $logQuery);

      // Redirect ke halaman detailitem.php dengan membawa idreturjual sebagai parameter query string
      header("location: detailrj.php?idreturjual=$idreturjual");
      exit;
   } else {
      // Jika terjadi kesalahan, tampilkan pesan error
      echo "Error: " . mysqli_error($conn);
   }
}
