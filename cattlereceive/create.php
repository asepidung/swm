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

// Validasi & ambil idpo
if (empty($_GET['idpo']) || !ctype_digit($_GET['idpo'])) die("PO tidak valid.");
$idpo = (int)$_GET['idpo'];

// CSRF
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['csrf_token'];

// Cek: sudah pernah di-receive aktif?
$cek = $conn->prepare("SELECT idreceive FROM cattle_receive WHERE idpo=? AND is_deleted=0 LIMIT 1");
$cek->bind_param("i", $idpo);
$cek->execute();
if ($cek->get_result()->fetch_row()) {
    header("Location: view.php?idpo=" . $idpo);
    exit;
}

// Ambil info PO + supplier + ringkasan qty
$stmt = $conn->prepare("
  SELECT p.idpo, p.nopo, p.podate, p.arrival_date, s.nmsupplier,
         COALESCE(SUM(d.qty),0) AS heads
  FROM pocattle p
  JOIN supplier s ON s.idsupplier = p.idsupplier
  LEFT JOIN pocattledetail d ON d.idpo=p.idpo AND d.is_deleted=0
  WHERE p.idpo=? AND p.is_deleted=0
  GROUP BY p.idpo, p.nopo, p.podate, p.arrival_date, s.nmsupplier
  LIMIT 1
");
$stmt->bind_param("i", $idpo);
$stmt->execute();
$po = $stmt->get_result()->fetch_assoc();
if (!$po) die("PO tidak ditemukan.");

// flash (jika store.php mengembalikan error)
$errs = $_SESSION['form_errors'] ?? [];
$old  = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_old']);

// data lama detail (jika ada error dari store.php)
$old_rows = [];
if (!empty($old['class'])) {
    $cnt = count($old['class']);
    for ($i = 0; $i < $cnt; $i++) {
        $old_rows[] = [
            'class'  => $old['class'][$i]  ?? '',
            'eartag' => $old['eartag'][$i] ?? '',
            'weight' => $old['weight'][$i] ?? '',
            'notes'  => $old['notes'][$i]  ?? '',
        ];
    }
}
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Receive <small class="text-muted">(PO: <?= e($po['nopo']) ?>)</small></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="draft.php" class="btn btn-secondary btn-sm"><i class="fas fa-undo-alt"></i> Kembali</a>
                </div>
            </div>
            <?php foreach ($errs as $er): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= e($er) ?><button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- Ringkasan PO -->
            <div class="card">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-2">PO Number</dt>
                        <dd class="col-sm-4"><?= e($po['nopo']) ?></dd>
                        <dt class="col-sm-2">Supplier</dt>
                        <dd class="col-sm-4"><?= e($po['nmsupplier']) ?></dd>

                        <dt class="col-sm-2">PO Date</dt>
                        <dd class="col-sm-4"><?= tgl($po['podate']) ?></dd>
                        <dt class="col-sm-2">Plan Arrival</dt>
                        <dd class="col-sm-4"><?= tgl($po['arrival_date']) ?></dd>

                        <dt class="col-sm-2">Cattle Qty</dt>
                        <dd class="col-sm-4"><?= number_format((int)$po['heads'], 0, ',', '.') ?> head</dd>
                    </dl>
                </div>
            </div>

            <!-- Form Create Receive -->
            <form method="post" action="store.php" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                <input type="hidden" name="idpo" value="<?= (int)$po['idpo'] ?>">

                <!-- Header Receive (1 baris: 3-3-1-1-4) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Header Receive</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row align-items-end">
                            <div class="form-group col-md-3">
                                <label>Receipt Date</label>
                                <input type="date" name="receipt_date" class="form-control" required
                                    value="<?= e($old['receipt_date'] ?? date('Y-m-d')) ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Doc No (Surat Jalan)</label>
                                <input type="text" name="doc_no" class="form-control" maxlength="50"
                                    value="<?= e($old['doc_no'] ?? '') ?>">
                            </div>

                            <!-- Fallback hidden agar saat OFF terkirim 0 -->
                            <input type="hidden" name="sv_ok" value="0">
                            <div class="form-group col-md-1">
                                <label>SV</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sv_ok" name="sv_ok" value="1"
                                        <?= ((string)($old['sv_ok'] ?? '0') === '1' ? 'checked' : '') ?>>
                                    <label class="custom-control-label" for="sv_ok">Y/T</label>
                                </div>
                            </div>

                            <input type="hidden" name="skkh_ok" value="0">
                            <div class="form-group col-md-1">
                                <label>SKKH</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="skkh_ok" name="skkh_ok" value="1"
                                        <?= ((string)($old['skkh_ok'] ?? '0') === '1' ? 'checked' : '') ?>>
                                    <label class="custom-control-label" for="skkh_ok">Y/T</label>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Note</label>
                                <input type="text" name="note" class="form-control" maxlength="255"
                                    value="<?= e($old['note'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DETAIL PER EKOR (tanpa RFID) -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Detail (Per Ekor)</h3>
                        <button type="button" id="btnAddRow" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Class</th>
                                        <th>Eartag</th>
                                        <th>Weight (Kg)</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="detailBody">
                                    <?php
                                    $classes = ['STEER', 'BULL', 'HEIFER', 'COW'];
                                    $rows = $old_rows;
                                    if (empty($rows)) {
                                        $rows[] = ['class' => '', 'eartag' => '', 'weight' => '', 'notes' => ''];
                                    }
                                    foreach ($rows as $rw): ?>
                                        <tr>
                                            <td class="text-center rownum"></td>
                                            <td>
                                                <select name="class[]" class="form-control form-control-sm" required>
                                                    <option value="">-- pilih --</option>
                                                    <?php foreach ($classes as $c): ?>
                                                        <option value="<?= $c ?>" <?= (strtoupper($rw['class']) === $c ? 'selected' : '') ?>><?= $c ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td><input type="text" name="eartag[]" class="form-control form-control-sm" maxlength="50" value="<?= e($rw['eartag']) ?>" required></td>
                                            <td><input type="number" name="weight[]" class="form-control form-control-sm text-right" step="0.01" min="0.01" value="<?= e($rw['weight']) ?>" required></td>
                                            <td><input type="text" name="notes[]" class="form-control form-control-sm" value="<?= e($rw['notes']) ?>"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm btnDel"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">Total Head</th>
                                        <th class="text-right" id="totalHead">0</th>
                                        <th class="text-right" id="totalWeight">0,00</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Receive
                        </button>
                        <a href="draft.php" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>

        </div>
    </section>
</div>

<?php include "../footer.php"; ?>

<script>
    function renumber() {
        const rows = document.querySelectorAll('#detailBody tr');
        let head = 0,
            w = 0;
        rows.forEach((tr, i) => {
            const c = tr.querySelector('.rownum');
            if (c) c.textContent = i + 1;
            const weight = tr.querySelector('input[name="weight[]"]');
            const wt = parseFloat(weight && weight.value ? weight.value : '0');
            if (!isNaN(wt) && wt > 0) {
                head += 1;
                w += wt;
            }
        });
        document.getElementById('totalHead').textContent = new Intl.NumberFormat('id-ID').format(head);
        document.getElementById('totalWeight').textContent = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(w);
    }

    function addRow() {
        const tpl = `
  <tr>
    <td class="text-center rownum"></td>
    <td>
      <select name="class[]" class="form-control form-control-sm" required>
        <option value="">-- pilih --</option>
        <option>STEER</option>
        <option>BULL</option>
        <option>HEIFER</option>
        <option>COW</option>
      </select>
    </td>
    <td><input type="text" name="eartag[]" class="form-control form-control-sm" maxlength="50" required></td>
    <td><input type="number" name="weight[]" class="form-control form-control-sm text-right" step="0.01" min="0.01" required></td>
    <td><input type="text" name="notes[]" class="form-control form-control-sm"></td>
    <td class="text-center"><button type="button" class="btn btn-danger btn-sm btnDel"><i class="fas fa-trash"></i></button></td>
  </tr>`;
        document.getElementById('detailBody').insertAdjacentHTML('beforeend', tpl);
        renumber();
    }

    document.getElementById('btnAddRow').addEventListener('click', addRow);
    document.getElementById('detailBody').addEventListener('click', function(e) {
        if (e.target.closest('.btnDel')) {
            const rows = document.querySelectorAll('#detailBody tr');
            if (rows.length <= 1) {
                alert('Minimal satu baris.');
                return;
            }
            e.target.closest('tr').remove();
            renumber();
        }
    });
    document.getElementById('detailBody').addEventListener('input', function(e) {
        if (e.target.name === 'weight[]' || e.target.name === 'eartag[]') renumber();
    });
    renumber();
</script>