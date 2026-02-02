<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <a href="draft.php" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus"></i> Draft
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
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
                                        <th>Note</th>
                                        <th>Made By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = "
                                        SELECT 
                                            grraw.*,
                                            supplier.nmsupplier,
                                            po.nopo,
                                            po.stat AS po_stat,
                                            request.norequest,
                                            request.iduser AS requester_id,
                                            um.fullname AS made_by,
                                            ur.fullname AS requester_name
                                        FROM grraw
                                        LEFT JOIN po ON grraw.idpo = po.idpo
                                        LEFT JOIN request ON po.idrequest = request.idrequest
                                        JOIN supplier ON grraw.idsupplier = supplier.idsupplier
                                        LEFT JOIN users um ON grraw.idusers = um.idusers
                                        LEFT JOIN users ur ON request.iduser = ur.idusers
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

                                            $idgr = (int)$tampil['idgr'];
                                            $idpo = (int)$tampil['idpo'];
                                            $po_stat = (int)($tampil['po_stat'] ?? 0);

                                            $receivedate = $tampil['receivedate'] ?? '';
                                            $requester_name = $tampil['requester_name'] ?: (
                                                !empty($tampil['requester_id']) ? 'User ID: ' . $tampil['requester_id'] : '-'
                                            );
                                    ?>
                                            <tr>
                                                <td class="text-center"><?= $no++; ?></td>
                                                <td class="text-center"><?= htmlspecialchars($tampil['grnumber']); ?></td>
                                                <td><?= htmlspecialchars($requester_name); ?></td>
                                                <td><?= htmlspecialchars($tampil['nmsupplier']); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($tampil['nopo']); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($tampil['norequest']); ?></td>
                                                <td class="text-center">
                                                    <?= $receivedate ? date("d-M-y", strtotime($receivedate)) : ''; ?>
                                                </td>
                                                <td><?= htmlspecialchars($tampil['note']); ?></td>
                                                <td class="text-center"><?= htmlspecialchars($tampil['made_by']); ?></td>
                                                <td class="text-center">
                                                    <a href="view.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-success" title="Lihat">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                    <a href="edit.php?idgr=<?= $idgr; ?>" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="delete.php?idgr=<?= $idgr; ?>&idpo=<?= $idpo ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                                        title="Hapus">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>

                                                    <?php if ($po_stat === 3) : ?>
                                                        <a href="finish.php?idgr=<?= $idgr; ?>&idpo=<?= $idpo ?>"
                                                            class="btn btn-sm btn-primary"
                                                            onclick="return confirm('Dengan menutup PO ini, GR tidak bisa dilakukan lagi. Lanjutkan?')"
                                                            title="Close PO">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php else : ?>
                                                        <button class="btn btn-sm btn-secondary" disabled title="PO sudah ditutup">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.title = "Goods Receipt List";
</script>

<?php include "../footer.php"; ?>