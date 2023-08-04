<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_POST['idcustomer'])) {
   $selectedCustomerId = $_POST['idcustomer'];
   $query = "SELECT alamat1, alamat2, alamat3 FROM customers WHERE idcustomer = $selectedCustomerId";
   $result = $conn->query($query);

   // Buat opsi alamat berdasarkan data yang ditemukan
   if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $alamat1 = $row['alamat1'];
      $alamat2 = $row['alamat2'];
      $alamat3 = $row['alamat3'];
      // Setiap opsi alamat memiliki nilai (value) yang sesuai dengan isi alamat
      echo "<option value=\"$alamat1\">$alamat1</option>";
      echo "<option value=\"$alamat2\">$alamat2</option>";
      echo "<option value=\"$alamat3\">$alamat3</option>";
   } else {
      echo "<option value=\"\">Tidak Ada Alamat Tersedia</option>";
   }
}

$conn->close();
