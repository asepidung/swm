<?php
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $userid = $_POST['userid'];
   $password = $_POST['password'];

   // Query untuk memeriksa apakah username sesuai di database
   $sql = "SELECT * FROM users WHERE userid = '$userid'";
   $result = mysqli_query($conn, $sql);

   if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_assoc($result);
      $hashedPassword = $row['passuser'];

      // Memeriksa kecocokan password yang dimasukkan dengan hash yang ada dalam database
      if (password_verify($password, $hashedPassword)) {
         // Jika password cocok, buat session dan redirect ke halaman dashboard
         session_start();
         $_SESSION['login'] = true;
         $_SESSION['userid'] = $userid;
         $_SESSION['idusers'] = $row['idusers'];
         header("Location: ../index.php");
         exit();
      } else {
         // Jika password tidak cocok, tampilkan pesan error
         echo "<script>alert('Invalid username or password. Please try again.'); window.location='login.php';</script>";
      }
   } else {
      // Jika data tidak ditemukan, tampilkan pesan error
      echo "<script>alert('Invalid username or password. Please try again.'); window.location='login.php';</script>";
   }
}

// Menutup koneksi ke database
mysqli_close($conn);
