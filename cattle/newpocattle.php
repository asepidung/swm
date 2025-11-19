<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function flash($type, $msg)
{
    echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>" . e($msg) . "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
}

// suppliers
$suppliers = [];
$q = $conn->query("SELECT idsupplier, nmsupplier FROM supplier ORDER BY nmsupplier ASC");
if ($q) while ($row = $q->fetch_assoc()) {
    $suppliers[] = $row;
}

// ambil old input kalau redirect error
$old = $_SESSION['form_old'] ?? [];
$errs = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_old'], $_SESSION['form_errors']);
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New PO Cattle</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-undo-alt"></i> Kembali</a>
                </div>
            </div>

            <?php if (!empty($errs)) foreach ($errs as $er) flash('danger', $er); ?>
            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'created') flash('success', 'PO berhasil dibuat'); ?>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form method="post" action="create.php" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>PO Date</label>
                                <input type="date" name="podate" class="form-control" required
                                    value="<?= e($old['podate'] ?? date('Y-m-d')) ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Arrival Date (Plan)</label>
                                <input type="date" name="arrival_date" class="form-control" required
                                    value="<?= e($old['arrival_date'] ?? '') ?>">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Supplier</label>
                                <select name="idsupplier" class="form-control" required>
                                    <option value="">-- pilih supplier --</option>
                                    <?php foreach ($suppliers as $s): ?>
                                        <option value="<?= (int)$s['idsupplier'] ?>"
                                            <?= (isset($old['idsupplier']) && (int)$old['idsupplier'] === (int)$s['idsupplier']) ? 'selected' : '' ?>>
                                            <?= e($s['nmsupplier']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- <label>Note</label> -->
                            <input type="text" name="note" class="form-control" placeholder="Catatan (opsional)"
                                value="<?= e($old['note'] ?? '') ?>">
                        </div>
                    </div>
                </div>


                <!-- DETAIL -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Detail (Cattle Class)</h3>
                        <button type="button" id="btnAddRow" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0" id="tblDetail">
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
                                    $rowCount = 1;
                                    if (!empty($old['class'])) $rowCount = count($old['class']);
                                    for ($i = 0; $i < $rowCount; $i++):
                                        $c = e($old['class'][$i]  ?? '');
                                        $q = e($old['qty'][$i]    ?? '');
                                        $p = e($old['price'][$i]  ?? '');
                                        $n = e($old['notes'][$i]  ?? '');
                                    ?>
                                        <tr>
                                            <td class="text-center rownum"></td>
                                            <td>
                                                <select name="class[]" class="form-control form-control-sm">
                                                    <option value="">-- pilih --</option>
                                                    <option <?= $c === 'STEER' ? 'selected' : '' ?>>STEER</option>
                                                    <option <?= $c === 'BULL' ? 'selected' : '' ?>>BULL</option>
                                                    <option <?= $c === 'HEIFER' ? 'selected' : '' ?>>HEIFER</option>
                                                    <option <?= $c === 'COW' ? 'selected' : '' ?>>COW</option>
                                                </select>
                                            </td>
                                            <td><input type="number" min="1" step="1" name="qty[]" class="form-control form-control-sm text-right" value="<?= $q ?>"></td>
                                            <td><input type="text" name="price[]" class="form-control form-control-sm text-right" placeholder="ex: 62000.00" value="<?= $p ?>"></td>
                                            <td><input type="text" name="notes[]" class="form-control form-control-sm" value="<?= $n ?>"></td>
                                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm btnDel"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                    <?php endfor; ?>
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
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<?php include "../footer.php"; ?>

<script>
    document.title = "New PO Cattle";

    // Jika nanti butuh tombol Generate lagi, tinggal aktifkan guard ini:
    const genBtn = document.getElementById('btnGenNo');
    if (genBtn) {
        genBtn.addEventListener('click', async () => {
            try {
                const res = await fetch('ponumber.php', {
                    cache: 'no-store'
                });
                const no = await res.text();
                const nopoInput = document.getElementById('nopo');
                if (nopoInput) nopoInput.value = no.trim();
            } catch (e) {
                alert('Gagal generate nomor PO');
            }
        });
    }

    function renumber() {
        const rows = document.querySelectorAll('#detailBody tr');
        let total = 0;
        rows.forEach((tr, idx) => {
            const idxCell = tr.querySelector('.rownum');
            if (idxCell) idxCell.textContent = idx + 1;

            const qtyEl = tr.querySelector('input[name="qty[]"]');
            const v = parseInt((qtyEl && qtyEl.value) ? qtyEl.value : '0', 10);
            if (!isNaN(v)) total += v;
        });
        const totalEl = document.getElementById('totalQty');
        if (totalEl) totalEl.textContent = new Intl.NumberFormat('id-ID').format(total);
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
        const body = document.getElementById('detailBody');
        body.insertAdjacentHTML('beforeend', tpl);
        renumber();
    }

    // Pastikan DOM siap
    document.addEventListener('DOMContentLoaded', () => {
        const btnAdd = document.getElementById('btnAddRow');
        if (btnAdd) btnAdd.addEventListener('click', addRow);

        const body = document.getElementById('detailBody');
        if (body) {
            body.addEventListener('click', (e) => {
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

            body.addEventListener('input', (e) => {
                if (e.target.name === 'qty[]') renumber();
            });
        }

        renumber();
    });
</script>