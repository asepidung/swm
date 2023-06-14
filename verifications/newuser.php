<?php
require "../konak/conn.php";

// Mengambil data dari form dan membersihkannya
// $nmuser = mysqli_real_escape_string($conn, $_POST['nmuser']);
$userid = mysqli_real_escape_string($conn, $_POST['userid']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$password2 = mysqli_real_escape_string($conn, $_POST['password2']);

// Menggunakan prepared statement untuk menyimpan data ke database
if ($password === $password2) {
   $password = password_hash($password, PASSWORD_DEFAULT);
   $stmt = mysqli_prepare($conn, "INSERT INTO users (userid, passuser) VALUES (?, ?)");
   mysqli_stmt_bind_param($stmt, "ss", $userid, $password);

   // Mengeksekusi prepared statement
   if (mysqli_stmt_execute($stmt)) {
      echo "<script>alert('Data berhasil disimpan.'); window.location='login.php';</script>";
   } else {
      echo "Error: " . mysqli_error($conn);
   }

   // Menutup prepared statement
   mysqli_stmt_close($stmt);
} else {
   echo "<script>alert('Password Anda berbeda.'); window.location='regist.php';</script>";
}

// Menutup koneksi ke database
mysqli_close($conn);
