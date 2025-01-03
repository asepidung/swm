<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../verifications/login.php");
    exit;
}

require "../konak/conn.php";

// Fungsi untuk membersihkan format angka
function normalizeNumber($number)
{
    $lastCommaPos = strrpos($number, ',');
    $lastDotPos = strrpos($number, '.');

    $decimalSeparator = $lastCommaPos > $lastDotPos ? ',' : '.';
    $thousandSeparator = $lastCommaPos < $lastDotPos ? ',' : '.';

    $number = str_replace($thousandSeparator, '', $number);
    if ($decimalSeparator != '.') {
        $number = str_replace($decimalSeparator, '.', $number);
    }

    return (float) $number;
}

// Ambil data dari form
$idrequest = $_POST['idrequest'] ?? null;
$duedate = $_POST['duedate'] ?? null;
$idsupplier = $_POST['idsupplier'] ?? null;
$other = $_POST['other'] ?? null;
$note = $_POST['note'] ?? null;
$taxrp = normalizeNumber($_POST['taxrp'] ?? 0);
$xamount = normalizeNumber($_POST['xamount'] ?? 0);

// Ambil data detail
$idrawmate = $_POST['idrawmate'] ?? [];
$weight = $_POST['weight'] ?? [];
$price = $_POST['price'] ?? [];
$notes = $_POST['notes'] ?? [];

// Validasi data
if (!$idrequest || !$duedate || !$idsupplier) {
    die("Error: Missing required fields.");
}

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Update data di tabel `request`
    $query_request = "UPDATE request SET duedate = ?, idsupplier = ?, other = ?, note = ?, taxrp = ?, xamount = ? WHERE idrequest = ?";
    $stmt_request = mysqli_prepare($conn, $query_request);
    mysqli_stmt_bind_param($stmt_request, "sissdsi", $duedate, $idsupplier, $other, $note, $taxrp, $xamount, $idrequest);

    if (!mysqli_stmt_execute($stmt_request)) {
        throw new Exception("Error updating request table: " . mysqli_stmt_error($stmt_request));
    }

    // Hapus detail lama untuk ID request ini
    $query_delete_details = "DELETE FROM requestdetail WHERE idrequest = ?";
    $stmt_delete_details = mysqli_prepare($conn, $query_delete_details);
    mysqli_stmt_bind_param($stmt_delete_details, "i", $idrequest);

    if (!mysqli_stmt_execute($stmt_delete_details)) {
        throw new Exception("Error deleting old request details: " . mysqli_stmt_error($stmt_delete_details));
    }

    // Masukkan detail baru
    $query_insert_details = "INSERT INTO requestdetail (idrequest, idrawmate, qty, price, notes) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert_details = mysqli_prepare($conn, $query_insert_details);

    foreach ($idrawmate as $i => $rawmate) {
        $qty = normalizeNumber($weight[$i] ?? 0); // Normalisasi qty
        $product_price = normalizeNumber($price[$i] ?? 0); // Normalisasi price
        $product_note = $notes[$i] ?? '';

        mysqli_stmt_bind_param($stmt_insert_details, "iiids", $idrequest, $rawmate, $qty, $product_price, $product_note);

        if (!mysqli_stmt_execute($stmt_insert_details)) {
            throw new Exception("Error inserting new request details: " . mysqli_stmt_error($stmt_insert_details));
        }
    }

    // Commit transaksi
    mysqli_commit($conn);

    // Redirect ke index.php
    header("Location: index.php");
    exit;

} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    mysqli_rollback($conn);
    die("Transaction failed: " . $e->getMessage());
}
