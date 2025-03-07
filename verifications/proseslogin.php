<?php
require "../konak/conn.php"; // Koneksi ke database
session_start(); // Mulai sesi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $userid = trim($_POST['userid']);
   $password = trim($_POST['password']);

   // Gunakan prepared statement untuk menghindari SQL Injection
   $sql = "SELECT idusers, userid, passuser, fullname, status FROM users WHERE userid = ?";
   $stmt = mysqli_prepare($conn, $sql);

   if ($stmt) {
      mysqli_stmt_bind_param($stmt, "s", $userid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if ($result && mysqli_num_rows($result) == 1) {
         $row = mysqli_fetch_assoc($result);
         $hashedPassword = $row['passuser'];
         $idusers = $row['idusers'];

         // Cek apakah akun dalam status INAKTIF
         if ($row['status'] === 'INAKTIF') {
            header("Location: login.php?error=inactive");
            exit();
         }

         // Verifikasi password
         if (password_verify($password, $hashedPassword)) {
            // Regenerasi ID sesi untuk mencegah session fixation
            session_regenerate_id(true);

            // Set sesi login
            $_SESSION['login'] = true;
            $_SESSION['userid'] = $userid;
            $_SESSION['idusers'] = $idusers;
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['last_activity'] = time(); // Catat waktu login
            $_SESSION['timeout'] = 30; // 5 menit dalam detik

            // Catat aktivitas login ke database
            $logSql = "INSERT INTO logactivity (iduser, event) VALUES (?, 'Login')";
            $logStmt = mysqli_prepare($conn, $logSql);
            mysqli_stmt_bind_param($logStmt, "i", $idusers);
            mysqli_stmt_execute($logStmt);
            mysqli_stmt_close($logStmt);

            // Redirect ke halaman utama
            header("Location: ../index.php");
            exit();
         } else {
            // Jika password salah
            header("Location: login.php?error=invalid");
            exit();
         }
      } else {
         // Jika username tidak ditemukan
         header("Location: login.php?error=notfound");
         exit();
      }

      mysqli_stmt_close($stmt);
   } else {
      die("Query gagal: " . mysqli_error($conn));
   }
}

// Tutup koneksi database
mysqli_close($conn);
