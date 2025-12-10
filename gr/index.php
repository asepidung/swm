<?php
require "../verifications/auth.php";
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
                                        <th>Requester</th>
                                        <th>Supplier</th>
                                        <th>PO Number</th>
                                        <th>Req Number</th>
                                        <th>Receiving Date</th>
                                        <!-- <th>Supplier ID</th> -->
                                        <th>Note</th>
                                        <th>Made By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Query: ambil requester dari tabel request (request.iduser) dan fullname pembuat GR dari grraw.idusers
                                    $query = "
                                        SELECT grraw.*,
                                               supplier.nmsupplier,
                                               po.nopo,
                                               request.norequest,
                                               request.iduser AS requester_id,
                                               um.fullname AS made_by,
                                               ur.fullname AS requester_name
                                        FROM grraw
                                        LEFT JOIN po ON grraw.idpo = po.idpo
                                        LEFT JOIN request ON po.idrequest = request.idrequest
                                        JOIN supplier ON grraw.idsupplier = supplier.idsupplier
                                        LEFT JOIN users um ON grraw.idusers = um.idusers        -- pembuat GR
                                        LEFT JOIN users ur ON request.iduser = ur.idusers      -- requester
                                        WHERE grraw.is_deleted = 0
                                        ORDER BY grraw.idgr DESC
                                    ";
                                    $ambildata = mysqli_query($conn, $query);

                                    if (!$ambildata) {
                                        die("Query error: " . htmlspecialchars(mysqli_error($conn)));
                                    }

                                    if (mysqli_num_rows($ambildata) === 0) {
                                        echo "<tr><td colspan='10' class='text-center'>No data available</td></tr>";
                                    } else {
                                        while ($tampil = mysqli_fetch_assoc($ambildata)) {
                                            $idgr = isset($tampil['idgr']) ? (int)$tampil['idgr'] : 0;
                                            $idpo = isset($tampil['idpo']) ? (int)$tampil['idpo'] : 0;
                                            $grnumber = isset($tampil['grnumber']) ? $tampil['grnumber'] : '';
                                            $nmsupplier = isset($tampil['nmsupplier']) ? $tampil['nmsupplier'] : '';
                                            $nopo = isset($tampil['nopo']) ? $tampil['nopo'] : '';
                                            $norequest = isset($tampil['norequest']) ? $tampil['norequest'] : '';
                                            $receivedate = isset($tampil['receivedate']) ? $tampil['receivedate'] : '';
                                            $note = isset($tampil['note']) ? $tampil['note'] : '';
                                            $made_by = isset($tampil['made_by']) ? $tampil['made_by'] : '';
                                            $requester_name = isset($tampil['requester_name']) ? $tampil['requester_name'] : '';
                                            // fallback: kalau requester_name kosong, bisa tampilkan '-' atau requester_id
                                            if ($requester_name === '' && !empty($tampil['requester_id'])) {
                                                $requester_name = 'User ID: ' . htmlspecialchars($tampil['requester_id']);
                                            }
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $no; ?></td>
                                                <td class="text-center"><?= htmlspecialchars($grnumber); ?></td>
                                                <td><?= htmlspecialchars($requester_name); ?></td>
                                                <td><?= htmlspecialchars($nmsupplier); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($nopo); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($norequest); ?></td>
                                                <td class="text-center">
                                                    <?= $receivedate ? htmlspecialchars(date("d-M-y", strtotime($receivedate))) : ''; ?>
                                                </td>
                                                <!-- <td><?= $tampil['suppcode']; ?></td> -->
                                                <td><?= htmlspecialchars($note); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($made_by); ?></td>
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
                                                    <a href="delete.php?idgr=<?= $idgr; ?>&idpo=<?= $idpo ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                            $no++;
                                        } // end while
                                    } // end else
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
    document.title = "Goods Receipt List";
</script>
<?php
include "../footer.php";
?>