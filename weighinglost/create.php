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
    return $d ? date('d/M/Y', strtotime($d)) : '-';
}

// =======================================
// MODE SAVE (POST)
// =======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idweigh   = isset($_POST['idweigh'])   ? (int)$_POST['idweigh']   : 0;
    $idreceive = isset($_POST['idreceive']) ? (int)$_POST['idreceive'] : 0;
    $loss_date = $_POST['loss_date'] ?? date('Y-m-d');
    $note      = $_POST['note'] ?? '';
    $iduser    = $_SESSION['idusers'] ?? 0;

    if ($idweigh <= 0 || $idreceive <= 0) {
        die("Data tidak valid (idweigh/idreceive).");
    }

    // Ambil arrays detail
    $idreceivedetail = $_POST['idreceivedetail'] ?? [];
    $idweighdetail   = $_POST['idweighdetail']   ?? [];
    $eartagArr       = $_POST['eartag']          ?? [];
    $classArr        = $_POST['class']           ?? [];
    $recvArr         = $_POST['receive_weight']  ?? [];
    $actArr          = $_POST['actual_weight']   ?? [];
    $priceArr        = $_POST['price_perkg']     ?? [];

    if (count($idreceivedetail) === 0) {
        die("Tidak ada data detail yang dikirim.");
    }

    // Siapkan data baris + hitung total
    $rows = [];
    $total_receive_weight = 0.0;
    $total_actual_weight  = 0.0;
    $total_loss_weight    = 0.0;
    $total_loss_cost      = 0.0;

    $count = count($idreceivedetail);
    for ($i = 0; $i < $count; $i++) {
        $idr  = (int)$idreceivedetail[$i];
        $idw  = (int)$idweighdetail[$i];
        $ear  = trim($eartagArr[$i] ?? '');
        $cls  = trim($classArr[$i] ?? '');
        $recv = (float)($recvArr[$i] ?? 0);
        $act  = (float)($actArr[$i] ?? 0);
        $loss = $recv - $act;

        // harga bisa kosong
        $priceRaw = trim($priceArr[$i] ?? '');
        $price = ($priceRaw === '') ? null : (float)$priceRaw;

        $lossCost = null;
        if ($price !== null) {
            $lossCost = $loss * $price;
            $total_loss_cost += $lossCost;
        }

        $total_receive_weight += $recv;
        $total_actual_weight  += $act;
        $total_loss_weight    += $loss;

        $rows[] = [
            'idreceivedetail' => $idr,
            'idweighdetail'   => $idw,
            'eartag'          => $ear,
            'class'           => $cls,
            'receive_weight'  => $recv,
            'actual_weight'   => $act,
            'loss_weight'     => $loss,
            'price_perkg'     => $price,
            'loss_cost'       => $lossCost
        ];
    }

    // Buat nomor dokumen sederhana (silakan ganti dengan generator versimu)
    $loss_no = 'LRC-' . date('ymdHis');

    // Simpan ke DB
    $conn->begin_transaction();
    try {
        // Insert header
        $sqlHeader = "
            INSERT INTO cattle_loss_receive
                (idreceive, idweigh, loss_no, loss_date, note,
                 total_receive_weight, total_actual_weight, total_loss_weight, total_loss_cost,
                 createby)
            VALUES
                (?,?,?,?,?,?,?,?,?,?)
        ";
        $stmtH = $conn->prepare($sqlHeader);
        $stmtH->bind_param(
            'iisssddddi',
            $idreceive,
            $idweigh,
            $loss_no,
            $loss_date,
            $note,
            $total_receive_weight,
            $total_actual_weight,
            $total_loss_weight,
            $total_loss_cost,
            $iduser
        );
        $stmtH->execute();
        $idloss = $stmtH->insert_id;
        $stmtH->close();

        // Insert detail
        $sqlDet = "
            INSERT INTO cattle_loss_receive_detail
                (idloss, idreceivedetail, idweighdetail, eartag, cattle_class,
                 receive_weight, actual_weight, loss_weight, price_perkg, loss_cost,
                 notes, createby)
            VALUES
                (?,?,?,?,?,?,?,?,?,?,?,?)
        ";
        $stmtD = $conn->prepare($sqlDet);

        foreach ($rows as $row) {
            $price  = $row['price_perkg'];
            $lcost  = $row['loss_cost'];

            // null handling untuk bind_param
            $priceParam = $price;
            $lcostParam = $lcost;
            $notes = ''; // belum ada catatan per ekor

            $stmtD->bind_param(
                'iiissdddddsi',
                $idloss,
                $row['idreceivedetail'],
                $row['idweighdetail'],
                $row['eartag'],
                $row['class'],
                $row['receive_weight'],
                $row['actual_weight'],
                $row['loss_weight'],
                $priceParam,
                $lcostParam,
                $notes,
                $iduser
            );
            $stmtD->execute();
        }
        $stmtD->close();

        $conn->commit();

        header("Location: view.php?id=" . $idloss);
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        die("Gagal menyimpan data loss: " . e($e->getMessage()));
    }
}

