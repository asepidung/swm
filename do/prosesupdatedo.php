<?php
require "../konak/conn.php";

$iddo = $_POST['iddo'];
$deliverydate = $_POST['deliverydate'];
$idcustomer = $_POST['idcustomer'];
$po = $_POST['po'];
$driver = $_POST['driver'];
$plat = $_POST['plat'];
$note = $_POST['note'];
$xbox = $_POST['xbox'];
$xweight = $_POST['xweight'];

$query_do = "UPDATE do SET deliverydate = ?, idcustomer = ?, po = ?, driver = ?, plat = ?, note = ?,  xbox = ?,  xweight = ? WHERE iddo = ?";
$stmt_do = mysqli_prepare($conn, $query_do);
mysqli_stmt_bind_param($stmt_do, "sissssidi", $deliverydate, $idcustomer, $po, $driver, $plat, $note, $xbox, $xweight, $iddo);

if (mysqli_stmt_execute($stmt_do)) {
  $delete_query = "DELETE FROM dodetail WHERE iddo = ?";
  $stmt_delete = mysqli_prepare($conn, $delete_query);
  mysqli_stmt_bind_param($stmt_delete, "i", $iddo);
  mysqli_stmt_execute($stmt_delete);

  $idgrade = $_POST['idgrade'];
  $idbarang = $_POST['idbarang'];
  $box = $_POST['box'];
  $weight = $_POST['weight'];
  $notes = $_POST['notes'];

  $query_dodetail = "INSERT INTO dodetail (iddo, idgrade, idbarang, box, weight, notes) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt_dodetail = mysqli_prepare($conn, $query_dodetail);
  mysqli_stmt_bind_param($stmt_dodetail, "iiiiis", $iddo, $idgrade_val, $idbarang_val, $box_val, $weight_val, $notes_val);

  for ($i = 0; $i < count($idgrade); $i++) {
    $idgrade_val = $idgrade[$i];
    $idbarang_val = $idbarang[$i];
    $box_val = $box[$i];
    $weight_val = $weight[$i];
    $notes_val = $notes[$i];
    mysqli_stmt_execute($stmt_dodetail);
  }

  mysqli_stmt_close($stmt_do);
  mysqli_stmt_close($stmt_delete);
  mysqli_stmt_close($stmt_dodetail);
  mysqli_close($conn);

  // Cek apakah tombol "approve" telah diklik
  if (isset($_POST['approve'])) {
    // Ambil nilai iddo dari form
    $iddo = $_POST['iddo'];

    // Lakukan koneksi ke database
    require "../konak/conn.php";

    // Update field "status" menjadi "approved" di tabel "do"
    $query = "UPDATE do SET status = 'Approved' WHERE iddo = '$iddo'";
    $result = mysqli_query($conn, $query);

    // Periksa apakah update berhasil
    if ($result) {
      // Redirect atau lakukan tindakan lain setelah berhasil diupdate
      // ...
    } else {
      // Penanganan kesalahan jika update gagal
      // ...
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
  }

  header("location: do.php");
} else {
  echo "Terjadi kesalahan: " . mysqli_error($conn);
  mysqli_stmt_close($stmt_do);
  mysqli_close($conn);
}
