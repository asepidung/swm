<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Ambil data dari formulir
   $idgr = $_POST['idgr'];
   $receivedate = $_POST['receivedate'];
   $idsupplier = $_POST['idsupplier'];
   $idnumber = $_POST['idnumber'];
   $xbox = $_POST['xbox'];
   $xweight = $_POST['xweight'];
   $note = $_POST['note'];

   // Update data di tabel gr
   $query_update_gr = "UPDATE gr SET receivedate = ?, idsupplier = ?, idnumber = ?, xbox = ?, xweight = ?, note = ? WHERE idgr = ?";
   $stmt_update_gr = $conn->prepare($query_update_gr);
   $stmt_update_gr->bind_param("sissdsi", $receivedate, $idsupplier, $idnumber, $xbox, $xweight, $note, $idgr);

   if ($stmt_update_gr->execute()) {
      // Update berhasil
      echo "Data GR berhasil diupdate. ";

      // Selanjutnya, Anda perlu mengupdate data di tabel grdetail. Loop melalui data yang dikirimkan melalui formulir.
      $idgrade = $_POST['idgrade'];
      $idbarang = $_POST['idbarang'];
      $box = $_POST['box'];
      $weight = $_POST['weight'];
      $notes = $_POST['notes'];

      // Hapus data lama dari grdetail
      $query_delete_grdetail = "DELETE FROM grdetail WHERE idgr = ?";
      $stmt_delete_grdetail = $conn->prepare($query_delete_grdetail);
      $stmt_delete_grdetail->bind_param("i", $idgr);
      $stmt_delete_grdetail->execute();

      // Loop dan masukkan data baru ke grdetail
      for ($i = 0; $i < count($idgrade); $i++) {
         $query_insert_grdetail = "INSERT INTO grdetail (idgr, idgrade, idbarang, box, weight, notes) VALUES (?, ?, ?, ?, ?, ?)";
         $stmt_insert_grdetail = $conn->prepare($query_insert_grdetail);
         $stmt_insert_grdetail->bind_param("iiidds", $idgr, $idgrade[$i], $idbarang[$i], $box[$i], $weight[$i], $notes[$i]);
         $stmt_insert_grdetail->execute();
      }

      header("location: index.php");;
   } else {
      // Update gagal
      echo "Gagal mengupdate data GR.";
   }

   $stmt_update_gr->close();
   $conn->close();
}
