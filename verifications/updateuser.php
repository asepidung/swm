<?php
// Include necessary files for session and database connection
include 'auth.php'; // for session checking
require "../konak/conn.php"; // pastikan file koneksi berada di path yang benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $userid = $_POST['userid'];
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];

    // Query untuk mengambil data pengguna berdasarkan idusers
    $query = "SELECT * FROM users WHERE idusers = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Verifikasi password lama
        if (password_verify($password, $user['passuser'])) {
            // Jika password lama valid, perbarui password jika ada password baru
            if (!empty($new_password)) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            } else {
                // Jika password baru kosong, gunakan password lama
                $new_password_hash = $user['passuser']; // Tidak mengubah password jika kosong
            }

            // Query untuk memperbarui data pengguna
            $update_query = "UPDATE users SET userid = ?, fullname = ?, passuser = ? WHERE idusers = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("sssi", $userid, $fullname, $new_password_hash, $id);
            $update_stmt->execute();

            // Mengalihkan ke halaman sukses atau memberi pesan sukses
            // echo "Data pengguna berhasil diperbarui!";
            // Optional: Redirect ke halaman lain, misalnya:
            header('Location: ../index.php');
        } else {
            echo "Password lama yang Anda masukkan salah!";
        }
    } else {
        echo "User tidak ditemukan.";
    }
}
