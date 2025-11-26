<?php
// DEBUG: tampilkan error supaya mudah troubleshooting (hapus/komentari di production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Matikan mysqli exceptions agar kita menampilkan error manual
mysqli_report(MYSQLI_REPORT_OFF);

require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "../notifcount.php";

// pesan (opsional)
$message = isset($_GET['message']) ? $_GET['message'] : "";

// default tanggal: awal = hari pertama bulan ini, akhir = max(deliverydate)
$awal = isset($_GET['awal']) ? $_GET['awal'] : date('Y-m-01');
$queryMaxDate = "SELECT MAX(`deliverydate`) AS max_date FROM `do`";
$resultMaxDate = mysqli_query($conn, $queryMaxDate);
if ($resultMaxDate === false) {
    echo "<div class='m-3 alert alert-danger'><strong>SQL Error (max date):</strong> " . mysqli_error($conn) . "<br><code>" . htmlspecialchars($queryMaxDate) . "</code></div>";
    include "../footer.php";
    exit;
}
$rowMaxDate = mysqli_fetch_assoc($resultMaxDate);
$maxDate = $rowMaxDate['max_date'] ?: date('Y-m-d');
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : $maxDate;

// top N (sanitasi)
$topN = isset($_GET['top']) ? intval($_GET['top']) : 10;
if ($topN <= 0) $topN = 10;

// daftar status yang dianggap "batal/reject"
$excluded_status_list = "'reject','rejected','cancel','void','rejected_by_customer'";

// Escape tanggal untuk dimasukkan ke query
$awal_esc = mysqli_real_escape_string($conn, $awal);
$akhir_esc = mysqli_real_escape_string($conn, $akhir);

/*
  Ambil daftar cuts (kategori) yang punya transaksi DO di rentang tanggal.
  PERBAIKAN: gunakan GROUP BY (bukan DISTINCT) agar ORDER BY expression aman.
  Urutan khusus: PRIME CUT, SECONDARY CUT, BONES, lalu urut berdasarkan idcut.
*/
$sqlCuts = "
SELECT `c`.`idcut`,
       COALESCE(`c`.`nmcut`, CONCAT('Cut ', `c`.`idcut`)) AS `nmcut`
FROM `cuts` `c`
JOIN `barang` `b` ON `b`.`idcut` = `c`.`idcut`
JOIN `dodetail` `dd` ON `dd`.`idbarang` = `b`.`idbarang`
JOIN `do` `d` ON `d`.`iddo` = `dd`.`iddo`
WHERE `d`.`is_deleted` = 0
  AND (`d`.`deliverydate` BETWEEN '{$awal_esc}' AND '{$akhir_esc}')
  AND (`d`.`status` IS NULL OR LOWER(`d`.`status`) NOT IN ({$excluded_status_list}))
GROUP BY `c`.`idcut`, `c`.`nmcut`
ORDER BY
  CASE
    WHEN UPPER(COALESCE(`c`.`nmcut`, '')) = 'PRIME CUT' THEN 1
    WHEN UPPER(COALESCE(`c`.`nmcut`, '')) = 'SECONDARY CUT' THEN 2
    WHEN UPPER(COALESCE(`c`.`nmcut`, '')) = 'BONES' THEN 3
    ELSE 4
  END,
  `c`.`idcut`
";

$resCuts = mysqli_query($conn, $sqlCuts);
if ($resCuts === false) {
    echo "<div class='m-3 alert alert-danger'><strong>SQL Error (cuts):</strong> " . mysqli_error($conn) . "<br><code>" . htmlspecialchars($sqlCuts) . "</code></div>";
    include "../footer.php";
    exit;
}

$cuts = [];
while ($r = mysqli_fetch_assoc($resCuts)) {
    $cuts[] = ['idcut' => $r['idcut'], 'nmcut' => $r['nmcut']];
}

/*
  Untuk tiap cut, ambil top N item berdasarkan COUNT DISTINCT (dd.iddo) dan SUM weight (total qty).
*/
$charts = []; // data untuk JS

