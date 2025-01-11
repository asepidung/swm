<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location: verifications/login.php");
    exit(); // Menghentikan eksekusi setelah redirect
}
require "../konak/conn.php";

$idgr = isset($_GET['idgr']) ? intval($_GET['idgr']) : 0;
$idusers = $_SESSION['idusers'];

// Query untuk mengambil data dari tabel grraw
$query = "
SELECT grraw.*, supplier.nmsupplier 
FROM grraw 
JOIN supplier ON grraw.idsupplier = supplier.idsupplier 
WHERE grraw.idgr = ? AND grraw.is_deleted = 0"; // Tambahkan kondisi is_deleted = 0
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idgr);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    die("<div class='alert alert-danger'>Data tidak ditemukan.</div>");
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../dist/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <style>
        body {
            font-family: Cambria, sans-serif;
            font-size: 18px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="container">
            <div class="row mb-2">
                <img src="../dist/img/headerquo.png" alt="Logo-grraw" class="img-fluid">
            </div>
            <h4 class="text-center">BUKTI TERIMA BARANG</h4>
            <h5 class="text-center">No :<b><i> <?= htmlspecialchars($row['grnumber']); ?></i></b></h5>
            <table class="table table-sm table-borderless mt-3">
                <tr>
                    <td width="23%">Supplier</td>
                    <td width="1%">:</td>
                    <td><?= htmlspecialchars($row['nmsupplier']); ?></td>
                </tr>
                <tr>
                    <td width="23%">Receiving Date</td>
                    <td width="1%">:</td>
                    <td><?= htmlspecialchars(date('d-M-Y', strtotime($row['receivedate']))); ?></td>
                </tr>
            </table>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr class="text-center">
                        <th width="2%">NO</th>
                        <th>Product Descriptions</th>
                        <th>Ordered Quantity</th>
                        <th>Received Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query untuk mengambil detail dari tabel grrawdetail
                    $querydetail = "
                    SELECT 
                        rm.nmrawmate, 
                        grd.orderqty AS ordered_qty, 
                        grd.qty AS received_qty
                    FROM grrawdetail grd
                    JOIN rawmate rm ON grd.idrawmate = rm.idrawmate
                    WHERE grd.idgr = ? AND grd.is_deleted = 0"; // Tambahkan kondisi is_deleted = 0
                    $stmtdetail = $conn->prepare($querydetail);
                    $stmtdetail->bind_param("i", $idgr);
                    $stmtdetail->execute();
                    $resultdetail = $stmtdetail->get_result();

                    $counter = 1;

                    while ($rowdetail = $resultdetail->fetch_assoc()) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $counter++; ?></td>
                            <td><?= htmlspecialchars($rowdetail['nmrawmate']); ?></td>
                            <td class="text-right"><?= htmlspecialchars(number_format($rowdetail['ordered_qty'], 2)); ?></td>
                            <td class="text-right"><?= htmlspecialchars(number_format($rowdetail['received_qty'], 2)); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-center">TOTAL</th>
                        <th class="text-right">
                            <?php
                            // Hitung total ordered_qty yang belum di-soft delete
                            $query_total_order = "SELECT SUM(orderqty) AS total_order FROM grrawdetail WHERE idgr = ? AND is_deleted = 0"; // Tambahkan kondisi is_deleted = 0
                            $stmt_total_order = $conn->prepare($query_total_order);
                            $stmt_total_order->bind_param("i", $idgr);
                            $stmt_total_order->execute();
                            $result_total_order = $stmt_total_order->get_result();
                            $total_order_row = $result_total_order->fetch_assoc();
                            echo htmlspecialchars(number_format($total_order_row['total_order'], 2));
                            ?>
                        </th>
                        <th class="text-right">
                            <?php
                            // Hitung total received_qty yang belum di-soft delete
                            $query_total_received = "SELECT SUM(qty) AS total_received FROM grrawdetail WHERE idgr = ? AND is_deleted = 0"; // Tambahkan kondisi is_deleted = 0
                            $stmt_total_received = $conn->prepare($query_total_received);
                            $stmt_total_received->bind_param("i", $idgr);
                            $stmt_total_received->execute();
                            $result_total_received = $stmt_total_received->get_result();
                            $total_received_row = $result_total_received->fetch_assoc();
                            echo htmlspecialchars(number_format($total_received_row['total_received'], 2));
                            ?>
                        </th>
                    </tr>
                </tfoot>
            </table>

            <div class="row mt-3">
                <div class="col-6 float-right text-justify">
                    <?php if (!empty($row['note'])) { ?>
                        Catatan : <strong><?= htmlspecialchars($row['note']); ?></strong>
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-8"></div>
                <div class="col-4 text-center">
                    RECEIVING STAFF
                    <br><br><br><br><br>
                    <?= htmlspecialchars($_SESSION['fullname']); ?>
                </div>
            </div>
            <div class="col">
                <p><strong>Penting !</strong></p>
                <li>Dokumen ini diperuntukkan sebagai bukti penerimaan Barang oleh PT. SANTI WIJAYA MEAT</li>
                <li>Dokumen Ini Wajib Dibawa Ketika Akan Melakukan Tukar Faktur</li>
            </div>
        </div>
    </div>
    <div class="row mt-3 justify-content-center no-print">
        <div class="col-6 col-sm-4 col-md-3 mb-2">
            <a href="javascript:history.back()">
                <button type="button" class="btn btn-block btn-success"><i class="fas fa-undo"></i></button>
            </a>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
            <button type="button" class="btn btn-block btn-warning" onclick="window.print()">
                <i class="fas fa-print"></i>
            </button>
        </div>
    </div>
    <script>
        // Mengubah judul halaman web
        document.title = "<?= htmlspecialchars($row['grnumber']); ?>";
    </script>
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../dist/js/adminlte.min.js"></script>
</body>

</html>