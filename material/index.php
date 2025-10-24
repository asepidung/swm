<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

/* Helper: bentuk URL laporan + param ret untuk balik ke halaman ini */
function usageViewUrl(string $sumber, int $idsumber): string
{
    // pastikan selalu kembali ke halaman summary material
    $ret = urlencode('../material/index.php');
    if ($sumber === 'REPACK') {
        return "../repack/laporan_rawusage_repack.php?id={$idsumber}&ret={$ret}";
    } elseif ($sumber === 'LAINNYA') {
        return "laporan_rawusage_other.php?id={$idsumber}&ret={$ret}";
    } else { // BONING
        return "../boning/laporan_rawusage.php?id={$idsumber}&ret={$ret}";
    }
}

/* Ringkasan gabungan dari raw_usage */
$sql = "
  SELECT 
    ru.sumber,
    ru.idsumber,
    COALESCE(
      CASE WHEN ru.sumber='REPACK'  THEN r.tglrepack  END,
      CASE WHEN ru.sumber='BONING'  THEN b.tglboning  END,
      CASE WHEN ru.sumber='LAINNYA' THEN u.tgl        END
    ) AS tgl_proses,
    COALESCE(
      CASE WHEN ru.sumber='REPACK'  THEN r.norepack   END,
      CASE WHEN ru.sumber='BONING'  THEN CONCAT('BN', LPAD(b.idboning,4,'0')) END,
      CASE WHEN ru.sumber='LAINNYA' THEN u.noother    END
    ) AS notrans,
    SUM(ru.qty) AS total_qty,
    COUNT(DISTINCT ru.idrawmate) AS jml_material
  FROM raw_usage ru
  LEFT JOIN repack      r ON ru.sumber='REPACK'  AND r.idrepack = ru.idsumber
  LEFT JOIN boning      b ON ru.sumber='BONING'  AND b.idboning = ru.idsumber
  LEFT JOIN usage_other u ON ru.sumber='LAINNYA' AND u.idother  = ru.idsumber
  GROUP BY ru.sumber, ru.idsumber, tgl_proses, notrans
  ORDER BY tgl_proses DESC, ru.sumber, ru.idsumber DESC
";
$res = mysqli_query($conn, $sql);

$msg = $_GET['msg'] ?? '';
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h4><i class="fas fa-clipboard-list"></i> Ringkasan Pemakaian Material (Semua Sumber)</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="other_usage_new.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> Tambah (Pengeluaran Lainnya)
                    </a>
                    <a href="javascript:history.back();" class="btn btn-secondary btn-sm">
                        <i class="fas fa-undo-alt"></i> Kembali
                    </a>
                </div>
            </div>
            <?php if ($msg): ?>
                <div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-dark shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Tabel Ringkasan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="summaryAll" class="table table-bordered table-striped table-sm">
                            <thead class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>ID Transaksi</th>
                                    <th>Proses</th>
                                    <th>Jml Material</th>
                                    <th>Total Qty</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                while ($r = mysqli_fetch_assoc($res)):
                                    $sumber   = $r['sumber'];
                                    $idsumber = (int)$r['idsumber'];
                                    $viewUrl  = usageViewUrl($sumber, $idsumber);
                                ?>
                                    <tr class="align-middle">
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center">
                                            <?= htmlspecialchars(date('d-M-y', strtotime($r['tgl_proses'] ?? date('Y-m-d')))) ?>
                                        </td>
                                        <td><?= htmlspecialchars($r['notrans'] ?? "{$sumber}-{$idsumber}") ?></td>
                                        <td class="text-center">
                                            <?php if ($sumber === 'BONING'): ?>
                                                <span class="badge badge-info">BONING</span>
                                            <?php elseif ($sumber === 'REPACK'): ?>
                                                <span class="badge badge-success">REPACK</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">LAINNYA</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= (int)$r['jml_material'] ?></td>
                                        <td class="text-right"><?= number_format((float)$r['total_qty'], 2) ?></td>
                                        <td class="text-center">
                                            <a href="<?= htmlspecialchars($viewUrl) ?>" class="btn btn-outline-info btn-sm" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <?php
                                $qGrand = mysqli_query($conn, "SELECT COALESCE(SUM(qty),0) AS gqty FROM raw_usage");
                                $grand  = mysqli_fetch_assoc($qGrand)['gqty'] ?? 0;
                                ?>
                                <tr>
                                    <th colspan="5" class="text-right">GRAND TOTAL QTY</th>
                                    <th class="text-right"><?= number_format((float)$grand, 2) ?></th>
                                    <th></th>
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
    document.title = "Ringkasan Pemakaian Material (All)";
    $(function() {
        $("#summaryAll").DataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            ordering: false,
            paging: true,
            pageLength: 25,
            searching: true,
            info: true,
            buttons: ["copy", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#summaryAll_wrapper .col-md-6:eq(0)');
    });
</script>

<?php include "../footnote.php"; ?>
<?php include "../footer.php"; ?>