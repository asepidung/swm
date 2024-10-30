<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";

// Validasi dan sanitasi idcarcase dari URL
$idcarcase = $_GET['idcarcase'] ?? null;
if (!$idcarcase || !is_numeric($idcarcase)) {
   echo "<script>alert('ID Carcase tidak valid!'); window.location='datacarcase.php';</script>";
   exit();
}

// Mulai transaksi
$conn->begin_transaction();

try {
   // Hapus data di tabel carcasedetail yang memiliki idcarcase terkait
   $deleteDetailQuery = "DELETE FROM carcasedetail WHERE idcarcase = ?";
   $deleteDetailStmt = $conn->prepare($deleteDetailQuery);
   $deleteDetailStmt->bind_param("i", $idcarcase);
   $deleteDetailStmt->execute();

   // Hapus data di tabel carcase yang memiliki idcarcase terkait
   $deleteCarcaseQuery = "DELETE FROM carcase WHERE idcarcase = ?";
   $deleteCarcaseStmt = $conn->prepare($deleteCarcaseQuery);
   $deleteCarcaseStmt->bind_param("i", $idcarcase);
   $deleteCarcaseStmt->execute();

   // Commit transaksi jika semua berhasil
   $conn->commit();

   // Notifikasi sukses dan redirect ke halaman datacarcase.php
   echo "<script>alert('Data berhasil dihapus!'); window.location='datacarcase.php';</script>";
} catch (Exception $e) {
   // Rollback transaksi jika terjadi kesalahan
   $conn->rollback();

   // Notifikasi gagal dan redirect ke halaman datacarcase.php
   echo "<script>alert('Gagal menghapus data! Silakan coba lagi.'); window.location='datacarcase.php';</script>";
}

// Tutup koneksi dan statement
$deleteDetailStmt->close();
$deleteCarcaseStmt->close();
$conn->close();
