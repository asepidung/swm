<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$idrepack = intval($_GET['id'] ?? 0);
if ($idrepack <= 0) die("Jalankan dari modul Repack yang valid.");

// Ambil nomor Repack
$qrep = $conn->query("SELECT norepack FROM repack WHERE idrepack = $idrepack LIMIT 1");
$drep = $qrep->fetch_assoc();
$norepack = $drep['norepack'] ?? "RPC-???";

// ==========================================================
// Ambil total pemakaian per material dari raw_usage
// ==========================================================
$sql = "
  SELECT 
      ru.idrawmate,
      COALESCE(rm.nmrawmate, CONCAT('ID#', ru.idrawmate)) AS nmrawmate,
      SUM(ru.qty) AS total_qty
  FROM raw_usage ru
  LEFT JOIN rawmate rm ON rm.idrawmate = ru.idrawmate
  WHERE ru.sumber = 'REPACK'
    AND ru.idsumber = ?
  GROUP BY ru.idrawmate
  ORDER BY nmrawmate ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idrepack);
$stmt->execute();
$res = $stmt->get_result();

$rows = [];
$grand = 0;
while ($r = $res->fetch_assoc()) {
    $rows[] = $r;
    $grand += (float)$r['total_qty'];
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h4><i class="fas fa-clipboard-list"></i> Laporan Pemakaian Bahan - <?= htmlspecialchars($norepack) ?></h4>
                    <div class="small text-muted">Ringkasan per material untuk proses REPACK ini.</div>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="index.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-undo-alt"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-dark shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Pemakaian Material</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="usageReport" class="table table-bordered table-striped table-sm">
                            <thead class="text-center">
                                <tr>
                                    <th style="width:48px">#</th>
                                    <th>Material</th>
                                    <th style="width:140px">Qty Terpakai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($rows as $r): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($r['nmrawmate']) ?></td>
                                        <td class="text-right"><?= number_format((float)$r['total_qty'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">TOTAL PEMAKAIAN</th>
                                    <th class="text-right"><?= number_format($grand, 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.title = "Laporan Raw Usage <?= htmlspecialchars($norepack) ?>";
    $(function() {
        $("#usageReport").DataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            ordering: false,
            paging: false,
            searching: true,
            info: false,
            buttons: ["copy", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#usageReport_wrapper .col-md-6:eq(0)');
    });
</script>

<?php include "../footnote.php"; ?>
<?php include "../footer.php"; ?>