foreach ($cuts as $c) {
    $idcut = intval($c['idcut']);
    $limit = intval($topN);

    $sqlTop = "
    SELECT `b`.`idbarang`, `b`.`nmbarang`, COUNT(DISTINCT `dd`.`iddo`) AS freq_do, COALESCE(SUM(`dd`.`weight`),0) AS total_qty
    FROM `dodetail` `dd`
    JOIN `do` `d` ON `d`.`iddo` = `dd`.`iddo`
    JOIN `barang` `b` ON `b`.`idbarang` = `dd`.`idbarang`
    WHERE `d`.`is_deleted` = 0
      AND (`d`.`deliverydate` BETWEEN '{$awal_esc}' AND '{$akhir_esc}')
      AND (`d`.`status` IS NULL OR LOWER(`d`.`status`) NOT IN ({$excluded_status_list}))
      AND `b`.`idcut` = {$idcut}
    GROUP BY `b`.`idbarang`, `b`.`nmbarang`
    ORDER BY freq_do DESC
    LIMIT {$limit}
    ";

    $resTop = mysqli_query($conn, $sqlTop);
    if ($resTop === false) {
        echo "<div class='m-3 alert alert-danger'><strong>SQL Error (top for idcut {$idcut}):</strong> " . mysqli_error($conn) . "<br><code>" . htmlspecialchars($sqlTop) . "</code></div>";
        include "../footer.php";
        exit;
    }

    $labels = [];
    $data = [];
    $qtys = [];

    while ($row = mysqli_fetch_assoc($resTop)) {
        $labels[] = $row['nmbarang'];
        $data[]   = (int)$row['freq_do'];
        $qtys[]   = (float)$row['total_qty'];
    }

    $charts[$idcut] = [
        'label' => $c['nmcut'],
        'labels' => $labels,
        'data' => $data,
        'qty'  => $qtys
    ];
}

// Determine default active tab: prefer PRIME CUT if exists, else use first cut
$defaultActiveIdcut = null;
foreach ($cuts as $c) {
    if (strtoupper($c['nmcut']) === 'PRIME CUT') {
        $defaultActiveIdcut = $c['idcut'];
        break;
    }
}
if ($defaultActiveIdcut === null && count($cuts) > 0) {
    $defaultActiveIdcut = $cuts[0]['idcut'];
}

