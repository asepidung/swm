<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

if (!function_exists('e')) {
    function e($s)
    {
        return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('tgl')) {
    function tgl($d)
    {
        return $d ? date('Y-m-d', strtotime($d)) : '';
    }
}

// ================================
// Validasi & ambil idweight
// ================================
$idweight = isset($_GET['idweight']) ? (int)$_GET['idweight'] : 0;
if ($idweight <= 0) {
    die("ID weight tidak valid.");
}

// ================================
// Ambil HEADER timbang
// ================================
$stmtH = $conn->prepare("
    SELECT 
        w.idweigh,
        w.weigh_no,
        w.weigh_date,
        w.note        AS weigh_note,
        r.receipt_date,
        p.nopo,
        s.idsupplier,
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
    WHERE w.idweigh = ? AND w.is_deleted = 0
    LIMIT 1
");
$stmtH->bind_param("i", $idweight);
$stmtH->execute();
$header = $stmtH->get_result()->fetch_assoc();
$stmtH->close();

if (!$header) {
    die("Data timbang tidak ditemukan atau sudah dihapus.");
}

// ================================
// Ambil DETAIL: hanya sapi yang
// BELUM punya carcas aktif
// ================================
$stmtD = $conn->prepare("
    SELECT 
        d.idweighdetail,
        d.eartag,
        crd.weight AS receive_weight,
        crd.class  AS cattle_class
    FROM weight_cattle_detail d
    JOIN cattle_receive_detail crd
          ON crd.idreceivedetail = d.idreceivedetail
    LEFT JOIN carcasedetail cd
          ON cd.idweightdetail = d.idweighdetail
    LEFT JOIN carcase c
          ON c.idcarcase = cd.idcarcase
         AND c.is_deleted = 0
    WHERE d.idweigh = ?
      AND c.idcarcase IS NULL   -- hanya sapi yang BELUM punya carcas aktif
    ORDER BY d.eartag
");

$stmtD->bind_param("i", $idweight);
$stmtD->execute();
$resD = $stmtD->get_result();

$details = [];
while ($row = $resD->fetch_assoc()) {
    $details[] = $row;
}
$stmtD->close();

if (empty($details)) {
    die("Tidak ada sapi yang tersisa untuk diproses carcas pada batch ini.");
}

// opsi class yang diizinkan
$breedOptions = ['STEER', 'HEIFER', 'COW', 'BULL'];
?>

<div class="content-wrapper">
    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-md-6">
                    <h1 class="m-0">Input Carcas</h1>
                    <small class="text-muted">
                        Berdasarkan hasil timbang: <?= e($header['weigh_no']); ?>
                    </small>
                </div>
                <div class="col-12 col-md-6 text-md-right mt-2 mt-md-0">
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

            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">

                            <!-- Info Header Timbang -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>No Timbang</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="<?= e($header['weigh_no']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tgl Timbang</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="<?= e(date('d-M-Y', strtotime($header['weigh_date']))); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="<?= e($header['nmsupplier']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>No PO</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="<?= e($header['nopo']); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Carcas -->
                            <form action="store.php" method="post">
                                <input type="hidden" name="idweight" value="<?= (int)$header['idweigh']; ?>">
                                <input type="hidden" name="idsupplier" value="<?= (int)$header['idsupplier']; ?>">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Killing</label>
                                            <input type="date" name="killdate" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Catatan</label>
                                            <input type="text" name="note" class="form-control form-control-sm"
                                                placeholder="Catatan tambahan (opsional)">
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width:5%;">No</th>
                                                <th style="width:18%;">Eartag</th>
                                                <th style="width:18%;">Class</th>
                                                <th>Carcase A (Kg)</th>
                                                <th>Carcase B (Kg)</th>
                                                <th>Hides (Kg)</th>
                                                <th>Tail (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($details as $d):
                                                $idwd     = (int)$d['idweighdetail'];
                                                $etag     = $d['eartag'];
                                                // sekarang pakai BERAT PENERIMAAN (bila ada) sebagai default live_weight
                                                $wReceive = $d['receive_weight'] ?? '';
                                                $class    = strtoupper(trim($d['cattle_class'] ?? ''));
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++; ?></td>
                                                    <td class="text-center">
                                                        <?= e($etag); ?>
                                                        <input type="hidden" name="idweighdetail[]" value="<?= $idwd; ?>">
                                                        <!-- perbaikan: kirim live_weight sesuai receive_weight (atau kosong),
                                                                     sebelumnya variabel $wLive tidak didefinisikan -->
                                                        <input type="hidden" name="live_weight[]" value="<?= e($wReceive); ?>">
                                                        <input type="hidden" name="eartag[]" value="<?= e($etag); ?>">
                                                    </td>
                                                    <td>
                                                        <select name="breed[]" class="form-control form-control-sm">
                                                            <option value="">- Pilih Class -</option>
                                                            <?php foreach ($breedOptions as $opt): ?>
                                                                <option value="<?= e($opt); ?>"
                                                                    <?= ($opt === $class) ? 'selected' : ''; ?>>
                                                                    <?= e($opt); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0"
                                                            name="carcase1[]" class="form-control form-control-sm text-right"
                                                            placeholder="A" max="500">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0"
                                                            name="carcase2[]" class="form-control form-control-sm text-right"
                                                            placeholder="B" max="500">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0"
                                                            name="hides[]" class="form-control form-control-sm text-right"
                                                            placeholder="Hides" max="100">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0"
                                                            name="tails[]" class="form-control form-control-sm text-right"
                                                            placeholder="Tail" max="100">
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <a href="draft.php" class="btn btn-secondary btn-block btn-sm">
                                            <i class="fas fa-times"></i> Batal
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-success btn-block btn-sm">
                                            <i class="fas fa-save"></i> Simpan Carcas
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div><!-- /.card-body -->
                    </div><!-- /.card -->

                </div>
            </div>

        </div>
    </section>
</div>

<script>
    document.title = "Input Carcas";
</script>

<?php include "../footer.php"; ?>