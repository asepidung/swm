<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

if (empty($_GET['id']) || !ctype_digit($_GET['id'])) die("Invalid PO id.");
$idpo = (int)$_GET['id'];

if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['csrf_token'];

function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function flash($t, $m)
{
    echo "<div class='alert alert-$t alert-dismissible fade show'>$m<button type='button' class='close' data-dismiss='alert'>&times;</button></div>";
}

// suppliers
$suppliers = [];
$qs = $conn->query("SELECT idsupplier, nmsupplier FROM supplier ORDER BY nmsupplier");
if ($qs) while ($row = $qs->fetch_assoc()) $suppliers[] = $row;

// header (TANPA kolom status)
$stmt = $conn->prepare("SELECT idpo,nopo,podate,arrival_date,idsupplier,note FROM pocattle WHERE idpo=? AND is_deleted=0 LIMIT 1");
$stmt->bind_param("i", $idpo);
$stmt->execute();
$po = $stmt->get_result()->fetch_assoc();
if (!$po) die("PO Cattle not found.");

// detail
$det = [];
$qd = $conn->prepare("SELECT class,qty,price,notes FROM pocattledetail WHERE idpo=? AND is_deleted=0 ORDER BY idpodetail");
$qd->bind_param("i", $idpo);
$qd->execute();
$r = $qd->get_result();
while ($row = $r->fetch_assoc()) $det[] = $row;

// flash errors (jika ada)
$errs = $_SESSION['form_errors'] ?? [];
$old  = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_old']);
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit PO Cattle <small class="text-muted">(<?= e($po['nopo']) ?>)</small></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-undo-alt"></i> Kembali</a>
                </div>
            </div>
            <?php foreach ($errs as $er) flash('danger', e($er)); ?>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form method="post" action="update.php" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                <input type="hidden" name="idpo" value="<?= (int)$po['idpo'] ?>">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Header PO</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>No. PO</label>
                                <input type="text" class="form-control" value="<?= e($po['nopo']) ?>" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label>PO Date</label>
                                <input type="date" name="podate" class="form-control" required value="<?= e($old['podate'] ?? $po['podate']) ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Arrival Date (Plan)</label>
                                <input type="date" name="arrival_date" class="form-control" value="<?= e($old['arrival_date'] ?? ($po['arrival_date'] ?? '')) ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Supplier</label>
                                <select name="idsupplier" class="form-control" required>
                                    <option value="">-- pilih supplier --</option>
                                    <?php foreach ($suppliers as $s): ?>
                                        <option value="<?= (int)$s['idsupplier'] ?>"
                                            <?= ((int)($old['idsupplier'] ?? $po['idsupplier']) === (int)$s['idsupplier']) ? 'selected' : '' ?>>
                                            <?= e($s['nmsupplier']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Note</label>
                                <input type="text" name="note" class="form-control" placeholder="Catatan (opsional)" value="<?= e($old['note'] ?? ($po['note'] ?? '')) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DETAIL -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Detail (Cattle Class)</h3>
                        <button type="button" id="btnAddRow" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah Baris</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th style="min-width:160px;">Cattle Class</th>
                                        <th style="width:120px;">Qty (Head)</th>
                                        <th style="width:150px;">Price / Kg</th>
                                        <th>Notes</th>
                                        <th style="width:60px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="detailBody">
                                    <?php
                                    $rows = [];
                                    if (!empty($old['class'])) {
                                        $cnt = count($old['class']);
                                        for ($i = 0; $i < $cnt; $i++) {
                                            $rows[] = [
                                                'class' => $old['class'][$i] ?? '',
                                                'qty' => $old['qty'][$i] ?? '',
                                                'price' => $old['price'][$i] ?? '',
                                                'notes' => $old['notes'][$i] ?? '',
                                            ];
                                        }
                                    } else {
                                        if (!empty($det)) foreach ($det as $d) {
                                            $rows[] = [
                                                'class' => $d['class'],
                                                'qty' => $d['qty'],
                                                'price' => is_null($d['price']) ? '' : $d['price'],
                                                'notes' => $d['notes'],
                                            ];
                                        }
                                        if (empty($rows)) $rows[] = ['class' => '', 'qty' => '', 'price' => '', 'notes' => ''];
                                    }
                                    $classes = ['STEER', 'BULL', 'HEIFER', 'COW'];
                                    foreach ($rows as $rw): ?>
                                        <tr>
                                            <td class="text-center rownum"></td>
                                            <td>
                                                <select name="class[]" class="form-control form-control-sm">
                                                    <option value="">-- pilih --</option>
                                                    <?php foreach ($classes as $c): ?>
                                                        <option value="<?= $c ?>" <?= (strtoupper($rw['class']) === $c ? 'selected' : '') ?>><?= $c ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td><input type="number" min="1" step="1" name="qty[]" class="form-control form-control-sm text-right" value="<?= e($rw['qty']) ?>"></td>
                                            <td><input type="text" name="price[]" class="form-control form-control-sm text-right" placeholder="ex: 62000.00" value="<?= e($rw['price']) ?>"></td>
                                            <td><input type="text" name="notes[]" class="form-control form-control-sm" value="<?= e($rw['notes']) ?>"></td>
                                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm btnDel"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">Total Qty</th>
                                        <th class="text-right" id="totalQty">0</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                    </div>
                </div>

            </form>
        </div>
    </section>
</div>

<?php include "../footer.php"; ?>

<script>
    document.title = "Edit PO Cattle";

    function renumber() {
        const rows = document.querySelectorAll('#detailBody tr');
        let total = 0;
        rows.forEach((tr, i) => {
            const c = tr.querySelector('.rownum');
            if (c) c.textContent = i + 1;
            const q = tr.querySelector('input[name="qty[]"]');
            const v = parseInt(q && q.value ? q.value : '0', 10);
            if (!isNaN(v)) total += v;
        });
        const t = document.getElementById('totalQty');
        if (t) t.textContent = new Intl.NumberFormat('id-ID').format(total);
    }

    function addRow() {
        const tpl = `
      <tr>
        <td class="text-center rownum"></td>
        <td>
          <select name="class[]" class="form-control form-control-sm">
            <option value="">-- pilih --</option>
            <option>STEER</option>
            <option>BULL</option>
            <option>HEIFER</option>
            <option>COW</option>
          </select>
        </td>
        <td><input type="number" min="1" step="1" name="qty[]" class="form-control form-control-sm text-right"></td>
        <td><input type="text" name="price[]" class="form-control form-control-sm text-right" placeholder="ex: 62000.00"></td>
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
                alert('Minimal satu baris detail.');
                return;
            }
            e.target.closest('tr').remove();
            renumber();
        }
    });

    document.getElementById('detailBody').addEventListener('input', function(e) {
        if (e.target.name === 'qty[]') renumber();
    });

    renumber();
</script>