// =======================================
// MODE FORM (GET)
// =======================================
$idweigh = isset($_GET['idweigh']) ? (int)$_GET['idweigh'] : 0;
if ($idweigh <= 0) {
    die("Parameter idweigh tidak valid.");
}

// Ambil header weighing + receive + PO
$sqlHead = "
SELECT
    w.idweigh,
    w.weigh_no,
    w.weigh_date,
    w.idreceive,
    r.receipt_date,
    r.doc_no,
    p.nopo,
    s.nmsupplier
FROM weight_cattle w
JOIN cattle_receive r
      ON r.idreceive = w.idreceive
     AND r.is_deleted = 0
JOIN pocattle p
      ON p.idpo = r.idpo
     AND p.is_deleted = 0
JOIN supplier s
      ON s.idsupplier = p.idsupplier
WHERE w.idweigh = ?
  AND w.is_deleted = 0
LIMIT 1
";
$stmt = $conn->prepare($sqlHead);
$stmt->bind_param('i', $idweigh);
$stmt->execute();
$headRes = $stmt->get_result();
$header  = $headRes->fetch_assoc();
$stmt->close();

if (!$header) {
    die("Data weighing tidak ditemukan.");
}
$idreceive = (int)$header['idreceive'];

// Ambil detail per ekor: receive vs timbang + harga default dari PO detail
$sqlDet = "
SELECT
    wd.idweighdetail,
    rd.idreceivedetail,
    rd.eartag,
    rd.class,
    rd.weight AS receive_weight,
    wd.weight AS actual_weight,
    (rd.weight - wd.weight) AS loss_weight,
    pd.price AS default_price
FROM weight_cattle_detail wd
JOIN cattle_receive_detail rd
      ON rd.idreceivedetail = wd.idreceivedetail
JOIN cattle_receive r
      ON r.idreceive = rd.idreceive
JOIN pocattledetail pd
      ON pd.idpo = r.idpo
     AND pd.class = rd.class
     AND pd.is_deleted = 0
WHERE wd.idweigh = ?
ORDER BY rd.eartag
";
$stmt2 = $conn->prepare($sqlDet);
$stmt2->bind_param('i', $idweigh);
$stmt2->execute();
$detailRes = $stmt2->get_result();
$details   = [];
while ($row = $detailRes->fetch_assoc()) {
    $details[] = $row;
}
$stmt2->close();

if (empty($details)) {
    die("Tidak ada detail timbang untuk weighing ini.");
}

// Hitung ringkasan awal untuk tampilan
$totalReceive = 0;
$totalActual  = 0;
$totalLoss    = 0;
foreach ($details as $d) {
    $totalReceive += (float)$d['receive_weight'];
    $totalActual  += (float)$d['actual_weight'];
    $totalLoss    += (float)$d['loss_weight'];
}
?>

