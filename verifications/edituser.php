<?php
// Include necessary files for session and database connection
include 'auth.php'; // for session checking
require "../konak/conn.php"; // pastikan file koneksi berada di path yang benar

// Check if ID is passed through URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data pengguna berdasarkan idusers
    $query = "SELECT * FROM users WHERE idusers = ?";
    $stmt = $conn->prepare($query); // menggunakan $conn sesuai dengan koneksi di conn.php
    $stmt->bind_param("i", $id); // pastikan $id bertipe integer
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Ambil data dari hasil query
        $fullname = $user['fullname'];
        $userid = $user['userid']; // ID pengguna
        $passuser = $user['passuser']; // Password pengguna (dalam bentuk hash)
    } else {
        echo "User tidak ditemukan.";
        exit();
    }
} else {
    echo "ID pengguna tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group span {
            color: #888;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Edit Pengguna: <?= htmlspecialchars($fullname) ?></h2>

        <form method="POST" action="updateuser.php">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="form-group">
                <label for="userid">ID Pengguna:</label>
                <input type="text" id="userid" name="userid" value="<?= htmlspecialchars($userid) ?>" required>
            </div>

            <div class="form-group">
                <label for="fullname">Nama Lengkap:</label>
                <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password Lama:</label>
                <input type="password" id="password" name="password" required>
                <span>Masukkan password lama untuk mengganti password</span>
            </div>

            <div class="form-group">
                <label for="new_password">Password Baru:</label>
                <input type="password" id="new_password" name="new_password">
                <span>Isi jika ingin mengganti password</span>
            </div>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>

</body>

</html>