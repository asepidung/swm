<?php
require "../verifications/auth.php";
require "../konak/conn.php";

// Ambil dan bersihkan input
$idrawmate     = isset($_POST['idrawmate']) ? intval($_POST['idrawmate']) : 0;
$nmrawmate     = isset($_POST['nmrawmate']) ? trim($_POST['nmrawmate']) : '';
$idrawcategory = isset($_POST['idrawcategory']) ? intval($_POST['idrawcategory']) : 0;
$stock         = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
$unit          = isset($_POST['unit']) ? trim($_POST['unit']) : '';

// Daftar unit yang valid (urut alfabet, sama dengan edit/new form)
$valid_units = ["Box", "Ikat", "Kg", "Pack", "Pcs", "Set"];

// Validasi dasar
if ($idrawmate <= 0 || $nmrawmate === '' || $idrawcategory <= 0 || !in_array($stock, [0, 1], true)) {
  echo "<script>alert('Form tidak lengkap atau ID tidak valid.'); window.location='editrawmate.php?idrawmate={$idrawmate}';</script>";
  exit();
}

// Validasi unit
if ($unit === '' || !in_array($unit, $valid_units, true)) {
  echo "<script>alert('Satuan (unit) tidak valid. Pilih satuan dari daftar.'); window.location='editrawmate.php?idrawmate={$idrawmate}';</script>";
  exit();
}

// Cek duplikat nama (case-insensitive), kecuali record yg sedang di-edit
$checkSql = "SELECT idrawmate FROM rawmate WHERE LOWER(nmrawmate) = LOWER(?) AND idrawmate <> ? LIMIT 1";
if ($stmt = $conn->prepare($checkSql)) {
  $stmt->bind_param("si", $nmrawmate, $idrawmate);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    $stmt->close();
    echo "<script>alert('Nama material sudah ada di database. Gunakan nama lain.'); window.location='editrawmate.php?idrawmate={$idrawmate}';</script>";
    exit();
  }
  $stmt->close();
} else {
  echo "<script>alert('Database error (check duplicate).'); window.location='editrawmate.php?idrawmate={$idrawmate}';</script>";
  exit();
}

// Update record (menggunakan prepared statement)
$updateSql = "UPDATE rawmate SET nmrawmate = ?, idrawcategory = ?, stock = ?, unit = ? WHERE idrawmate = ?";
if ($stmt = $conn->prepare($updateSql)) {
  $stmt->bind_param("siisi", $nmrawmate, $idrawcategory, $stock, $unit, $idrawmate);
  if ($stmt->execute()) {
    $stmt->close();
    echo "<script>alert('Data rawmate berhasil diperbarui.'); window.location='index.php';</script>";
    exit();
  } else {
    $err = $stmt->error;
    $stmt->close();
    echo "<script>alert('Gagal memperbarui data: " . addslashes($err) . "'); window.location='editrawmate.php?idrawmate={$idrawmate}';</script>";
    exit();
  }
} else {
  echo "<script>alert('Database error (update).'); window.location='editrawmate.php?idrawmate={$idrawmate}';</script>";
  exit();
}

// Tutup koneksi
mysqli_close($conn);