<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cattle Weight Loss (Receiving) - Create</h1>
                    <p class="mb-0 text-muted">
                        Hitung selisih berat kedatangan vs timbang ulang dan nilai loss-nya.
                    </p>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="draft.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-undo-alt"></i> Kembali ke Draft
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main -->
    <section class="content">
        <div class="container-fluid">
            <form method="post" action="store.php">

                <input type="hidden" name="idweigh" value="<?= (int)$header['idweigh']; ?>">
                <input type="hidden" name="idreceive" value="<?= (int)$header['idreceive']; ?>">

                <!-- CARD HEADER -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Header</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Weigh No</label>
                                <input type="text" class="form-control" value="<?= e($header['weigh_no']); ?>" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Weigh Date</label>
                                <input type="text" class="form-control" value="<?= tgl($header['weigh_date']); ?>" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Receive Date</label>
                                <input type="text" class="form-control" value="<?= tgl($header['receipt_date']); ?>" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Doc Receive</label>
                                <input type="text" class="form-control" value="<?= e($header['doc_no']); ?>" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>No PO</label>
                                <input type="text" class="form-control" value="<?= e($header['nopo']); ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Supplier</label>
                                <input type="text" class="form-control" value="<?= e($header['nmsupplier']); ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Loss Date</label>
                                <input type="date" name="loss_date" class="form-control"
                                    value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" class="form-control" rows="2"
                                placeholder="Catatan (opsional)"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Total Receive Weight (Kg)</label>
                                <input type="text" class="form-control"
                                    value="<?= number_format($totalReceive, 2, ',', '.'); ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Total Actual Weight (Kg)</label>
                                <input type="text" class="form-control"
                                    value="<?= number_format($totalActual, 2, ',', '.'); ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Total Loss Weight (Kg)</label>
                                <input type="text" class="form-control"
                                    value="<?= number_format($totalLoss, 2, ',', '.'); ?>" readonly>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- CARD DETAIL -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Detail per Ekor</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Eartag</th>
                                    <th>Class</th>
                                    <th>Receive Wt (Kg)</th>
                                    <th>Actual Wt (Kg)</th>
                                    <th>Loss Wt (Kg)</th>
                                    <th>Price / Kg</th>
                                    <th>Loss Cost (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($details as $d):
                                    $recv = (float)$d['receive_weight'];
                                    $act  = (float)$d['actual_weight'];
                                    $loss = (float)$d['loss_weight'];
                                    $price = $d['default_price'] !== null ? (float)$d['default_price'] : null;
                                    $lossCost = ($price !== null) ? $loss * $price : 0;
                                ?>
                                    <tr class="text-center">
                                        <td><?= $no; ?></td>

                                        <td class="text-left">
                                            <?= e($d['eartag']); ?>
                                            <input type="hidden" name="eartag[]" value="<?= e($d['eartag']); ?>">
                                            <input type="hidden" name="idreceivedetail[]" value="<?= (int)$d['idreceivedetail']; ?>">
                                            <input type="hidden" name="idweighdetail[]" value="<?= (int)$d['idweighdetail']; ?>">
                                        </td>

                                        <td>
                                            <?= e($d['class']); ?>
                                            <input type="hidden" name="class[]" value="<?= e($d['class']); ?>">
                                        </td>

                                        <td class="text-right">
                                            <?= number_format($recv, 2, ',', '.'); ?>
                                            <input type="hidden" name="receive_weight[]" value="<?= $recv; ?>">
                                        </td>

                                        <td class="text-right">
                                            <?= number_format($act, 2, ',', '.'); ?>
                                            <input type="hidden" name="actual_weight[]" value="<?= $act; ?>">
                                        </td>

                                        <td class="text-right">
                                            <?= number_format($loss, 2, ',', '.'); ?>
                                        </td>

                                        <td>
                                            <input type="number"
                                                step="1"
                                                min="0"
                                                name="price_perkg[]"
                                                class="form-control form-control-sm text-right price-input"
                                                value="<?= $price !== null ? $price : ''; ?>"
                                                data-loss="<?= $loss; ?>"
                                                data-row="<?= $no; ?>">
                                        </td>

                                        <td class="text-right">
                                            <span id="loss_cost_row_<?= $no; ?>">
                                                <?= $price !== null ? number_format($lossCost, 0, ',', '.') : '-'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php
                                    $no++;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Loss
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </section>
</div>

<script>
    // optional: update tampilan loss cost saat harga diubah
    document.querySelectorAll('.price-input').forEach(function(el) {
        el.addEventListener('input', function() {
            var loss = parseFloat(this.getAttribute('data-loss')) || 0;
            var row = this.getAttribute('data-row');
            var price = parseFloat(this.value) || 0;
            var cost = loss * price;

            var span = document.getElementById('loss_cost_row_' + row);
            if (!span) return;

            if (this.value === '') {
                span.textContent = '-';
            } else {
                span.textContent = new Intl.NumberFormat('id-ID', {
                    maximumFractionDigits: 0
                }).format(cost);
            }
        });
    });
</script>

<?php include "../footer.php"; ?>