<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Ambil data dari form dan bersihkan
$kdrawmate      = isset($_POST['kdrawmate']) ? trim($_POST['kdrawmate']) : '';
$nmrawmate      = isset($_POST['nmrawmate']) ? trim($_POST['nmrawmate']) : '';
$tampilkan_stock = isset($_POST['tampilkan_stock']) ? trim($_POST['tampilkan_stock']) : '';
$idrawcategory  = isset($_POST['idrawcategory']) ? intval($_POST['idrawcategory']) : 0;
$unit           = isset($_POST['unit']) ? trim($_POST['unit']) : '';

// Validasi sederhana
if ($kdrawmate === '' || $nmrawmate === '' || $idrawcategory <= 0 || $tampilkan_stock === '') {
   echo "<script>alert('Form tidak lengkap. Pastikan semua field wajib terisi.'); window.location='newrawmate.php';</script>";
   exit();
}

// Konversi nama material ke UPPERCASE multibyte-safe
$nmrawmate_upper = mb_strtoupper($nmrawmate, 'UTF-8');

// Cek apakah nama rawmate sudah ada (case-insensitive menggunakan uppercase)
$checkSql = "SELECT idrawmate FROM rawmate WHERE UPPER(nmrawmate) = ? LIMIT 1";
if ($stmt = $conn->prepare($checkSql)) {
   $stmt->bind_param("s", $nmrawmate_upper);
   $stmt->execute();
   $stmt->store_result();
   if ($stmt->num_rows > 0) {
      // Nama sudah ada
      $stmt->close();
      echo "<script>alert('Nama material sudah ada dalam database.'); window.location='newrawmate.php';</script>";
      exit();
   }
   $stmt->close();
} else {
   // Jika prepare gagal
   echo "<script>alert('Database error (check).'); window.location='newrawmate.php';</script>";
   exit();
}

// Insert data baru ke tabel rawmate (termasuk unit) — simpan nama dalam UPPERCASE
$insertSql = "INSERT INTO rawmate (kdrawmate, nmrawmate, idrawcategory, stock, unit) VALUES (?, ?, ?, ?, ?)";
if ($stmt = $conn->prepare($insertSql)) {
   // tipe: kdrawmate (s), nmrawmate (s), idrawcategory (i), stock (i), unit (s)
   $stock_int = (int)$tampilkan_stock;
   $stmt->bind_param("ssiss", $kdrawmate, $nmrawmate_upper, $idrawcategory, $stock_int, $unit);

   if ($stmt->execute()) {
      $stmt->close();
      echo "<script>alert('Data berhasil disimpan.'); window.location='index.php';</script>";
      exit();
   } else {
      $err = $stmt->error;
      $stmt->close();
      echo "<script>alert('Gagal menyimpan data: " . addslashes($err) . "'); window.location='newrawmate.php';</script>";
      exit();
   }
} else {
   // prepare gagal
   echo "<script>alert('Database error (insert).'); window.location='newrawmate.php';</script>";
   exit();
}

// Tutup koneksi (opsional)
mysqli_close($conn);