?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0 text-dark">Produk Fast Moving</h4>
                    <small class="text-muted">Berdasarkan frekuensi pemesanan</small>
                </div>
            </div>

            <?php if (!empty($message)) : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>⚠️ Peringatan:</strong> <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-2">
                    <form id="filterForm" method="GET" action="">
                        <input type="date" class="form-control form-control-sm" name="awal" value="<?= htmlspecialchars($awal); ?>">
                </div>
                <div class="col-2">
                    <input type="date" class="form-control form-control-sm" name="akhir" value="<?= htmlspecialchars($akhir); ?>">
                </div>
                <div class="col-2">
                    <select class="form-control form-control-sm" name="top">
                        <?php for ($i = 5; $i <= 50; $i += 5): ?>
                            <option value="<?= $i; ?>" <?= $i == $topN ? 'selected' : ''; ?>>Top <?= $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-sm btn-primary" name="search"><i class="fas fa-search"></i> Apply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Hasil</h5>
                            <div class="card-tools">
                                <small class="text-muted">Periode: <?= htmlspecialchars($awal) ?> — <?= htmlspecialchars($akhir) ?> · Top <?= $topN ?></small>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (count($cuts) === 0): ?>
                                <div class="alert alert-info">Tidak ada data DO pada rentang tanggal ini.</div>
                            <?php else: ?>
                                <ul class="nav nav-tabs" id="cutTabs" role="tablist">
                                    <?php foreach ($cuts as $c):
                                        $active = ($c['idcut'] == $defaultActiveIdcut) ? 'active' : '';
                                    ?>
                                        <li class="nav-item">
                                            <a class="nav-link <?= $active ?>" id="cut-<?= $c['idcut'] ?>-tab" data-toggle="tab" href="#cut-<?= $c['idcut'] ?>" role="tab" aria-controls="cut-<?= $c['idcut'] ?>">
                                                <?= htmlspecialchars($c['nmcut'] ?: 'Cut ' . $c['idcut']) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <div class="tab-content mt-3" id="cutTabsContent">
                                    <?php foreach ($cuts as $c):
                                        $paneActive = ($c['idcut'] == $defaultActiveIdcut) ? 'active show' : '';
                                    ?>
                                        <div class="tab-pane fade <?= $paneActive ?>" id="cut-<?= $c['idcut'] ?>" role="tabpanel">
                                            <div class="card card-outline card-secondary">
                                                <div class="card-header">
                                                    <h6 class="card-title mb-0"><?= htmlspecialchars($c['nmcut']) ?></h6>
                                                </div>
                                                <div class="card-body">
                                                    <?php
                                                    $ch = $charts[$c['idcut']] ?? null;
                                                    if (!$ch || count($ch['labels']) === 0) {
                                                        echo '<div class="alert alert-light">Tidak ada item di kategori ini untuk periode & filter saat ini.</div>';
                                                    } else {
                                                        echo '<div class="position-relative mb-4" style="min-height:320px">';
                                                        echo '<canvas id="chart-' . $c['idcut'] . '" style="width:100%;height:320px;"></canvas>';
                                                        echo '</div>';
                                                        echo '<div class="table-responsive">';
                                                        echo '<table class="table table-sm table-bordered">';
                                                        echo '<thead><tr><th>#</th><th>Nama Item</th><th class="text-right">Jumlah Pemesanan</th><th class="text-right">Total Qty</th></tr></thead><tbody>';
                                                        for ($k = 0; $k < count($ch['labels']); $k++) {
                                                            $no = $k + 1;
                                                            $name = htmlspecialchars($ch['labels'][$k]);
                                                            $f = intval($ch['data'][$k]);
                                                            $q = number_format($ch['qty'][$k], 2);
                                                            echo "<tr><td class='text-center'>{$no}</td><td>{$name}</td><td class='text-right'>{$f}</td><td class='text-right'>{$q}</td></tr>";
                                                        }
                                                        echo '</tbody></table></div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Pastikan Chart.js sudah include (path harus sesuai) -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<script>
    document.title = "Produk Fast Moving";
    const chartsData = <?= json_encode($charts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?> || {};

    function genColors(n) {
        const out = [];
        for (let i = 0; i < n; i++) {
            const h = Math.floor((i * 47) % 360);
            out.push(`hsl(${h} 60% 55% / 0.95)`);
        }
        return out;
    }
    const chartInstances = {};

    function renderChartForIdcut(idcut) {
        const cfg = chartsData[idcut];
        if (!cfg) return;
        const canvasId = 'chart-' + idcut;
        const el = document.getElementById(canvasId);
        if (!el) return;
        const ctx = el.getContext('2d');
        if (chartInstances[canvasId]) {
            chartInstances[canvasId].destroy();
            delete chartInstances[canvasId];
        }
        const labels = cfg.labels || [];
        const dataFreq = (cfg.data || []).map(v => Number(v) || 0);
        const dataQty = (cfg.qty || []).map(v => Number(v) || 0);
        const colors = genColors(labels.length);
        chartInstances[canvasId] = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: dataFreq,
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    y: {
                        ticks: {
                            autoSkip: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            boxWidth: 12
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const idx = context.dataIndex;
                                const f = dataFreq[idx] || 0;
                                const q = dataQty[idx] || 0;
                                return `${context.dataset.label}: ${f} pemesanan — Total Qty: ${q.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}`;
                            }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // render active tab charts
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            if (pane.classList.contains('active') || pane.classList.contains('show')) {
                const id = pane.id.replace('cut-', '');
                renderChartForIdcut(id);
            }
        });

        // on tab shown, render chart for that tab
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            const href = $(e.target).attr('href');
            if (!href) return;
            const idcut = href.replace('#cut-', '');
            renderChartForIdcut(idcut);
        });
    });
</script>

<?php
include "../footer.php";
?>