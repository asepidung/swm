<?php
// DEBUG: tampilkan error supaya mudah troubleshooting (hapus/komentari di production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_OFF);

require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "../notifcount.php";

// Ambil tahun (default = tahun sekarang) — kamu bisa set via ?year=2024
$year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));

// Optional: juga beri pilihan awal/akhir (dipakai cuma untuk info)
$awal = isset($_GET['awal']) ? $_GET['awal'] : date($year . '-01-01');
$akhir = isset($_GET['akhir']) ? $_GET['akhir'] : date('Y-m-d');

// Nama bulan untuk labels
$monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// fungsi bantu untuk inisialisasi array 12 bulan dengan 0
function initMonths()
{
    $a = [];
    for ($m = 1; $m <= 12; $m++) $a[$m] = 0.0;
    return $a;
}

// Ambil data penjualan per bulan untuk tahun tertentu
$year_cur = intval($year);
$year_prev = $year_cur - 1;

// Escape untuk safety
$awal_esc = mysqli_real_escape_string($conn, $awal);
$akhir_esc = mysqli_real_escape_string($conn, $akhir);

// Query: SUM(xamount) per month untuk tahun tertentu
function getMonthlySales($conn, $year_val)
{
    $sql = "
    SELECT MONTH(`invoice_date`) AS mon, COALESCE(SUM(`xamount`),0) AS total
    FROM `invoice`
    WHERE `is_deleted` = 0
      AND YEAR(`invoice_date`) = {$year_val}
    GROUP BY MONTH(`invoice_date`)
    ";
    $res = mysqli_query($conn, $sql);
    if ($res === false) {
        echo "<div class='m-3 alert alert-danger'><strong>SQL Error (monthly sales for {$year_val}):</strong> " . mysqli_error($conn) . "<br><code>" . htmlspecialchars($sql) . "</code></div>";
        return null;
    }
    $months = initMonths();
    while ($r = mysqli_fetch_assoc($res)) {
        $m = intval($r['mon']);
        $months[$m] = floatval($r['total']);
    }
    return $months;
}

$months_cur_raw = getMonthlySales($conn, $year_cur);
$months_prev = getMonthlySales($conn, $year_prev);

if ($months_cur_raw === null || $months_prev === null) {
    include "../footer.php";
    exit;
}

// Jika tahun yang dipilih adalah tahun sekarang, putus garis untuk bulan setelah bulan sekarang.
// Cari bulan saat ini
$currentYear = intval(date('Y'));
$currentMonth = intval(date('n')); // 1..12

// Buat array data untuk chart: nilai null untuk bulan yang belum terjadi (agar garis putus)
$data_cur = [];
for ($m = 1; $m <= 12; $m++) {
    $val = isset($months_cur_raw[$m]) ? round($months_cur_raw[$m], 2) : 0;
    if ($year_cur === $currentYear && $m > $currentMonth) {
        // masa depan: gunakan null agar Chart.js memutuskan garis
        $data_cur[] = null;
    } else {
        $data_cur[] = $val;
    }
}

// Untuk prev year, tampilkan semua bulan (jika tidak ada data -> 0)
$data_prev = [];
for ($m = 1; $m <= 12; $m++) {
    $data_prev[] = round($months_prev[$m], 2);
}

// Hitung total hanya untuk bulan yang ada data (exclude null)
$total_cur = 0;
for ($i = 0; $i < 12; $i++) {
    if ($data_cur[$i] !== null) $total_cur += $data_cur[$i];
}
$total_prev = array_sum($data_prev);

// Persentase perubahan
if ($total_prev == 0) {
    $change_pct = ($total_cur == 0) ? 0 : 100;
} else {
    $change_pct = (($total_cur - $total_prev) / $total_prev) * 100;
}

// format numbers for display (PHP)
function fmt_no_dec($v)
{
    return number_format(round($v), 0, ',', '.');
}

