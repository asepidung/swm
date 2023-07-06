<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $idboning = $_POST['idboning'];
  $idsupplier = $_POST['idsupplier'];
  $tglboning = $_POST['tglboning'];
  $qtysapi = $_POST['qtysapi'];

  // Lakukan validasi form jika diperlukan

  // Update data boning di database
  $query = "UPDATE boning SET idsupplier = '$idsupplier', tglboning = '$tglboning', qtysapi = $qtysapi WHERE idboning = '$idboning'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    // Jika data berhasil diupdate, redirect ke halaman lain atau lakukan tindakan lainnya
    header("location: databoning.php");
    exit();
  } else {
    // Jika terjadi kesalahan saat mengupdate data, tampilkan pesan error atau lakukan tindakan lainnya
    echo "Error: " . mysqli_error($conn);
  }
}

// Jika halaman ini diakses tanpa melalui metode POST, redirect ke halaman lain atau lakukan tindakan lainnya
header("location: editboning.php");
exit();
