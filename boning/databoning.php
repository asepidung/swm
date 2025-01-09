<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
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
          <!-- <h1 class="m-0">DATA BONING</h1> -->
          <a href="newboning.php"><button type="button" class="btn btn-info"><i class="fas fa-plus-circle"></i> Baru</button></a>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- /.card-header -->
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
                  $ambildata = mysqli_query($conn, "SELECT b.*, p.nmsupplier FROM boning b JOIN supplier p ON b.idsupplier = p.idsupplier WHERE b.is_deleted = 0 ORDER BY b.batchboning DESC");
                  while ($tampil = mysqli_fetch_array($ambildata)) {

                    // Menghitung total berat dari labelboning terkait dengan idboning
                    $query_total_weight = "SELECT SUM(qty) AS total_weight FROM labelboning WHERE idboning = " . $tampil['idboning'] . " AND is_deleted = 0";
                    $result_total_weight = mysqli_query($conn, $query_total_weight);
                    $row_total_weight = mysqli_fetch_assoc($result_total_weight);
                    $total_weight = $row_total_weight['total_weight'];

                    // Menghitung rata-rata berat per sapi
                    $avg_weight_per_sapi = 0;
                    if ($tampil['qtysapi'] > 0) {
                      $avg_weight_per_sapi = $total_weight / $tampil['qtysapi'];
                    }
                  ?>

                    <tr class="text-center">
                      <td><?= $no; ?></td>
                      <td><?= $tampil['batchboning']; ?></td>
                      <td><?= date("d-M-Y", strtotime($tampil['tglboning'])); ?></td>
                      <td class="text-left"><?= $tampil['nmsupplier']; ?></td>
                      <td><?= $tampil['qtysapi']; ?></td>
                      <td class="text-right"><?= number_format($total_weight, 2); ?></td>
                      <td class="text-right"><?= number_format($avg_weight_per_sapi, 2); ?></td>
                      <td class="text-left"><?= $tampil['keterangan']; ?></td>
                      </button>
                      <td>
                        <?php
                        if (isset($idusers) && ($idusers == 1 || $idusers == 9)) {
                          $is_locked = $tampil['kunci']; // Ambil status kunci dari tabel boning
                        ?>
                          <a class="btn btn-sm <?= $is_locked == 1 ? 'btn-danger' : 'btn-secondary'; ?>"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="<?= $is_locked == 1 ? 'Unlock' : 'Lock'; ?>"
                            href="javascript:void(0);"
                            onclick="confirmLock(<?= $tampil['idboning']; ?>, <?= $is_locked == 1 ? 0 : 1; ?>)">
                            <i class="fas <?= $is_locked == 1 ? 'fa-lock' : 'fa-lock-open'; ?>"></i>
                          </a>
                        <?php
                        }
                        ?>
                        <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Buat Label" onclick="window.location.href='labelboning.php?id=<?php echo $tampil['idboning']; ?>'">
                          <i class="fas fa-barcode"></i>
                        </a>
                        <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Lihat Hasil Boning" onclick="window.location.href='boningdetail.php?id=<?php echo $tampil['idboning']; ?>'">
                          <i class="fas fa-eye">
                          </i>
                        </a>
                        <a class="btn btn-info btn-sm" href="editdataboning.php?idboning=<?= $tampil['idboning'] ?>">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                        <?php
                        // Periksa nilai kunci
                        $isLocked = $tampil['kunci'] == 1; // Asumsikan $tampil['kunci'] berisi nilai kunci
                        ?>
                        <a class="btn btn-danger btn-sm <?= $isLocked ? 'disabled' : ''; ?>"
                          href="<?= !$isLocked ? "#" : ''; ?>"
                          <?= $isLocked ? 'aria-disabled="true" tabindex="-1"' : 'onclick="return confirmDelete(event, ' . $tampil['idboning'] . ')"'; ?>>
                          <i class="fas fa-minus-circle"></i>
                        </a>

                      </td>
                    </tr>
                  <?php
                    $no++;
                  }
                  ?>
                </tbody>

              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
  <!-- </div> -->
  <!-- /.content-wrapper -->

  <script>
    function confirmLock(idboning, kunci) {
      // Menampilkan dialog konfirmasi
      if (confirm('Apakah kamu yakin ingin ' + (kunci === 1 ? 'mengunci' : 'membuka kunci') + ' batch boning ini?')) {
        // Redirect ke file PHP yang menangani pembaruan database
        window.location.href = 'togglekunci.php?idboning=' + idboning + '&kunci=' + kunci;
      }
    }
    // Mengubah judul halaman web
    document.title = "Data Boning";

    function confirmDelete(event, idboning) {
      // Konfirmasi pertama
      var firstConfirm = confirm("Apakah kamu yakin ingin menghapus data boning ini?");

      if (firstConfirm) {
        // Konfirmasi kedua
        var secondConfirm = confirm("PERINGATAN !!! Semua data termasuk data stock akan terhapus, Lanjutkan?");

        if (secondConfirm) {
          // Jika kedua konfirmasi dijawab Yes, lanjutkan ke proses penghapusan
          window.location.href = "deletedataboning.php?idboning=" + idboning;
        } else {
          // Jika konfirmasi kedua ditolak, batalkan tindakan penghapusan
          event.preventDefault(); // Mencegah link untuk menuju ke halaman penghapusan
        }
      } else {
        // Jika konfirmasi pertama ditolak, batalkan tindakan penghapusan
        event.preventDefault(); // Mencegah link untuk menuju ke halaman penghapusan
      }
    }
  </script>
  <?php
  // require "../footnote.php";
  include "../footer.php" ?>