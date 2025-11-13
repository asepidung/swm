<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Helpers
function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function tgl($d)
{
    return $d ? date('d-M-Y', strtotime($d)) : '-';
}

// Ambil idreceive (utama) atau fallback dari idpo
$idreceive = null;
if (!empty($_GET['id']) && ctype_digit($_GET['id'])) {
    $idreceive = (int)$_GET['id'];
} elseif (!empty($_GET['idpo']) && ctype_digit($_GET['idpo'])) {
    $idpo = (int)$_GET['idpo'];
    $q = $conn->prepare("SELECT idreceive FROM cattle_receive WHERE idpo=? AND is_deleted=0 LIMIT 1");
    $q->bind_param("i", $idpo);
    $q->execute();
    $tmp = $q->get_result()->fetch_assoc();
    if ($tmp) $idreceive = (int)$tmp['idreceive'];
}
if (!$idreceive) {
    http_response_code(400);
    exit("Invalid receive id.");
}

// ===== Header =====
$stmtH = $conn->prepare("
  SELECT r.idreceive, r.idpo, r.receipt_date, r.doc_no, r.sv_ok, r.skkh_ok, r.note,
         r.creatime, r.createby, u.fullname AS created_by,
         p.nopo, p.podate, p.arrival_date,
         s.nmsupplier
  FROM cattle_receive r
  JOIN pocattle p   ON p.idpo = r.idpo
  JOIN supplier s   ON s.idsupplier = p.idsupplier
  LEFT JOIN users u ON u.idusers = r.createby
  WHERE r.idreceive = ? AND r.is_deleted = 0
  LIMIT 1
");
$stmtH->bind_param("i", $idreceive);
$stmtH->execute();
$rcv = $stmtH->get_result()->fetch_assoc();
if (!$rcv) {
    http_response_code(404);
    exit("Receive data not found.");
}

// ===== Detail =====
$stmtD = $conn->prepare("
  SELECT idreceivedetail, eartag, weight, class, notes
  FROM cattle_receive_detail
  WHERE idreceive = ?
  ORDER BY idreceivedetail ASC
");
$stmtD->bind_param("i", $idreceive);
$stmtD->execute();
$rows = $stmtD->get_result()->fetch_all(MYSQLI_ASSOC);

// Hitung total
$totalHead = count($rows);
$totalWeight = 0.0;
foreach ($rows as $r) $totalWeight += (float)$r['weight'];
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Cattle Receive <small class="text-muted">(<?= e($rcv['nopo']) ?>)</small></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-undo-alt"></i> Kembali</a>
                    <button class="btn btn-warning btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- Ringkasan -->
            <div class="card">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-2">PO Number</dt>
                        <dd class="col-sm-4"><?= e($rcv['nopo']) ?></dd>
                        <dt class="col-sm-2">Supplier</dt>
                        <dd class="col-sm-4"><?= e($rcv['nmsupplier']) ?></dd>

                        <dt class="col-sm-2">PO Date</dt>
                        <dd class="col-sm-4"><?= tgl($rcv['podate']) ?></dd>
                        <dt class="col-sm-2">Plan Arrival</dt>
                        <dd class="col-sm-4"><?= tgl($rcv['arrival_date']) ?></dd>

                        <dt class="col-sm-2">Receipt Date</dt>
                        <dd class="col-sm-4"><?= tgl($rcv['receipt_date']) ?></dd>
                        <dt class="col-sm-2">Doc No</dt>
                        <dd class="col-sm-4"><?= e($rcv['doc_no'] ?? '-') ?></dd>

                        <dt class="col-sm-2">SV</dt>
                        <dd class="col-sm-4"><?= $rcv['sv_ok'] ? 'Ada' : 'Tidak' ?></dd>
                        <dt class="col-sm-2">SKKH</dt>
                        <dd class="col-sm-4"><?= $rcv['skkh_ok'] ? 'Ada' : 'Tidak' ?></dd>

                        <?php if (!empty($rcv['note'])): ?>
                            <dt class="col-sm-2">Note</dt>
                            <dd class="col-sm-10"><?= nl2br(e($rcv['note'])) ?></dd>
                        <?php endif; ?>

                        <dt class="col-sm-2">Created At</dt>
                        <dd class="col-sm-4"><?= tgl(substr($rcv['creatime'], 0, 10)) . ' ' . substr($rcv['creatime'], 11, 8) ?></dd>
                        <dt class="col-sm-2">Created By</dt>
                        <dd class="col-sm-4"><?= e($rcv['created_by'] ?? '-') ?></dd>
                    </dl>
                </div>
            </div>

            <!-- Detail -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Eartag</th>
                                    <th>Class</th>
                                    <th>Weight (Kg)</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($rows)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada detail.</td>
                                    </tr>
                                    <?php else: $no = 1;
                                    foreach ($rows as $r): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= e($r['eartag']) ?></td>
                                            <td class="text-center"><?= e($r['class']) ?></td>
                                            <td class="text-right"><?= number_format((float)$r['weight'], 2, ',', '.') ?></td>
                                            <td><?= e($r['notes']) ?></td>
                                        </tr>
                                <?php endforeach;
                                endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th class="text-right"><?= number_format($totalWeight, 2, ',', '.') ?></th>
                                    <th class="text-left"><?= number_format($totalHead, 0, ',', '.') ?> head</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<?php include "../footer.php"; ?>

<style>
    @media print {

        .main-sidebar,
        .main-header,
        .main-footer,
        .btn,
        .content-header {
            display: none !important;
        }

        .content-wrapper {
            margin: 0 !important;
        }
    }
</style>