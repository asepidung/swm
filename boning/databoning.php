<?php
require "../verifications/auth.php";
$idusers = $_SESSION['idusers'] ?? 0;

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">

  <!-- Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <a href="newboning.php" class="btn btn-info">
            <i class="fas fa-plus-circle"></i> Baru
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

                  while ($tampil = mysqli_fetch_assoc($ambildata)) {

                    $idboning = (int)$tampil['idboning'];

                    // Total berat produksi
                    $qWeight = mysqli_query($conn, "
                      SELECT SUM(qty) AS total_weight
                      FROM labelboning
                      WHERE idboning = $idboning AND is_deleted = 0
                    ");
                    $rw = mysqli_fetch_assoc($qWeight);
                    $total_weight = (float)($rw['total_weight'] ?? 0);

                    // MBR
                    $avg_weight = 0;
                    if ((int)$tampil['qtysapi'] > 0) {
                      $avg_weight = $total_weight / (int)$tampil['qtysapi'];
                    }

                    $isLocked = (int)$tampil['kunci'];
                  ?>
                    <tr class="text-center">
                      <td><?= $no++; ?></td>

                      <!-- Batch Number: selalu link ke laporan -->
                      <td>
                        <a href="laporan_rawusage.php?id=<?= $idboning; ?>"
                          class="text-primary font-weight-bold"
                          title="Lihat Laporan Pemakaian Bahan (HPP)">
                          <?= htmlspecialchars($tampil['batchboning']); ?>
                        </a>
                      </td>

                      <td><?= date("d-M-Y", strtotime($tampil['tglboning'])); ?></td>
                      <td class="text-left"><?= htmlspecialchars($tampil['nmsupplier']); ?></td>
                      <td><?= (int)$tampil['qtysapi']; ?></td>
                      <td class="text-right"><?= number_format($total_weight, 2); ?></td>
                      <td class="text-right"><?= number_format($avg_weight, 2); ?></td>
                      <td class="text-left"><?= htmlspecialchars($tampil['keterangan']); ?></td>

                      <td class="text-nowrap">

                        <?php if ($idusers == 1 || $idusers == 9): ?>
                          <!-- Lock / Unlock -->
                          <a class="btn btn-sm <?= $isLocked ? 'btn-danger' : 'btn-secondary'; ?>"
                            title="<?= $isLocked ? 'Unlock' : 'Lock'; ?>"
                            href="togglekunci.php?idboning=<?= $idboning; ?>&kunci=<?= $isLocked ? 0 : 1; ?>"
                            onclick="return confirm('Apakah yakin ingin <?= $isLocked ? 'membuka kunci' : 'mengunci'; ?> boning ini?')">
                            <i class="fas <?= $isLocked ? 'fa-lock' : 'fa-lock-open'; ?>"></i>
                          </a>
                        <?php endif; ?>

                        <!-- Label -->
                        <a class="btn btn-warning btn-sm"
                          title="Buat Label"
                          href="labelboning.php?id=<?= $idboning; ?>">
                          <i class="fas fa-barcode"></i>
                        </a>

                        <!-- Detail -->
                        <a class="btn btn-success btn-sm"
                          title="Lihat Hasil Boning"
                          href="boningdetail.php?id=<?= $idboning; ?>">
                          <i class="fas fa-eye"></i>
                        </a>

                        <!-- Edit -->
                        <a class="btn btn-info btn-sm"
                          title="Edit Boning"
                          href="editdataboning.php?idboning=<?= $idboning; ?>">
                          <i class="fas fa-pencil-alt"></i>
                        </a>

                        <!-- Delete (disable jika terkunci) -->
                        <a class="btn btn-danger btn-sm <?= $isLocked ? 'disabled' : ''; ?>"
                          href="<?= $isLocked ? 'javascript:void(0)' : 'deletedataboning.php?idboning=' . $idboning; ?>"
                          onclick="<?= $isLocked ? '' : 'return confirm(\'Yakin ingin menghapus data boning ini?\')'; ?>">
                          <i class="fas fa-minus-circle"></i>
                        </a>

                      </td>
                    </tr>
                  <?php } ?>
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
  document.title = "Data Boning";
  $(function() {
    $("#example1").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      ordering: false,
      paging: true,
      pageLength: 25,
      searching: true,
      info: true,
      buttons: ["copy", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>

<?php include "../footer.php"; ?>