?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0 text-dark">Sales — Yearly Overview</h4>
                    <small class="text-muted">Per bulan (Jan–Dec). Tahun dipilih: <?= htmlspecialchars($year_cur) ?> — Perbandingan dengan <?= htmlspecialchars($year_prev) ?></small>
                </div>
                <div class="col-sm-6">
                    <form class="form-inline float-sm-right" method="GET" action="">
                        <label class="mr-2">Year</label>
                        <select name="year" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                            <?php for ($y = intval(date('Y')); $y >= intval(date('Y')) - 5; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == $year_cur ? 'selected' : ''; ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                        <input type="hidden" name="awal" value="<?= htmlspecialchars($awal) ?>">
                        <input type="hidden" name="akhir" value="<?= htmlspecialchars($akhir) ?>">
                    </form>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Sales</h3>
                                <a href="javascript:void(0);">View Report</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <p class="d-flex flex-column">
                                    <span class="text-bold text-lg">Rp <?= fmt_no_dec($total_cur) ?></span>
                                    <span>Sales in <?= $year_cur ?></span>
                                </p>
                                <p class="ml-auto d-flex flex-column text-right">
                                    <span class="<?= $change_pct >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <?php if ($change_pct >= 0): ?><i class="fas fa-arrow-up"></i><?php else: ?><i class="fas fa-arrow-down"></i><?php endif; ?>
                                        <?= number_format(abs($change_pct), 2) ?>%
                                    </span>
                                    <span class="text-muted">vs <?= $year_prev ?></span>
                                </p>
                            </div>

                            <div class="position-relative mb-4" style="min-height:260px;">
                                <canvas id="sales-year-chart" height="240"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2">
                                    <i class="fas fa-square" style="color:#007bff"></i> <?= $year_cur ?>
                                </span>
                                <span>
                                    <i class="fas fa-square" style="color:#a0a0a0"></i> <?= $year_prev ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- detail table -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h6 class="card-title">Detail Bulanan</h6>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th class="text-right">Total <?= $year_cur ?></th>
                                        <th class="text-right">Total <?= $year_prev ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < 12; $i++): ?>
                                        <tr>
                                            <td><?= $monthLabels[$i] ?></td>
                                            <td class="text-right">Rp <?= fmt_no_dec($data_cur[$i] === null ? 0 : $data_cur[$i]) ?></td>
                                            <td class="text-right">Rp <?= fmt_no_dec($data_prev[$i]) ?></td>
                                        </tr>
                                    <?php endfor; ?>
                                    <tr class="font-weight-bold">
                                        <td>Total</td>
                                        <td class="text-right">Rp <?= fmt_no_dec($total_cur) ?></td>
                                        <td class="text-right">Rp <?= fmt_no_dec($total_prev) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="../plugins/chart.js/Chart.min.js"></script>
<script>
    document.title = "Sales Yearly Chart";

    const monthLabels = <?= json_encode($monthLabels); ?>;
    const dataCurRaw = <?= json_encode($data_cur); ?>; // may contain nulls (future months)
    const dataPrev = <?= json_encode($data_prev); ?>;

    // robust formatter using Intl.NumberFormat
    // gunakan 'en-US' untuk comma separators (1,234,567). Ganti ke 'id-ID' jika mau titik sebagai thousand separator.
    const currencyFormatter = new Intl.NumberFormat('en-US', {
        maximumFractionDigits: 0
    });

    // helper to safe-format any value (may be null/undefined/string)
    function formatNumberForChart(v) {
        if (v === null || v === undefined) return '0';
        const n = Number(v);
        if (isNaN(n)) return v;
        return currencyFormatter.format(Math.round(n));
    }

    (function() {
        const ctx = document.getElementById('sales-year-chart').getContext('2d');

        if (window._salesYearChart) {
            window._salesYearChart.destroy();
        }

        window._salesYearChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                        label: '<?= $year_cur ?>',
                        data: dataCurRaw,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0,123,255,0.12)',
                        pointRadius: 4,
                        pointBackgroundColor: '#007bff',
                        tension: 0.3,
                        spanGaps: false, // DON'T draw line across null gaps
                        fill: true
                    },
                    {
                        label: '<?= $year_prev ?>',
                        data: dataPrev,
                        borderColor: '#9aa0a6',
                        backgroundColor: 'rgba(160,160,160,0.08)',
                        pointRadius: 3,
                        pointBackgroundColor: '#9aa0a6',
                        tension: 0.3,
                        spanGaps: true,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                stacked: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                // prefer parsed.y; fallback to raw
                                let v = null;
                                if (context.parsed && typeof context.parsed.y !== 'undefined') v = context.parsed.y;
                                else if (context.raw !== undefined) v = context.raw;
                                if (v === null || v === undefined) return context.dataset.label + ': -';
                                return context.dataset.label + ': Rp ' + formatNumberForChart(v);
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + formatNumberForChart(value);
                            }
                        }
                    }
                }
            }
        });
    })();
</script>

<?php
include "../footer.php";
?>