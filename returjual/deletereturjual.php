<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if (isset($_GET['idreturjual'])) {
  $idreturjual = $_GET['idreturjual'];

  // Mulai transaksi
  $conn->autocommit(false);

  try {
      // Ambil semua kdbarcode dari tabel returjualdetail berdasarkan idreturjual
      $query_get_barcodes = "SELECT kdbarcode FROM returjualdetail WHERE idreturjual = ?";
      $stmt_get_barcodes = $conn->prepare($query_get_barcodes);
      $stmt_get_barcodes->bind_param("i", $idreturjual);
      $stmt_get_barcodes->execute();
      $result_barcodes = $stmt_get_barcodes->get_result();

      $barcodes_returjualdetail = [];
      while ($row = $result_barcodes->fetch_assoc()) {
          $barcodes_returjualdetail[] = $row['kdbarcode'];
      }
      $stmt_get_barcodes->close();

      // Jika tidak ada barcode, tetap lanjutkan penghapusan returjual
      if (empty($barcodes_returjualdetail)) {
          // Soft delete data dari tabel returjual (set is_deleted = 1)
          $soft_delete_returjual = "UPDATE returjual SET is_deleted = 1 WHERE idreturjual = ?";
          $stmt_soft_delete_returjual = $conn->prepare($soft_delete_returjual);
          $stmt_soft_delete_returjual->bind_param("i", $idreturjual);
          if (!$stmt_soft_delete_returjual->execute()) {
              throw new Exception("Gagal melakukan soft delete pada tabel returjual.");
          }
          $stmt_soft_delete_returjual->close();

          // Commit transaksi jika semua query berhasil dieksekusi
          $conn->commit();
          header("location: index.php"); // Redirect to the list page
          exit();
      }

      // Cek kecocokan barcode antara returjualdetail dan stock
      $query_check_stock = "SELECT kdbarcode FROM stock WHERE kdbarcode IN (" . str_repeat('?,', count($barcodes_returjualdetail) - 1) . "?)";
      $stmt_check_stock = $conn->prepare($query_check_stock);
      $stmt_check_stock->bind_param(str_repeat("s", count($barcodes_returjualdetail)), ...$barcodes_returjualdetail);
      $stmt_check_stock->execute();
      $result_stock = $stmt_check_stock->get_result();

      $barcodes_stock = [];
      while ($row = $result_stock->fetch_assoc()) {
          $barcodes_stock[] = $row['kdbarcode'];
      }
      $stmt_check_stock->close();

      // Bandingkan barcode returjualdetail dan stock
      sort($barcodes_returjualdetail);
      sort($barcodes_stock);
      if ($barcodes_returjualdetail !== $barcodes_stock) {
          throw new Exception("Penghapusan Gagal, Barang Sudah digunakan atau tidak cocok.");
      }

      // Soft delete data dari tabel returjual (set is_deleted = 1)
      $soft_delete_returjual = "UPDATE returjual SET is_deleted = 1 WHERE idreturjual = ?";
      $stmt_soft_delete_returjual = $conn->prepare($soft_delete_returjual);
      $stmt_soft_delete_returjual->bind_param("i", $idreturjual);
      if (!$stmt_soft_delete_returjual->execute()) {
          throw new Exception("Gagal melakukan soft delete pada tabel returjual.");
      }
      $stmt_soft_delete_returjual->close();

      // Hapus data dari tabel stock berdasarkan barcode
      $query_delete_stock = "DELETE FROM stock WHERE kdbarcode IN (" . str_repeat('?,', count($barcodes_stock) - 1) . "?)";
      $stmt_delete_stock = $conn->prepare($query_delete_stock);
      $stmt_delete_stock->bind_param(str_repeat("s", count($barcodes_stock)), ...$barcodes_stock);
      if (!$stmt_delete_stock->execute()) {
          throw new Exception("Gagal menghapus data dari tabel stock.");
      }
      $stmt_delete_stock->close();

      // Commit transaksi jika semua query berhasil dieksekusi
      $conn->commit();
  } catch (Exception $e) {
      // Rollback transaksi jika terjadi kesalahan
      $conn->rollback();
      echo "<p>Error: " . $e->getMessage() . "</p>";
      echo "<a href='javascript:history.back()'>Kembali</a>";
      exit();
  } finally {
      // Set autocommit kembali ke true setelah selesai
      $conn->autocommit(true);
      $conn->close();
  }
}

header("location: index.php"); // Redirect to the list page
exit();
