<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$idusers = $_SESSION['idusers'] ?? 0;

/* Generate nomor OU-YYMMDD-### */
$prefix = "OU-" . date('ymd') . "-";
$run = 1;
$q = mysqli_query($conn, "SELECT COUNT(*) AS n FROM usage_other WHERE DATE(createtime)=CURDATE()");
if ($q) {
    $run = (int)mysqli_fetch_assoc($q)['n'] + 1;
}
$noother = $prefix . str_pad($run, 3, "0", STR_PAD_LEFT);

/* Dropdown rawmate */
$rawOptions = "";
$qraw = mysqli_query($conn, "SELECT idrawmate, nmrawmate FROM rawmate WHERE stock=1 ORDER BY nmrawmate ASC");
while ($r = mysqli_fetch_assoc($qraw)) {
    $rawOptions .= '<option value="' . $r['idrawmate'] . '">' . htmlspecialchars($r['nmrawmate']) . '</option>';
}
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h4><i class="fas fa-share-square"></i> Pengeluaran Material — Lainnya</h4>
                    <div class="small text-muted">Semua baris disimpan ke <code>raw_usage</code> dengan sumber <b>OTHER</b>.</div>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-undo-alt"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form method="post" action="save_other_usage.php" id="otherForm">
                <div class="card card-dark shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Header Dokumen</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>No Dokumen</label>
                                <input type="text" name="noother" class="form-control" value="<?= htmlspecialchars($noother) ?>" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tanggal</label>
                                <input type="date" name="tgl" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Catatan</label>
                                <input type="text" name="note" class="form-control" placeholder="Keterangan (opsional)">
                            </div>
                        </div>

                        <hr>
                        <h6 class="mb-2">Detail Material</h6>
                        <div id="rows"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()"><i class="fas fa-plus-circle"></i> Tambah Baris</button>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </div>
                <input type="hidden" name="idusers" value="<?= (int)$idusers ?>">
            </form>
        </div>
    </section>
</div>

<script>
    function addRow() {
        const wrap = document.getElementById('rows');
        const row = document.createElement('div');
        row.className = 'form-row align-items-end mb-2 other-item';
        row.innerHTML = `
    <div class="col-md-6">
      <label>Material</label>
      <select name="idrawmate[]" class="form-control select2" required>
        <option value="">-- pilih material --</option>
        <?= $rawOptions ?>
      </select>
    </div>
    <div class="col-md-3">
      <label>Qty</label>
      <input type="number" name="qty[]" class="form-control" step="1" min="0" required>
    </div>
    <div class="col-md-3">
      <label>Catatan Baris</label>
      <div class="input-group">
        <input type="text" name="row_note[]" class="form-control" placeholder="opsional">
        <div class="input-group-append">
          <button type="button" class="btn btn-outline-danger" onclick="this.closest('.other-item').remove()"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>
  `;
        wrap.appendChild(row);
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    }
    document.addEventListener('DOMContentLoaded', () => {
        addRow();
    });
    document.title = "Pengeluaran Material - Lainnya";
</script>

<?php include "../footnote.php"; ?>
<?php include "../footer.php"; ?>