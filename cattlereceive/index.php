<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Helpers
function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function tgl($d)
{
    return $d ? date('d/M/Y', strtotime($d)) : '-';
}
function yn($i)
{
    return $i ? 'Ya' : 'Tidak';
}

// Query list cattle_receive + ringkasan detail
$sql = "
SELECT
  r.idreceive,
  r.idpo,
  r.receipt_date,
  r.doc_no,
  r.sv_ok,
  r.skkh_ok,
  r.note,
  p.nopo,
  s.nmsupplier,
  COALESCE(COUNT(d.idreceivedetail), 0)       AS heads,
  COALESCE(SUM(d.weight), 0)                  AS total_weight
FROM cattle_receive r
JOIN pocattle   p ON p.idpo = r.idpo
JOIN supplier   s ON s.idsupplier = p.idsupplier
LEFT JOIN cattle_receive_detail d ON d.idreceive = r.idreceive
WHERE r.is_deleted = 0
GROUP BY r.idreceive, r.idpo, r.receipt_date, r.doc_no, r.sv_ok, r.skkh_ok, r.note, p.nopo, s.nmsupplier
ORDER BY r.idreceive DESC
";
$res = $conn->query($sql);
?>
<div class="content-wrapper">
    <!-- Header + Tombol Draft -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-md-3 mb-2">
                    <a href="draft.php" class="btn btn-sm btn-outline-primary btn-block">
                        <i class="fas fa-file-alt"></i> Draft
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- List -->
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
                                        <th>Receipt Date</th>
                                        <th>No PO</th>
                                        <th>Supplier</th>
                                        <th>Doc No</th>
                                        <th>SV</th>
                                        <th>SKKH</th>
                                        <th>Qty (Head)</th>
                                        <th>Total Weight (Kg)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if ($res && $res->num_rows) {
                                        while ($r = $res->fetch_assoc()) {
                                            $idreceive = (int)$r['idreceive'];
                                    ?>
                                            <tr class="text-center">
                                                <td><?= $no++; ?></td>
                                                <td><?= tgl($r['receipt_date']); ?></td>
                                                <td class="text-left"><?= e($r['nopo']); ?></td>
                                                <td class="text-left"><?= e($r['nmsupplier']); ?></td>
                                                <td class="text-left"><?= e($r['doc_no'] ?? '-'); ?></td>
                                                <td><?= yn((int)$r['sv_ok']); ?></td>
                                                <td><?= yn((int)$r['skkh_ok']); ?></td>
                                                <td><?= number_format((int)$r['heads'], 0, ',', '.'); ?></td>
                                                <td class="text-right"><?= number_format((float)$r['total_weight'], 2, ',', '.'); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="view.php?id=<?= $idreceive ?>" class="btn btn-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="edit.php?id=<?= $idreceive ?>" class="btn btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="delete.php?id=<?= $idreceive ?>" class="btn btn-danger"
                                                            onclick="return confirm('Hapus data penerimaan ini?')"
                                                            title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="10" class="text-center text-muted">Belum ada data Cattle Receive.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>

<?php include "../footer.php"; ?>