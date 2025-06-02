<?php
require "../verifications/auth.php";
require "../konak/conn.php";

$tipebarang = $_POST['tipebarang'] ?? '';
$kdbarang = isset($_POST['kdbarang']) ? intval($_POST['kdbarang']) : 0;
$nmbarang = trim($_POST['nmbarang'] ?? '');
$cut = $_POST['cut'] ?? '';
$kodeinduk = isset($_POST['kodeinduk']) ? intval($_POST['kodeinduk']) : null;

if (!$tipebarang || !$nmbarang || !$cut) {
   echo "<script>alert('Mohon lengkapi data wajib diisi.'); window.history.back();</script>";
   exit;
}

if ($tipebarang === 'utama') {
   if (!$kdbarang) {
      echo "<script>alert('Kode barang harus diisi untuk barang utama.'); window.history.back();</script>";
      exit;
   }
   // Cek duplikat kode dan nama
   $stmt = $conn->prepare("SELECT 1 FROM barang WHERE kdbarang = ? OR nmbarang = ?");
   $stmt->bind_param("is", $kdbarang, $nmbarang);
   $stmt->execute();
   $stmt->store_result();
   if ($stmt->num_rows > 0) {
      echo "<script>alert('Kode atau nama barang sudah ada dalam database.'); window.history.back();</script>";
      $stmt->close();
      $conn->close();
      exit;
   }
   $stmt->close();

   // Insert barang utama
   $stmt = $conn->prepare("INSERT INTO barang (kdbarang, nmbarang, idcut, kodeinduk) VALUES (?, ?, ?, NULL)");
   $stmt->bind_param("isi", $kdbarang, $nmbarang, $cut);

   if ($stmt->execute()) {
      echo "<script>alert('Data barang utama berhasil disimpan.'); window.location='barang.php';</script>";
   } else {
      echo "<script>alert('Gagal menyimpan data barang utama.'); window.history.back();</script>";
   }
   $stmt->close();
} elseif ($tipebarang === 'turunan') {
   if (!$kodeinduk) {
      echo "<script>alert('Barang induk harus dipilih untuk produk turunan.'); window.history.back();</script>";
      exit;
   }

   // Cek nama barang duplikat
   $stmt = $conn->prepare("SELECT 1 FROM barang WHERE nmbarang = ?");
   $stmt->bind_param("s", $nmbarang);
   $stmt->execute();
   $stmt->store_result();
   if ($stmt->num_rows > 0) {
      echo "<script>alert('Nama barang sudah ada dalam database.'); window.history.back();</script>";
      $stmt->close();
      $conn->close();
      exit;
   }
   $stmt->close();

   // Hitung jumlah produk turunan induk yang sudah ada
   $stmt = $conn->prepare("SELECT COUNT(*) FROM barang WHERE kodeinduk = ?");
   $stmt->bind_param("i", $kodeinduk);
   $stmt->execute();
   $stmt->bind_result($jumlahTurunan);
   $stmt->fetch();
   $stmt->close();

   // Hitung kode turunan baru: kode induk + jumlah turunan + 1
   $kdbarang_db = $kodeinduk + $jumlahTurunan + 1;

   // Cek kode barang baru sudah ada atau belum (extra safety)
   $stmt = $conn->prepare("SELECT 1 FROM barang WHERE kdbarang = ?");
   $stmt->bind_param("i", $kdbarang_db);
   $stmt->execute();
   $stmt->store_result();
   if ($stmt->num_rows > 0) {
      echo "<script>alert('Kode barang turunan sudah ada, silakan ulangi proses.'); window.history.back();</script>";
      $stmt->close();
      $conn->close();
      exit;
   }
   $stmt->close();

   // Insert barang turunan
   $stmt = $conn->prepare("INSERT INTO barang (kdbarang, nmbarang, idcut, kodeinduk) VALUES (?, ?, ?, ?)");
   $stmt->bind_param("isii", $kdbarang_db, $nmbarang, $cut, $kodeinduk);

   if ($stmt->execute()) {
      echo "<script>alert('Data barang turunan berhasil disimpan dengan kode: $kdbarang_db'); window.location='barang.php';</script>";
   } else {
      echo "<script>alert('Gagal menyimpan data barang turunan.'); window.history.back();</script>";
   }
   $stmt->close();
} else {
   echo "<script>alert('Tipe barang tidak valid.'); window.history.back();</script>";
}

$conn->close();
