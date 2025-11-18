<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Query untuk mendapatkan data dari tabel po yang is_deleted = 0 dan stat = 0, join dengan tabel supplier
$query = "SELECT p.idpo, s.nmsupplier, p.duedate AS deliveryat, p.nopo, r.norequest, p.note, p.stat
          FROM po p
          JOIN supplier s ON p.idsupplier = s.idsupplier
          JOIN request r ON p.idrequest = r.idrequest
          WHERE p.is_deleted = 0 AND p.stat = 0";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mt-3">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Supplier</th>
                                        <th>Receiving Date</th>
                                        <th>PO</th>
                                        <th>Request No</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $idpo = isset($row['idpo']) ? (int)$row['idpo'] : 0;
                                        $nmsupplier = isset($row['nmsupplier']) ? $row['nmsupplier'] : '';
                                        $deliveryat = isset($row['deliveryat']) ? $row['deliveryat'] : '';
                                        $nopo = isset($row['nopo']) ? $row['nopo'] : '';
                                        $norequest = isset($row['norequest']) ? $row['norequest'] : '';
                                        $note = isset($row['note']) ? $row['note'] : '';
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $counter++; ?></td>
                                            <td><?php echo htmlspecialchars($nmsupplier); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($deliveryat); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($nopo); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($norequest); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($note); ?></td>
                                            <td class="text-center">
                                                <a class="btn btn-primary btn-xs"
                                                    data-toggle="tooltip"
                                                    data-placement="bottom"
                                                    title="Buat GR"
                                                    href="newgr.php?id=<?php echo $idpo; ?>">
                                                    Proses GR <i class="fas fa-truck"></i>
                                                </a>

                                                <a class="btn btn-danger btn-xs"
                                                    data-toggle="tooltip"
                                                    data-placement="bottom"
                                                    title="Cancel"
                                                    href="cancel.php?id=<?php echo $idpo; ?>"
                                                    onclick="return confirm('Apa Kamu Yakin Ingin Menolak GR ini?');">
                                                    Cancel <i class="fas fa-window-close"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    // Mengubah judul halaman web
    document.title = "DRAFT GR";
</script>
<?php
include "../footer.php";
?>