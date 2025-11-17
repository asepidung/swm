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
if (!function_exists('tglinput')) {
    // Untuk value input[type=date]
    function tglinput($d)
    {
        return $d ? date('Y-m-d', strtotime($d)) : '';
    }
}

// ================================
// Validasi & ambil idcarcase
// ================================
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    exit("Invalid carcase id.");
}
$idcarcase = (int)$_GET['id'];

// ================================
// Ambil HEADER carcase
// ================================
$stmtH = $conn->prepare("
    SELECT 
        c.idcarcase,
        c.killdate,
        c.note,
        c.idweight,
        c.idsupplier,
        s.nmsupplier,
        w.weigh_no,
        w.weigh_date
    FROM carcase c
    LEFT JOIN supplier s      ON s.idsupplier = c.idsupplier
    LEFT JOIN weight_cattle w ON w.idweigh    = c.idweight
    WHERE c.idcarcase = ? AND c.is_deleted = 0
    LIMIT 1
");
$stmtH->bind_param("i", $idcarcase);
$stmtH->execute();
$header = $stmtH->get_result()->fetch_assoc();
$stmtH->close();

if (!$header) {
    http_response_code(404);
    exit("Carcas not found or already deleted.");
}

// ================================
// Ambil DETAIL carcasedetail
// ================================
$stmtD = $conn->prepare("
    SELECT 
        cd.iddetail,
        cd.eartag,
        cd.breed,
        cd.berat,
        cd.carcase1,
        cd.carcase2,
        cd.hides,
        cd.tail
    FROM carcasedetail cd
    WHERE cd.idcarcase = ?
    ORDER BY cd.eartag
");
$stmtD->bind_param("i", $idcarcase);
$stmtD->execute();
$resD = $stmtD->get_result();

$details = [];
while ($row = $resD->fetch_assoc()) {
    $details[] = $row;
}
$stmtD->close();

// opsi class yang diizinkan
$breedOptions = ['STEER', 'HEIFER', 'COW', 'BULL'];
?>

<div class="content-wrapper">
    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 col-md-6">
                    <h1 class="m-0">Edit Carcas</h1>
                    <small class="text-muted">
                        Supplier: <?= e($header['nmsupplier'] ?? '-'); ?> | No Timbang: <?= e($header['weigh_no'] ?? '-'); ?>
                    </small>
                </div>
                <div class="col-12 col-md-6 text-md-right mt-2 mt-md-0">
                    <a href="index.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-undo-alt"></i> Kembali ke Data Carcas
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

                            <!-- Form Edit -->
                            <form action="update.php" method="post">
                                <input type="hidden" name="idcarcase" value="<?= (int)$header['idcarcase']; ?>">

                                <!-- Header info -->
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Killing</label>
                                            <input type="date" name="killdate" class="form-control form-control-sm"
                                                value="<?= e(tglinput($header['killdate'])); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Supplier</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= e($header['nmsupplier'] ?? '-'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No Timbang</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= e($header['weigh_no'] ?? '-'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tgl Timbang</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="<?= $header['weigh_date'] ? e(date('d-M-Y', strtotime($header['weigh_date']))) : '-'; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Catatan</label>
                                    <input type="text" name="note" class="form-control form-control-sm"
                                        value="<?= e($header['note'] ?? ''); ?>"
                                        placeholder="Catatan tambahan (opsional)">
                                </div>

                                <hr>

                                <!-- Detail table -->
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="text-center">
                                            <tr>
                                                <th style="width:5%;">No</th>
                                                <th style="width:14%;">Eartag</th>
                                                <th style="width:14%;">Class</th>
                                                <th style="width:14%;">Live Wt (Kg)</th>
                                                <th>Carcase A (Kg)</th>
                                                <th>Carcase B (Kg)</th>
                                                <th>Hides (Kg)</th>
                                                <th>Tail (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (empty($details)):
                                            ?>
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        Tidak ada detail carcas untuk dokumen ini.
                                                    </td>
                                                </tr>
                                                <?php
                                            else:
                                                $no = 1;
                                                foreach ($details as $d):
                                                    $iddet = (int)$d['iddetail'];
                                                    $etag  = $d['eartag'];
                                                    $breedVal = strtoupper(trim($d['breed'] ?? ''));
                                                    $live  = (float)$d['berat'];
                                                    $c1    = (float)$d['carcase1'];
                                                    $c2    = (float)$d['carcase2'];
                                                    $h     = (float)$d['hides'];
                                                    $t     = (float)$d['tail'];
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?= $no++; ?></td>
                                                        <td class="text-center">
                                                            <?= e($etag); ?>
                                                            <input type="hidden" name="iddetail[]" value="<?= $iddet; ?>">
                                                        </td>
                                                        <td>
                                                            <select name="breed[]" class="form-control form-control-sm">
                                                                <option value="">- Pilih Class -</option>
                                                                <?php foreach ($breedOptions as $opt): ?>
                                                                    <option value="<?= e($opt); ?>"
                                                                        <?= ($opt === $breedVal) ? 'selected' : ''; ?>>
                                                                        <?= e($opt); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td class="text-right">
                                                            <?= number_format($live, 2, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0"
                                                                name="carcase1[]" class="form-control form-control-sm text-right"
                                                                value="<?= e($c1); ?>">
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0"
                                                                name="carcase2[]" class="form-control form-control-sm text-right"
                                                                value="<?= e($c2); ?>">
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0"
                                                                name="hides[]" class="form-control form-control-sm text-right"
                                                                value="<?= e($h); ?>">
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0"
                                                                name="tails[]" class="form-control form-control-sm text-right"
                                                                value="<?= e($t); ?>">
                                                        </td>
                                                    </tr>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <a href="index.php" class="btn btn-secondary btn-block btn-sm">
                                            <i class="fas fa-times"></i> Batal
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-success btn-block btn-sm">
                                            <i class="fas fa-save"></i> Simpan Perubahan
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
    document.title = "Edit Carcas";
</script>

<?php include "../footer.php"; ?>