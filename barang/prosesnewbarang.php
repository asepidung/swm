<?php
require "../verifications/auth.php";
require "../konak/conn.php";

$tipebarang = $_POST['tipebarang'] ?? '';
$nmbarang = strtoupper(trim($_POST['nmbarang'] ?? ''));
$cut = $_POST['cut'] ?? '';
$kodeinduk = isset($_POST['kodeinduk']) ? intval($_POST['kodeinduk']) : null;

if (!$tipebarang || !$nmbarang || !$cut) {
   echo "<script>alert('Mohon lengkapi data wajib diisi.'); window.history.back();</script>";
   exit;
}

if ($tipebarang === 'utama') {
   // Ambil digit kategori dari idcut (misal idcut=1 → kategoriDigit=1)
   $stmt = $conn->prepare("SELECT idcut FROM cuts WHERE idcut = ?");
   $stmt->bind_param("i", $cut);
   $stmt->execute();
   $stmt->bind_result($kategoriDigit);
   $stmt->fetch();
   $stmt->close();

   // Cari semua kdbarang di kategori ini untuk menghitung urutan tertinggi
   $stmt = $conn->prepare("SELECT kdbarang FROM barang WHERE idcut = ? AND kodeinduk IS NULL");
   $stmt->bind_param("i", $cut);
   $stmt->execute();
   $result = $stmt->get_result();

   $maxUrut = 0;
   while ($row = $result->fetch_assoc()) {
      $kode = str_pad($row['kdbarang'], 6, "0", STR_PAD_LEFT); // pastikan 6 digit
      $urut = intval(substr($kode, 1, 3)); // ambil 3 digit tengah
      if ($urut > $maxUrut) {
         $maxUrut = $urut;
      }
   }
   $stmt->close();

   // Nomor urut baru
   $nomorUrut = $maxUrut + 1;
   $kdbarang_db = ($kategoriDigit * 100000) + ($nomorUrut * 100); // hasil akhir

   // Cek duplikat nama barang
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

   // Simpan barang utama
   $stmt = $conn->prepare("INSERT INTO barang (kdbarang, nmbarang, idcut, kodeinduk) VALUES (?, ?, ?, NULL)");
   $stmt->bind_param("isi", $kdbarang_db, $nmbarang, $cut);

   if ($stmt->execute()) {
      echo "<script>alert('Barang utama berhasil disimpan dengan kode: $kdbarang_db'); window.location='barang.php';</script>";
   } else {
      echo "<script>alert('Gagal menyimpan barang utama.'); window.history.back();</script>";
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

   // Hitung jumlah turunan dari induk
   $stmt = $conn->prepare("SELECT COUNT(*) FROM barang WHERE kodeinduk = ?");
   $stmt->bind_param("i", $kodeinduk);
   $stmt->execute();
   $stmt->bind_result($jumlahTurunan);
   $stmt->fetch();
   $stmt->close();

   $kdbarang_db = $kodeinduk + $jumlahTurunan + 1;

   // Cek kode sudah dipakai atau belum
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

   // Simpan barang turunan
   $stmt = $conn->prepare("INSERT INTO barang (kdbarang, nmbarang, idcut, kodeinduk) VALUES (?, ?, ?, ?)");
   $stmt->bind_param("isii", $kdbarang_db, $nmbarang, $cut, $kodeinduk);

   if ($stmt->execute()) {
      echo "<script>alert('Barang turunan berhasil disimpan dengan kode: $kdbarang_db'); window.location='barang.php';</script>";
   } else {
      echo "<script>alert('Gagal menyimpan barang turunan.'); window.history.back();</script>";
   }
   $stmt->close();
} else {
   echo "<script>alert('Tipe barang tidak valid.'); window.history.back();</script>";
}

$conn->close();
