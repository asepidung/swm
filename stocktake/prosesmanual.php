<?php
require "../verifications/auth.php";
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Ambil nilai dari formulir
   $idst = $_POST['idst'];
   $kdbarcode = $_POST['kdbarcode'];
   $idbarang = $_POST['idbarang'][0]; // Asumsikan array, sesuaikan jika bukan array
   $idgrade = $_POST['idgrade'][0];
   $qty = $_POST['qty'];
   $pcs = $_POST['pcs'];
   $pod = $_POST['pod'];
   $origin = $_POST['origin'];

   // Validasi koneksi database
   if (!$conn) {
      die("Koneksi gagal: " . mysqli_connect_error());
   }

   // Aktifkan exception mode agar error bisa ditangkap try-catch
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

   try {
      // Cek apakah kdbarcode sudah ada di stocktakedetail
      $checkQuery = "SELECT COUNT(*) FROM stocktakedetail WHERE kdbarcode = ?";
      $checkStmt = mysqli_prepare($conn, $checkQuery);
      mysqli_stmt_bind_param($checkStmt, "s", $kdbarcode);
      mysqli_stmt_execute($checkStmt);
      mysqli_stmt_bind_result($checkStmt, $count);
      mysqli_stmt_fetch($checkStmt);
      mysqli_stmt_close($checkStmt);

      if ($count > 0) {
         // Barcode sudah ada, gagalkan proses
         header("location: starttaking.php?id=$idst&stat=duplicate");
         exit;
      }

      // Mulai transaksi
      mysqli_begin_transaction($conn);

      // Insert ke stocktakedetail
      $query1 = "INSERT INTO stocktakedetail (idst, kdbarcode, idbarang, idgrade, qty, pcs, pod, origin) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt1 = mysqli_prepare($conn, $query1);
      mysqli_stmt_bind_param($stmt1, "issiidss", $idst, $kdbarcode, $idbarang, $idgrade, $qty, $pcs, $pod, $origin);
      mysqli_stmt_execute($stmt1);

      // Insert ke manualstock
      $query2 = "INSERT INTO manualstock (idst, kdbarcode, idbarang, idgrade, qty, pcs, pod, origin) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt2 = mysqli_prepare($conn, $query2);
      mysqli_stmt_bind_param($stmt2, "issiidss", $idst, $kdbarcode, $idbarang, $idgrade, $qty, $pcs, $pod, $origin);
      mysqli_stmt_execute($stmt2);

      // Commit transaksi
      mysqli_commit($conn);

      // Redirect sukses
      header("location: starttaking.php?id=$idst&stat=success");
      exit;
   } catch (Exception $e) {
      // Rollback jika error
      mysqli_rollback($conn);
      die("Terjadi kesalahan: " . $e->getMessage());
   }
}
