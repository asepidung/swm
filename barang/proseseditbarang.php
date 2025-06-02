<?php
require "../verifications/auth.php";
require "../konak/conn.php";

$idbarang = $_POST['idbarang'] ?? 0;
$tipebarang_baru = $_POST['tipebarang'] ?? '';
$kdbarang_input = isset($_POST['kdbarang']) ? intval($_POST['kdbarang']) : 0;
$nmbarang = trim($_POST['nmbarang'] ?? '');
$cut = $_POST['cut'] ?? '';
$kodeinduk_baru = isset($_POST['kodeinduk']) ? intval($_POST['kodeinduk']) : null;

if (!$idbarang || !$nmbarang || !$cut) {
  echo "<script>alert('Mohon lengkapi data wajib diisi.'); window.history.back();</script>";
  exit;
}

// Ambil data lama
$stmt = $conn->prepare("SELECT kdbarang, kodeinduk FROM barang WHERE idbarang = ?");
$stmt->bind_param("i", $idbarang);
$stmt->execute();
$stmt->bind_result($kdbarang_lama, $kodeinduk_lama);
$stmt->fetch();
$stmt->close();

$tipebarang_lama = is_null($kodeinduk_lama) ? 'utama' : 'turunan';

// Tentukan kode barang yang akan disimpan
if ($tipebarang_baru === 'utama') {
  if (!$kdbarang_input) {
    echo "<script>alert('Kode barang wajib diisi untuk barang utama.'); window.history.back();</script>";
    exit;
  }
  $kdbarang_db = $kdbarang_input;
} elseif ($tipebarang_baru === 'turunan') {
  if (!$kodeinduk_baru) {
    echo "<script>alert('Barang induk wajib dipilih untuk produk turunan.'); window.history.back();</script>";
    exit;
  }
  // Jika tipe berubah dari utama ke turunan atau pindah induk turunan, generate kode baru
  if ($tipebarang_lama === 'utama' || $kodeinduk_lama !== $kodeinduk_baru) {
    // Hitung jumlah turunan induk baru
    $stmt = $conn->prepare("SELECT COUNT(*) FROM barang WHERE kodeinduk = ?");
    $stmt->bind_param("i", $kodeinduk_baru);
    $stmt->execute();
    $stmt->bind_result($jumlahTurunan);
    $stmt->fetch();
    $stmt->close();

    $kdbarang_db = $kodeinduk_baru + $jumlahTurunan + 1;

    // Cek apakah kode sudah dipakai
    $stmt = $conn->prepare("SELECT 1 FROM barang WHERE kdbarang = ? AND idbarang != ?");
    $stmt->bind_param("ii", $kdbarang_db, $idbarang);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      echo "<script>alert('Kode barang turunan sudah ada, silakan coba lagi.'); window.history.back();</script>";
      $stmt->close();
      $conn->close();
      exit;
    }
    $stmt->close();
  } else {
    // Tetap pakai kode lama jika tidak pindah induk
    $kdbarang_db = $kdbarang_lama;
  }
} else {
  echo "<script>alert('Tipe barang tidak valid.'); window.history.back();</script>";
  exit;
}

// Cek duplikat kode barang di luar record ini (untuk tipe utama atau turunan yg kode tetap)
$stmt = $conn->prepare("SELECT 1 FROM barang WHERE kdbarang = ? AND idbarang != ?");
$stmt->bind_param("ii", $kdbarang_db, $idbarang);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
  echo "<script>alert('Kode barang sudah digunakan oleh data lain.'); window.history.back();</script>";
  $stmt->close();
  $conn->close();
  exit;
}
$stmt->close();

// Cek duplikat nama barang di luar record ini
$stmt = $conn->prepare("SELECT 1 FROM barang WHERE nmbarang = ? AND idbarang != ?");
$stmt->bind_param("si", $nmbarang, $idbarang);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
  echo "<script>alert('Nama barang sudah digunakan oleh data lain.'); window.history.back();</script>";
  $stmt->close();
  $conn->close();
  exit;
}
$stmt->close();

if ($tipebarang_baru === 'utama') {
  $kodeinduk_db = null;
} else {
  $kodeinduk_db = $kodeinduk_baru;
}

if ($kodeinduk_db === null) {
  $stmt = $conn->prepare("UPDATE barang SET kdbarang = ?, nmbarang = ?, idcut = ?, kodeinduk = NULL WHERE idbarang = ?");
  $stmt->bind_param("ssii", $kdbarang_db, $nmbarang, $cut, $idbarang);
} else {
  $stmt = $conn->prepare("UPDATE barang SET kdbarang = ?, nmbarang = ?, idcut = ?, kodeinduk = ? WHERE idbarang = ?");
  $stmt->bind_param("ssiii", $kdbarang_db, $nmbarang, $cut, $kodeinduk_db, $idbarang);
}

if ($stmt->execute()) {
  echo "<script>alert('Data barang berhasil diperbarui.'); window.location='barang.php';</script>";
} else {
  echo "<script>alert('Gagal memperbarui data barang.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
