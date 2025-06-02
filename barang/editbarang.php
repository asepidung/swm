<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$idbarang = $_GET['idbarang'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM barang WHERE idbarang = ?");
$stmt->bind_param("i", $idbarang);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$tipebarang = is_null($row['kodeinduk']) ? 'utama' : 'turunan';
?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card card-dark mt-3">
            <div class="card-header">
              <h3 class="card-title">Edit Barang</h3>
            </div>
            <form method="POST" action="proseseditbarang.php" id="formEditBarang">
              <div class="card-body">

                <input type="hidden" name="idbarang" value="<?= htmlspecialchars($idbarang) ?>">

                <div class="form-group">
                  <label for="tipebarang">Tipe Barang <span class="text-danger">*</span></label>
                  <select class="form-control" name="tipebarang" id="tipebarang" required>
                    <option value="utama" <?= $tipebarang === 'utama' ? 'selected' : '' ?>>Barang Utama</option>
                    <option value="turunan" <?= $tipebarang === 'turunan' ? 'selected' : '' ?>>Barang Turunan</option>
                  </select>
                </div>

                <div class="form-group" id="kodeContainer">
                  <label for="kdbarang">Kode Barang <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="kdbarang" id="kdbarang" value="<?= htmlspecialchars($row['kdbarang']) ?>" required>
                </div>

                <div class="form-group <?= $tipebarang === 'turunan' ? '' : 'd-none' ?>" id="parentContainer">
                  <label for="kodeinduk">Barang Induk <span class="text-danger">*</span></label>
                  <select class="form-control" name="kodeinduk" id="kodeinduk">
                    <option value="" disabled <?= $tipebarang === 'turunan' ? '' : 'selected' ?>>Pilih Barang Induk</option>
                    <?php
                    $query = mysqli_query($conn, "SELECT kdbarang, nmbarang FROM barang WHERE kodeinduk IS NULL ORDER BY nmbarang ASC");
                    while ($induk = mysqli_fetch_assoc($query)) {
                      $selected = ($induk['kdbarang'] == $row['kodeinduk']) ? 'selected' : '';
                      echo '<option value="' . htmlspecialchars($induk['kdbarang']) . '" ' . $selected . '>'
                        . strtoupper(htmlspecialchars($induk['nmbarang'])) . ' - ' . htmlspecialchars($induk['kdbarang']) . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="nmbarang">Nama Product <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nmbarang" id="nmbarang" value="<?= htmlspecialchars($row['nmbarang']) ?>" required>
                </div>

                <div class="form-group">
                  <label for="cut">Kategori <span class="text-danger">*</span></label>
                  <select class="form-control" name="cut" id="cut" required>
                    <option value="" disabled>Pilih Kategori</option>
                    <?php
                    $query = mysqli_query($conn, "SELECT idcut, nmcut FROM cuts ORDER BY nmcut ASC");
                    while ($cutRow = mysqli_fetch_assoc($query)) {
                      $selected = ($cutRow['idcut'] == $row['idcut']) ? 'selected' : '';
                      echo "<option value=\"{$cutRow['idcut']}\" $selected>{$cutRow['nmcut']}</option>";
                    }
                    ?>
                  </select>
                </div>

              </div>

              <div class="form-group text-right mr-3">
                <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-level-up-alt"></i> Update</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const tipeBarang = document.getElementById('tipebarang');
    const kodeBarang = document.getElementById('kdbarang');
    const parentContainer = document.getElementById('parentContainer');
    const kodeIndukSelect = document.getElementById('kodeinduk');

    function toggleFields() {
      if (tipeBarang.value === 'turunan') {
        parentContainer.classList.remove('d-none');
        kodeIndukSelect.setAttribute('required', 'required');
        kodeBarang.setAttribute('readonly', 'readonly');
      } else {
        parentContainer.classList.add('d-none');
        kodeIndukSelect.removeAttribute('required');
        kodeIndukSelect.value = '';
        kodeBarang.removeAttribute('readonly');
      }
    }

    tipeBarang.addEventListener('change', toggleFields);

    // Jalankan saat load
    toggleFields();
  });
</script>

<?php
include "../footer.php";
include "../footnote.php";
?>