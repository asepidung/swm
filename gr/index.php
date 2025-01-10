<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- <a href="newgr.php"><button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a> -->
                    <a href="draft.php"><button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Draft</button></a>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>GR Number</th>
                                        <th>Receiving Date</th>
                                        <th>Supplier</th>
                                        <th>Note</th>
                                        <th>Made By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Query untuk mengambil data dari tabel grraw dan tabel terkait
                                    $query = "
                                    SELECT grraw.*, supplier.nmsupplier, po.nopo, users.fullname 
                                    FROM grraw
                                    LEFT JOIN po ON grraw.idpo = po.idpo
                                    JOIN supplier ON grraw.idsupplier = supplier.idsupplier
                                    LEFT JOIN users ON grraw.idusers = users.idusers
                                    WHERE grraw.is_deleted = 0
                                    ORDER BY grraw.idgr DESC";
                                    $ambildata = mysqli_query($conn, $query);

                                    if (!$ambildata) {
                                        die("Query error: " . mysqli_error($conn));
                                    }

                                    while ($tampil = mysqli_fetch_array($ambildata)) {
                                        $idgr = $tampil['idgr'];
                                        $idpo = $tampil['idpo'];
                                        $fullname = $tampil['fullname']; // Mengambil nama pembuat GR
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no; ?></td>
                                            <td class="text-center"><?= htmlspecialchars($tampil['grnumber']); ?></td>
                                            <td class="text-center"><?= date("d-M-y", strtotime($tampil['receivedate'])); ?></td>
                                            <td><?= htmlspecialchars($tampil['nmsupplier']); ?></td>
                                            <td><?= htmlspecialchars($tampil['note']); ?></td>
                                            <td class="text-center"><?= htmlspecialchars($fullname); ?></td>
                                            <td class="text-center">
                                                <!-- <a href="grscan.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-warning" title="Scan">
                                                    <i class="fas fa-barcode"></i>
                                                </a> -->
                                                <!-- <a href="grdetail.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-primary" title="Label">
                                                    <i class="fas fa-tag"></i>
                                                </a> -->
                                                <a href="view.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-success" title="Lihat">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                                <a href="edit.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <!-- <a href="partial.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-warning" title="Partial">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a> -->
                                                <a href="delete.php?idgr=<?= $idgr; ?> & idpo=<?= $idpo ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php $no++;
                                    } ?>
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
    document.title = "Goods Receipt List";
</script>
<?php
include "../footer.php";
?>