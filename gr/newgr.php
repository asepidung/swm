<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT p.duedate AS deliveryat, p.nopo, s.nmsupplier, s.idsupplier, p.idpo 
          FROM po p 
          JOIN supplier s ON p.idsupplier = s.idsupplier 
          WHERE p.idpo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    include "../footer.php";
    exit();
}

$row = $result->fetch_assoc();
?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mt-3">
                    <form method="POST" action="inputgr.php">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="deliveryat">Receiving Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="deliveryat" id="deliveryat" value="<?= htmlspecialchars($row['deliveryat']) ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="nmsupplier">Supplier Name <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['nmsupplier']) ?>" required readonly>
                                                <input type="hidden" name="idsupplier" id="idsupplier" value="<?= htmlspecialchars($row['idsupplier']) ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="idnumber">Supplier Transaction Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="suppcode" id="suppcode" placeholder="Biarkan Kosong Jika Tidak Ada">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="nopo">PO Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($row['nopo']) ?>" readonly>
                                                <input type="hidden" name="idpo" id="idpo" value="<?= htmlspecialchars($row['idpo']) ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="note" id="note" placeholder="Catatan Untuk GR">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Raw Material Descriptions</th>
                                            <th>Order Quantity</th>
                                            <th>Received Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;

                                        // Query untuk mengambil data dari tabel podetail berdasarkan idpo
                                        $queryDetail = "SELECT pd.idrawmate, pd.qty AS order_qty, rm.nmrawmate 
                                                        FROM podetail pd 
                                                        JOIN rawmate rm ON pd.idrawmate = rm.idrawmate 
                                                        WHERE pd.idpo = ?";
                                        $stmtDetail = $conn->prepare($queryDetail);
                                        $stmtDetail->bind_param("i", $id);
                                        $stmtDetail->execute();
                                        $resultDetail = $stmtDetail->get_result();

                                        // Menampilkan data dari query
                                        while ($tampil = $resultDetail->fetch_assoc()) { ?>
                                            <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($tampil['nmrawmate']); ?></td>
                                                <td class="text-right"><?= number_format($tampil['order_qty'], 2); ?></td>
                                                <td class="text-right">
                                                    <input type="hidden" name="idrawmate[]" value="<?= $tampil['idrawmate']; ?>">
                                                    <input type="number" step="0.01" class="form-control" name="received_qty[]" placeholder="Qty diterima" required>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <div class="row mt-2">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')">Proses GR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Mengubah judul halaman web
    document.title = "New GR Raw Materials";
</script>

<?php
include "../footer.php";
?>