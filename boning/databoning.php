<?php
require "../verifications/auth.php";
$idusers = $_SESSION['idusers'] ?? 0;

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <a href="newboning.php">
            <button type="button" class="btn btn-info">
              <i class="fas fa-plus-circle"></i> Baru
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead class="text-center">
                  <tr>
                    <th>#</th>
                    <th>Batch Number</th>
                    <th>Tgl Boning</th>
                    <th>Supplier</th>
                    <th>Jml Sapi</th>
                    <th>Ttl Weight</th>
                    <th>MBR</th>
                    <th>Catatan</th>
                    <th>AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $ambildata = mysqli_query($conn, "
                    SELECT b.*, p.nmsupplier 
                    FROM boning b 
                    JOIN supplier p ON b.idsupplier = p.idsupplier 
                    WHERE b.is_deleted = 0 
                    ORDER BY b.batchboning DESC
                  ");
                  while ($tampil = mysqli_fetch_array($ambildata)) {

                    $idboning = (int)$tampil['idboning'];

                    // Hitung total berat dari labelboning
                    $query_total_weight = "
                      SELECT SUM(qty) AS total_weight 
                      FROM labelboning 
                      WHERE idboning = $idboning AND is_deleted = 0";
                    $result_total_weight = mysqli_query($conn, $query_total_weight);
                    $row_total_weight = mysqli_fetch_assoc($result_total_weight);
                    $total_weight = $row_total_weight['total_weight'] ?? 0;

                    // Rata-rata berat per sapi
                    $avg_weight_per_sapi = 0;
                    if ($tampil['qtysapi'] > 0) {
                      $avg_weight_per_sapi = $total_weight / $tampil['qtysapi'];
                    }

                    // Cek apakah sudah ada data raw_usage
                    $cekRaw = $conn->query("SELECT COUNT(*) AS jml FROM raw_usage WHERE sumber='BONING' AND idsumber=$idboning");
                    $adaRaw = $cekRaw && ($cekRaw->fetch_assoc()['jml'] > 0);
                  ?>
                    <tr class="text-center">
                      <td><?= $no; ?></td>

                      <!-- Batch Number link ke laporan -->
                      <td>
                        <?php if ($adaRaw): ?>
                          <a href="laporan_rawusage.php?id=<?= $idboning; ?>"
                            class="text-primary font-weight-bold"
                            title="Lihat Laporan Pemakaian Rawmate">
                            <?= htmlspecialchars($tampil['batchboning']); ?>
                          </a>
                        <?php else: ?>
                          <span class="text-muted"><?= htmlspecialchars($tampil['batchboning']); ?></span>
                        <?php endif; ?>
                      </td>

                      <td><?= date("d-M-Y", strtotime($tampil['tglboning'])); ?></td>
                      <td class="text-left"><?= $tampil['nmsupplier']; ?></td>
                      <td><?= $tampil['qtysapi']; ?></td>
                      <td class="text-right"><?= number_format($total_weight, 2); ?></td>
                      <td class="text-right"><?= number_format($avg_weight_per_sapi, 2); ?></td>
                      <td class="text-left"><?= $tampil['keterangan']; ?></td>

                      <td>
                        <?php
                        if (isset($idusers) && ($idusers == 1 || $idusers == 9)) {
                          $is_locked = (int)$tampil['kunci'];
                        ?>
                          <!-- Tombol Lock / Unlock -->
                          <a class="btn btn-sm <?= $is_locked == 1 ? 'btn-danger' : 'btn-secondary'; ?>"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="<?= $is_locked == 1 ? 'Unlock' : 'Lock'; ?>"
                            href="javascript:void(0);"
                            onclick="handleLock(<?= $idboning; ?>, <?= $is_locked == 1 ? 0 : 1; ?>, <?= $adaRaw ? 'true' : 'false'; ?>)">
                            <i class="fas <?= $is_locked == 1 ? 'fa-lock' : 'fa-lock-open fa-spin'; ?>"></i>
                          </a>
                        <?php } ?>

                        <!-- Tombol lainnya -->
                        <a class="btn btn-warning btn-sm" title="Buat Label"
                          onclick="window.location.href='labelboning.php?id=<?= $idboning; ?>'">
                          <i class="fas fa-barcode"></i>
                        </a>
                        <a class="btn btn-success btn-sm" title="Lihat Hasil Boning"
                          onclick="window.location.href='boningdetail.php?id=<?= $idboning; ?>'">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a class="btn btn-info btn-sm" href="editdataboning.php?idboning=<?= $idboning; ?>">
                          <i class="fas fa-pencil-alt"></i>
                        </a>

                        <?php $isLocked = $tampil['kunci'] == 1; ?>
                        <a class="btn btn-danger btn-sm <?= $isLocked ? 'disabled' : ''; ?>"
                          href="<?= !$isLocked ? '#' : ''; ?>"
                          <?= $isLocked ? 'aria-disabled="true" tabindex="-1"' : 'onclick="return confirmDelete(event, ' . $idboning . ')"'; ?>>
                          <i class="fas fa-minus-circle"></i>
                        </a>
                      </td>
                    </tr>
                  <?php $no++;
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  // Handle lock tombol (cek apakah sudah ada data raw_usage)
  function handleLock(idboning, kunci, adaRaw) {
    if (!adaRaw && kunci === 1) {
      alert("Tidak dapat mengunci. Data pemakaian bahan (raw usage) belum ada untuk boning ini.");
      return;
    }
    if (confirm('Apakah kamu yakin ingin ' + (kunci === 1 ? 'mengunci' : 'membuka kunci') + ' batch boning ini?')) {
      window.location.href = 'togglekunci.php?idboning=' + idboning + '&kunci=' + kunci;
    }
  }

  // Hapus data boning (double konfirmasi)
  function confirmDelete(event, idboning) {
    var firstConfirm = confirm("Apakah kamu yakin ingin menghapus data boning ini?");
    if (firstConfirm) {
      var secondConfirm = confirm("PERINGATAN !!! Semua data termasuk data stock akan terhapus, Lanjutkan?");
      if (secondConfirm) {
        window.location.href = "deletedataboning.php?idboning=" + idboning;
      } else event.preventDefault();
    } else event.preventDefault();
  }

  document.title = "Data Boning";
</script>

<?php include "../footer.php"; ?>