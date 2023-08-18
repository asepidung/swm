<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
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
$status = "Unapproved";

// Mengecek apakah ada iddo yang sama dengan $iddo di tabel 'doreceipt'
$check_doreceipt_query = "SELECT iddoreceipt FROM doreceipt WHERE iddo = ?";
$stmt_check_doreceipt = mysqli_prepare($conn, $check_doreceipt_query);
mysqli_stmt_bind_param($stmt_check_doreceipt, "i", $iddo);
mysqli_stmt_execute($stmt_check_doreceipt);
mysqli_stmt_store_result($stmt_check_doreceipt);

// Menghapus data di tabel 'doreceiptdetail' jika ada iddoreceipt yang sama
if (mysqli_stmt_num_rows($stmt_check_doreceipt) > 0) {
  mysqli_stmt_bind_result($stmt_check_doreceipt, $iddoreceipt);
  while (mysqli_stmt_fetch($stmt_check_doreceipt)) {
    $delete_doreceiptdetail_query = "DELETE FROM doreceiptdetail WHERE iddoreceipt = ?";
    $stmt_delete_doreceiptdetail = mysqli_prepare($conn, $delete_doreceiptdetail_query);
    mysqli_stmt_bind_param($stmt_delete_doreceiptdetail, "i", $iddoreceipt);
    mysqli_stmt_execute($stmt_delete_doreceiptdetail);
    mysqli_stmt_close($stmt_delete_doreceiptdetail);
  }
}

// Menghapus data di tabel 'doreceipt' yang memiliki iddo sama dengan tabel 'do'
$delete_doreceipt_query = "DELETE FROM doreceipt WHERE iddo = ?";
$stmt_delete_doreceipt = mysqli_prepare($conn, $delete_doreceipt_query);
mysqli_stmt_bind_param($stmt_delete_doreceipt, "i", $iddo);
mysqli_stmt_execute($stmt_delete_doreceipt);
mysqli_stmt_close($stmt_delete_doreceipt);

// Update data pada tabel 'do'
$query_do = "UPDATE do SET deliverydate = ?, idcustomer = ?, po = ?, driver = ?, plat = ?, note = ?,  xbox = ?,  xweight = ?,  status = ? WHERE iddo = ?";
$stmt_do = mysqli_prepare($conn, $query_do);
mysqli_stmt_bind_param($stmt_do, "sissssidsi", $deliverydate, $idcustomer, $po, $driver, $plat, $note, $xbox, $xweight, $status, $iddo);

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

  var_dump($_POST['idgrade']);
  var_dump($_POST['idbarang']);
  var_dump($_POST['box']);
  var_dump($_POST['weight']);
  var_dump($_POST['notes']);

  $query_dodetail = "INSERT INTO dodetail (iddo, idgrade, idbarang, box, weight, notes) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt_dodetail = mysqli_prepare($conn, $query_dodetail);
  mysqli_stmt_bind_param($stmt_dodetail, "iiiids", $iddo, $idgrade_val, $idbarang_val, $box_val, $weight_val, $notes_val);

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

  header("location: do.php");
} else {
  echo "Terjadi kesalahan: " . mysqli_error($conn);
  mysqli_stmt_close($stmt_do);
  mysqli_close($conn);
}
