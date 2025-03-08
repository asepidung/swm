<?php
require "../verifications/auth.php";
require "../konak/conn.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
   die("Parameter ID tidak valid.");
}

$idst = intval($_GET['id']);

// Mulai transaksi database untuk menjaga konsistensi data
$conn->begin_transaction();

try {
   // **1️⃣ Ambil nomor stock take (nost) dari tabel stocktake**
   $query_nost = "SELECT nost FROM stocktake WHERE idst = ?";
   $stmt_nost = $conn->prepare($query_nost);
   $stmt_nost->bind_param("i", $idst);
   $stmt_nost->execute();
   $result_nost = $stmt_nost->get_result();

   if ($result_nost->num_rows > 0) {
      $row_nost = $result_nost->fetch_assoc();
      $nost = $row_nost['nost'];
   } else {
      throw new Exception("Stock Take tidak ditemukan.");
   }
   $stmt_nost->close();

   // **2️⃣ Cek apakah ada barang yang belum terscan (missing stock)**
   $checkMissingQuery = "
        SELECT COUNT(*) AS total_missing 
        FROM stock 
        WHERE kdbarcode NOT IN (SELECT kdbarcode FROM stocktakedetail WHERE idst = ?)
    ";
   $stmtCheckMissing = $conn->prepare($checkMissingQuery);
   $stmtCheckMissing->bind_param("i", $idst);
   $stmtCheckMissing->execute();
   $resultCheckMissing = $stmtCheckMissing->get_result();
   $rowCheckMissing = $resultCheckMissing->fetch_assoc();
   $total_missing = $rowCheckMissing['total_missing'] ?? 0;
   $stmtCheckMissing->close();

   // **3️⃣ Jika masih ada barang yang belum terscan, simpan ke `missing_stock`**
   if ($total_missing > 0) {
      $insertMissingStock = "
            INSERT INTO missing_stock (idst, kdbarcode, idgrade, idbarang, qty, pcs, pod, origin)
            SELECT ?, kdbarcode, idgrade, idbarang, qty, pcs, pod, origin 
            FROM stock 
            WHERE kdbarcode NOT IN (SELECT kdbarcode FROM stocktakedetail WHERE idst = ?)
        ";
      $stmtMissing = $conn->prepare($insertMissingStock);
      $stmtMissing->bind_param("ii", $idst, $idst);
      if (!$stmtMissing->execute()) {
         throw new Exception("Gagal menyimpan data barang yang belum terscan.");
      }
      $stmtMissing->close();
   }

   // **4️⃣ Hapus semua data di tabel stock**
   $deleteStock = "DELETE FROM stock";
   $stmtDelete = $conn->prepare($deleteStock);
   if (!$stmtDelete->execute()) {
      throw new Exception("Gagal menghapus data dari tabel stock.");
   }
   $stmtDelete->close();

   // **5️⃣ Ambil data dari stocktakedetail**
   $sql = "SELECT kdbarcode, idgrade, idbarang, qty, pcs, pod, origin FROM stocktakedetail WHERE idst = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("i", $idst);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows === 0) {
      throw new Exception("Tidak ada data stocktakedetail untuk diinput ke stock.");
   }

   // **6️⃣ Masukkan data dari `stocktakedetail` ke `stock`**
   $insertSql = "INSERT INTO stock (kdbarcode, idgrade, idbarang, qty, pcs, pod, origin) VALUES (?, ?, ?, ?, ?, ?, ?)";
   $insertStmt = $conn->prepare($insertSql);

   while ($row = $result->fetch_assoc()) {
      $insertStmt->bind_param("siidisi", $row['kdbarcode'], $row['idgrade'], $row['idbarang'], $row['qty'], $row['pcs'], $row['pod'], $row['origin']);
      if (!$insertStmt->execute()) {
         throw new Exception("Gagal menyisipkan data ke tabel stock.");
      }
   }

   $stmt->close();
   $insertStmt->close();

   // **7️⃣ Insert log activity untuk mencatat perubahan stock**
   $event = "Stock Take Confirm";
   $iduser = $_SESSION['idusers'];
   $logQuery = "INSERT INTO logactivity (iduser, event, docnumb, waktu) VALUES (?, ?, ?, NOW())";
   $stmt_log = $conn->prepare($logQuery);
   $stmt_log->bind_param("iss", $iduser, $event, $nost);
   if (!$stmt_log->execute()) {
      throw new Exception("Gagal mencatat log aktivitas.");
   }
   $stmt_log->close();

   // **8️⃣ Update kolom `stocked` di `stocktake` menjadi 1 setelah konfirmasi berhasil**
   $updateStocked = "UPDATE stocktake SET stocked = 1 WHERE idst = ?";
   $stmtUpdateStocked = $conn->prepare($updateStocked);
   $stmtUpdateStocked->bind_param("i", $idst);
   if (!$stmtUpdateStocked->execute()) {
      throw new Exception("Gagal memperbarui status stocked.");
   }
   $stmtUpdateStocked->close();

   // **9️⃣ Commit transaksi jika semua berhasil**
   $conn->commit();
   $conn->close();

   header("Location: index.php");
   exit();
} catch (Exception $e) {
   // Rollback jika ada kesalahan
   $conn->rollback();
   $conn->close();

   // Tampilkan pesan error untuk debugging (Bisa dihapus di produksi)
   die("Terjadi kesalahan: " . $e->getMessage());
}
