<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $idusers = $_POST['idusers'];
   $roles = [
      'produksi' => 0,
      'warehouse' => 0,
      'distributions' => 0,
      'purchase_module' => 0,
      'sales' => 0,
      'finance' => 0,
      'data_report' => 0,
      'master_data' => 0
   ];

   if (isset($_POST['menu_access'])) {
      foreach ($_POST['menu_access'] as $access) {
         if (array_key_exists($access, $roles)) {
            $roles[$access] = 1;
         }
      }
   }

   $query = "UPDATE role SET 
      produksi = '{$roles['produksi']}', 
      warehouse = '{$roles['warehouse']}', 
      distributions = '{$roles['distributions']}', 
      purchase_module = '{$roles['purchase_module']}', 
      sales = '{$roles['sales']}', 
      finance = '{$roles['finance']}', 
      data_report = '{$roles['data_report']}', 
      master_data = '{$roles['master_data']}' 
      WHERE idusers = $idusers";

   if (mysqli_query($conn, $query)) {
      header("Location: user.php");
      exit();
   } else {
      echo "<script>alert('Gagal memperbarui data. Silahkan coba lagi.'); window.location='user.php';</script>";
   }
}

mysqli_close($conn);
