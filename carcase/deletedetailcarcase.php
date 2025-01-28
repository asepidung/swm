<?php
require "../konak/conn.php";

// Periksa apakah parameter iddetail dan idcarcase ada
if (isset($_GET['iddetail']) && isset($_GET['idcarcase'])) {
    // Ambil nilai dari parameter GET dan pastikan aman dengan intval
    $iddetail = intval($_GET['iddetail']);
    $idcarcase = intval($_GET['idcarcase']);

    // Mulai koneksi dan coba menghapus data
    try {
        // Query untuk menghapus data dari carcasedetail berdasarkan iddetail
        $deleteQuery = "DELETE FROM carcasedetail WHERE iddetail = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);

        if (!$stmt) {
            throw new Exception("Gagal mempersiapkan statement: " . mysqli_error($conn));
        }

        // Bind parameter dan eksekusi query
        mysqli_stmt_bind_param($stmt, "i", $iddetail);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Gagal menghapus data: " . mysqli_stmt_error($stmt));
        }

        // Tutup statement
        mysqli_stmt_close($stmt);

        // Redirect ke halaman editcarcase.php dengan idcarcase terkait
        header("Location: editcarcase.php?idcarcase=" . $idcarcase);
        exit;
    } catch (Exception $e) {
        // Jika terjadi kesalahan, tampilkan pesan error
        echo "Terjadi kesalahan: " . $e->getMessage();
    } finally {
        // Tutup koneksi ke database
        mysqli_close($conn);
    }
} else {
    // Jika parameter tidak lengkap, redirect ke halaman editcarcase tanpa id
    header("Location: editcarcase.php");
    exit;
}
