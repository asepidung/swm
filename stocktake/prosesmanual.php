<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit;
}

require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Ambil nilai dari formulir
   $idst = $_POST['idst'];
   $kdbarcode = $_POST['kdbarcode'];
   $idbarang = $_POST['idbarang'][0];
   $idgrade = $_POST['idgrade'][0];
   $qty = $_POST['qty'];
   $pcs = $_POST['pcs'];
   $pod = $_POST['pod'];
   $origin = $_POST['origin'];

   // Validasi data sebelum dimasukkan ke database


   // Cek koneksi
   if (!$conn) {
      die("Koneksi gagal: " . mysqli_connect_error());
   }

   // Mulai transaksi untuk memastikan kedua query sukses
   mysqli_begin_transaction($conn);

   try {
      // Query untuk stocktakedetail
      $query1 = "INSERT INTO stocktakedetail (idst, kdbarcode, idbarang, idgrade, qty, pcs, pod, origin) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt1 = mysqli_prepare($conn, $query1);
      mysqli_stmt_bind_param($stmt1, "isssdiss", $idst, $kdbarcode, $idbarang, $idgrade, $qty, $pcs, $pod, $origin);
      mysqli_stmt_execute($stmt1);

      // Query untuk manualstock
      $query2 = "INSERT INTO manualstock (idst, kdbarcode, idbarang, idgrade, qty, pcs, pod, origin) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt2 = mysqli_prepare($conn, $query2);
      mysqli_stmt_bind_param($stmt2, "isssdiss", $idst, $kdbarcode, $idbarang, $idgrade, $qty, $pcs, $pod, $origin);
      mysqli_stmt_execute($stmt2);

      // Commit transaksi jika keduanya berhasil
      mysqli_commit($conn);

      // Redirect ke halaman berikutnya
      header("location: starttaking.php?id=$idst&stat=success");
      exit;
   } catch (Exception $e) {
      // Rollback jika ada kegagalan
      mysqli_rollback($conn);
      die("Error: " . $e->getMessage());
   }
}
