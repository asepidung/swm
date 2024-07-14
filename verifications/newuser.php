<?php
require "../konak/conn.php";

// Mengambil data dari form dan membersihkannya
$userid = mysqli_real_escape_string($conn, $_POST['userid']);
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$password2 = mysqli_real_escape_string($conn, $_POST['password2']);
$menu_access = isset($_POST['menu_access']) ? $_POST['menu_access'] : [];

// Menggunakan prepared statement untuk menyimpan data ke database
if ($password === $password2) {
   $password = password_hash($password, PASSWORD_DEFAULT);
   $stmt = mysqli_prepare($conn, "INSERT INTO users (fullname, userid, passuser) VALUES (?, ?, ?)");
   mysqli_stmt_bind_param($stmt, "sss", $fullname, $userid, $password);

   // Mengeksekusi prepared statement
   if (mysqli_stmt_execute($stmt)) {
      // Mendapatkan idusers yang baru saja dimasukkan
      $idusers = mysqli_insert_id($conn);

      // Set default access value to 0
      $access = [
         'produksi' => 0,
         'warehouse' => 0,
         'distributions' => 0,
         'purchase_module' => 0,
         'sales' => 0,
         'finance' => 0,
         'data_report' => 0,
         'master_data' => 0
      ];

      // Update access value based on selected menus
      foreach ($menu_access as $menu) {
         if (isset($access[$menu])) {
            $access[$menu] = 1;
         }
      }

      // Insert data access ke tabel role
      $stmt_role = mysqli_prepare($conn, "INSERT INTO role (idusers, produksi, warehouse, distributions, purchase_module, sales, finance, data_report, master_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
      mysqli_stmt_bind_param($stmt_role, "iiiiiiiii", $idusers, $access['produksi'], $access['warehouse'], $access['distributions'], $access['purchase_module'], $access['sales'], $access['finance'], $access['data_report'], $access['master_data']);

      // Mengeksekusi prepared statement untuk role
      if (mysqli_stmt_execute($stmt_role)) {
         echo "<script>alert('Data berhasil disimpan.'); window.location='login.php';</script>";
      } else {
         echo "Error: " . mysqli_error($conn);
      }

      // Menutup prepared statement untuk role
      mysqli_stmt_close($stmt_role);
   } else {
      echo "Error: " . mysqli_error($conn);
   }

   // Menutup prepared statement untuk user
   mysqli_stmt_close($stmt);
} else {
   echo "<script>alert('Password Anda berbeda.'); window.location='regist.php';</script>";
}

// Menutup koneksi ke database
mysqli_close($conn);
