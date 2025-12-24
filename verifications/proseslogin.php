<?php
require "../konak/conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

   $userid   = trim($_POST['userid'] ?? '');
   $password = trim($_POST['password'] ?? '');

   if ($userid === '' || $password === '') {
      header("Location: login.php?error=empty");
      exit();
   }

   $sql = "SELECT idusers, userid, passuser, fullname, status 
            FROM users 
            WHERE userid = ? 
            LIMIT 1";

   $stmt = mysqli_prepare($conn, $sql);

   if (!$stmt) {
      die("Prepare failed: " . mysqli_error($conn));
   }

   mysqli_stmt_bind_param($stmt, "s", $userid);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);

   if ($result && mysqli_num_rows($result) === 1) {

      $row = mysqli_fetch_assoc($result);

      // Cek status akun
      if ($row['status'] === 'INAKTIF') {
         header("Location: login.php?error=inactive");
         exit();
      }

      // Verifikasi password
      if (!password_verify($password, $row['passuser'])) {
         header("Location: login.php?error=invalid");
         exit();
      }

      // Login sukses
      session_regenerate_id(true);

      $_SESSION['login']    = true;
      $_SESSION['userid']   = $row['userid'];
      $_SESSION['idusers']  = $row['idusers'];
      $_SESSION['fullname'] = $row['fullname'];

      // Catat log login
      $logSql = "INSERT INTO logactivity (iduser, event) VALUES (?, 'Login')";
      $logStmt = mysqli_prepare($conn, $logSql);

      if ($logStmt) {
         mysqli_stmt_bind_param($logStmt, "i", $row['idusers']);
         mysqli_stmt_execute($logStmt);
         mysqli_stmt_close($logStmt);
      }

      mysqli_stmt_close($stmt);
      mysqli_close($conn);

      header("Location: ../index.php");
      exit();
   } else {
      header("Location: login.php?error=notfound");
      exit();
   }
}

mysqli_close($conn);
