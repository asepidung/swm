<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
  exit;
}

// Koneksi ke database
require "../konak/conn.php";

// Sertakan header, navbar, dan sidebar
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// Periksa apakah ID boning tersedia di parameter GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("Jalankan Dari Modul Produksi");
}
$idboning = intval($_GET['id']);
$idusers = $_SESSION['idusers'] ?? 0;

// Query daftar barang
$queryBarang = "SELECT * FROM barang ORDER BY nmbarang ASC";
$resultBarang = mysqli_query($conn, $queryBarang);

// Query daftar grade
$queryGrade = "SELECT * FROM grade ORDER BY nmgrade ASC";
$resultGrade = mysqli_query($conn, $queryGrade);

// Set tanggal default untuk packed date
if (!isset($_SESSION['packdate']) || $_SESSION['packdate'] == '') {
  $_SESSION['packdate'] = date('Y-m-d');
}

$packdate = $_SESSION['packdate'];

// Periksa status kunci boning
$queryKunci = "SELECT kunci FROM boning WHERE idboning = $idboning";
$resultKunci = mysqli_query($conn, $queryKunci);
$rowKunci = mysqli_fetch_assoc($resultKunci);
$is_locked = $rowKunci['kunci'];

?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <a href="databoning.php"><button type="button" class="btn btn-sm btn-success"><i class="fas fa-undo-alt"></i> DATA BONING</button></a>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <?php if ($is_locked == 0): ?>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body">
                <form method="POST" action="insert_labelboning.php">
                  <!-- Dropdown Barang -->
                  <div class="form-group">
                    <div class="input-group">
                      <select class="form-control" name="idbarang" id="idbarang" required autofocus>
                        <option value="">--Pilih Item--</option>
                        <?php while ($row = mysqli_fetch_assoc($resultBarang)) : ?>
                          <option value="<?= $row['idbarang']; ?>"><?= $row['nmbarang']; ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                  </div>

                  <!-- Dropdown Grade -->
                  <div class="form-group">
                    <div class="input-group">
                      <select class="form-control" name="idgrade" id="idgrade" required>
                        <option value="">--Pilih Grade--</option>
                        <?php while ($row = mysqli_fetch_assoc($resultGrade)) : ?>
                          <option value="<?= $row['idgrade']; ?>"><?= $row['nmgrade']; ?></option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                  </div>

                  <!-- Packed Date -->
                  <div class="form-group">
                    <input type="date" class="form-control" name="packdate" id="packdate" required value="<?= $packdate; ?>">
                  </div>

                  <!-- Expired Date -->
                  <div class="form-group">
                    <input type="date" readonly class="form-control" name="exp" id="exp" value="<?= $_SESSION['exp'] ?? ''; ?>">
                  </div>

                  <!-- Qty Input -->
                  <div class="form-group">
                    <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" required>
                  </div>

                  <!-- Submit Button -->
                  <button type="submit" class="btn bg-gradient-primary btn-block" name="submit">Print</button>
                </form>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Tabel Hasil Boning -->
        <div class="col-lg">
          <div class="card">
            <div class="card-body">
              <table id="labelTable" class="table table-bordered table-striped table-sm">
                <thead class="text-center">
                  <tr>
                    <th>#</th>
                    <th>Barcode</th>
                    <th>Grade</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Pcs</th>
                    <th>Author</th>
                    <th>Create</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#labelTable').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "fetch_labelboning.php?id=<?= $idboning ?>",
        "type": "GET"
      },
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

<?php
require "../footer.php";
?>