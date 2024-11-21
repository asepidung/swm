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

// Tambahkan prefix ke ID boning
$idboningWithPrefix = str_pad($idboning, 4, "0", STR_PAD_LEFT);

// Query daftar barang
$queryBarang = "SELECT * FROM barang ORDER BY nmbarang ASC";
$resultBarang = mysqli_query($conn, $queryBarang);
if (!$resultBarang) {
  die("Error pada query barang: " . mysqli_error($conn));
}

// Query daftar grade
$queryGrade = "SELECT * FROM grade ORDER BY nmgrade ASC";
$resultGrade = mysqli_query($conn, $queryGrade);
if (!$resultGrade) {
  die("Error pada query grade: " . mysqli_error($conn));
}

// Set tanggal default untuk packed date
if (!isset($_SESSION['packdate']) || $_SESSION['packdate'] == '') {
  $_SESSION['packdate'] = date('Y-m-d');
}
?>
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <a href="databoning.php"><button type="button" class="btn btn-sm btn-success"><i class="fas fa-undo-alt"></i> DATA BONING</button></a>
        </div>
      </div>
    </div>
  </div>
  <!-- Main Content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4">
          <!-- Form Card -->
          <div class="card">
            <div class="card-body">
              <form method="POST" action="cetaklabelboning.php" onsubmit="submitForm(event)">
                <!-- Dropdown Barang -->
                <div class="form-group">
                  <div class="input-group">
                    <select class="form-control" name="idbarang" id="idbarang" required>
                      <option value="" selected>--Pilih Item--</option>
                      <?php while ($row = mysqli_fetch_assoc($resultBarang)) : ?>
                        <option value="<?= $row['idbarang']; ?>"><?= $row['nmbarang']; ?></option>
                      <?php endwhile; ?>
                    </select>
                    <div class="input-group-append">
                      <a href="../barang/newbarang.php" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                    </div>
                  </div>
                </div>

                <!-- Dropdown Grade -->
                <div class="form-group">
                  <div class="input-group">
                    <select class="form-control" name="idgrade" id="idgrade" required>
                      <option value="" selected>--Pilih Grade--</option>
                      <?php while ($row = mysqli_fetch_assoc($resultGrade)) : ?>
                        <option value="<?= $row['idgrade']; ?>"><?= $row['nmgrade']; ?></option>
                      <?php endwhile; ?>
                    </select>
                  </div>
                </div>

                <!-- Packed Date -->
                <div class="form-group">
                  <div class="input-group">
                    <input type="date" class="form-control" name="packdate" id="packdate" required value="<?= $_SESSION['packdate']; ?>">
                  </div>
                </div>

                <!-- Expired Date -->
                <div class="form-group">
                  <div class="input-group">
                    <input type="date" readonly class="form-control" name="exp" id="exp" value="<?= $_SESSION['exp'] ?? ''; ?>">
                  </div>
                </div>

                <!-- Tenderstreach -->
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="tenderstreach" id="tenderstreach" <?= isset($_SESSION['tenderstreach']) && $_SESSION['tenderstreach'] ? 'checked' : ''; ?>>
                  <label class="form-check-label">Aktifkan Tenderstreatch</label>
                </div>

                <!-- Hidden Inputs -->
                <input type="hidden" name="idusers" id="idusers" value="<?= $idusers ?>">
                <input type="hidden" name="idboningWithPrefix" id="idboningWithPrefix" value="<?= $idboningWithPrefix; ?>">
                <input type="hidden" name="idboning" id="idboning" value="<?= $idboning; ?>">

                <!-- Qty Input -->
                <div class="row">
                  <div class="col-8">
                    <div class="form-group">
                      <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" required>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <input type="number" class="form-control" name="count" id="count" value="1" readonly>
                    </div>
                  </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn bg-gradient-primary btn-block" name="submit">Print</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Table Section -->
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
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
                    <th>Hapus</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $queryData = "SELECT l.*, b.nmbarang, u.fullname, g.nmgrade 
                                                  FROM labelboning l
                                                  JOIN barang b ON l.idbarang = b.idbarang 
                                                  JOIN boning bo ON l.idboning = bo.idboning
                                                  JOIN grade g ON l.idgrade = g.idgrade
                                                  JOIN users u ON l.iduser = u.idusers
                                                  WHERE l.idboning = $idboning ORDER BY l.idlabelboning DESC";
                  $resultData = mysqli_query($conn, $queryData);

                  while ($tampil = mysqli_fetch_assoc($resultData)) :
                    $fullname = $tampil['fullname'];
                    $kdbarcode = $tampil['kdbarcode'];
                    $existsTally = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tallydetail WHERE barcode = '$kdbarcode'"))['total'] > 0;
                    $existsDetailBahan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM detailbahan WHERE barcode = '$kdbarcode'"))['total'] > 0;
                  ?>
                    <tr class="text-center">
                      <td><?= $no++; ?></td>
                      <td><?= $tampil['kdbarcode']; ?></td>
                      <td><?= $tampil['nmgrade']; ?></td>
                      <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                      <td><?= $tampil['qty']; ?></td>
                      <td><?= $tampil['pcs']; ?></td>
                      <td><?= $fullname; ?></td>
                      <td><?= date("H:i:s", strtotime($tampil['dibuat'])); ?></td>
                      <td>
                        <?php if ($existsTally) : ?>
                          <i class="fas fa-check-circle"></i>
                        <?php elseif ($existsDetailBahan) : ?>
                          <i class="fas fa-box-open text-success"></i>
                        <?php else : ?>
                          <a href="hapus_labelboning.php?id=<?= $tampil['idlabelboning']; ?>&idboning=<?= $idboning; ?>&kdbarcode=<?= $tampil['kdbarcode']; ?>" class="text-danger" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="far fa-times-circle"></i>
                          </a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.title = "Boning <?= "BN" . $idboningWithPrefix ?>";
  </script>
</div>
<?php
require "../footer.php";
?>