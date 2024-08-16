<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "../header.php";
$idrepack = $_GET['id'];
// Mengambil daftar barang
$query = "SELECT * FROM barang ORDER BY nmbarang ASC";
$result = mysqli_query($conn, $query);
$barangOptions = "";
while ($row = mysqli_fetch_assoc($result)) {
   $idbarang = $row['idbarang'];
   $nmbarang = $row['nmbarang'];
   $barangOptions .= "<option value=\"$idbarang\">$nmbarang</option>";
}
?>
<div class="content-header">
   <div class="container-fluid">
      <div class="row">
         <div class="col-3">
            <a href="index.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</button></a>
            <a href="detailbahan.php?id=<?= $idrepack ?>&stat=ready"><button type="button" class="btn btn-outline-success">Tambah Bahan <i class="fas fa-arrow-alt-circle-right"></i></button></a>
         </div>
         <div class="col">
            <span class="text-primary">
               <h4>PRINT LABEL HASIL</h4>
            </span>
         </div>
      </div>
   </div>
</div>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-3">
            <div class="card">
               <div class="card-body">
                  <form method="POST" action="printlabel.php" onsubmit="submitForm(event)">
                     <input type="hidden" name="idrepack" value="<?= $idrepack; ?>">
                     <div class="form-group">
                        <div class="input-group">
                           <select class="form-control" name="origin" id="origin" required>
                              <option value="">** Pilih Asal Barang **</option>
                              <option value="3" <?= (isset($_SESSION['origin']) && $_SESSION['origin'] == 3) ? 'selected' : ''; ?>>REPACK</option>
                              <option value="2" <?= (isset($_SESSION['origin']) && $_SESSION['origin'] == 2) ? 'selected' : ''; ?>>TRADING</option>
                              <option value="5" <?= (isset($_SESSION['origin']) && $_SESSION['origin'] == 5) ? 'selected' : ''; ?>>IMPORT</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <select class="form-control" name="idbarang" id="idbarang" required autofocus>
                              <?php
                              if (isset($_SESSION['idbarang']) && $_SESSION['idbarang'] != '') {
                                 $selectedIdbarang = $_SESSION['idbarang'];
                                 echo "<option value=\"$selectedIdbarang\" selected>--Pilih Item--</option>";
                              } else {
                                 echo '<option value="" selected>--Pilih Item--</option>';
                              }
                              $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                              $result = mysqli_query($conn, $query);
                              while ($row = mysqli_fetch_assoc($result)) {
                                 $idbarang = $row['idbarang'];
                                 $nmbarang = $row['nmbarang'];
                                 $selected = ($idbarang == $selectedIdbarang) ? 'selected' : '';
                                 echo "<option value=\"$idbarang\" $selected>$nmbarang</option>";
                              }
                              ?>
                           </select>
                           <div class="input-group-append">
                              <a href="../barang/newbarang.php" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <select class="form-control" name="idgrade" id="idgrade" required>
                              <?php
                              if (isset($_SESSION['idgrade']) && $_SESSION['idgrade'] != '') {
                                 $selectedIdgrade = $_SESSION['idgrade'];
                                 echo "<option value=\"$selectedIdgrade\" selected>--Pilih Grade--</option>";
                              } else {
                                 echo '<option value="" selected>--Pilih Grade--</option>';
                              }
                              $query = "SELECT * FROM grade ORDER BY nmgrade ASC";
                              $result = mysqli_query($conn, $query);
                              while ($row = mysqli_fetch_assoc($result)) {
                                 $idgrade = $row['idgrade'];
                                 $nmgrade = $row['nmgrade'];
                                 $selected = ($idgrade == $selectedIdgrade) ? 'selected' : '';
                                 echo "<option value=\"$idgrade\" $selected>$nmgrade</option>";
                              }
                              ?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <?php
                           if (!isset($_SESSION['packdate']) || $_SESSION['packdate'] == '') {
                              $_SESSION['packdate'] = date('Y-m-d');
                           }
                           ?>
                           <input type="date" class="form-control" name="packdate" id="packdate" required value="<?= $_SESSION['packdate']; ?>">
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <input type="text" class="form-control" name="note" id="note" placeholder="Catatan Item">
                        </div>
                     </div>
                     <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tenderstreach" id="tenderstreach" <?php echo isset($_SESSION['tenderstreach']) && $_SESSION['tenderstreach'] ? 'checked' : ''; ?>>
                        <label class="form-check-label">Aktifkan Tenderstreatch</label>
                     </div>
                     <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pembulatan" id="pembulatan" <?php echo isset($_SESSION['pembulatan']) && $_SESSION['pembulatan'] ? 'checked' : ''; ?>>
                        <label class="form-check-label">1 Digit Koma</label>
                     </div>
                     <div class="form-group mt-1">
                        <div class="input-group">
                           <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" required>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-block bg-gradient-primary" name="submit">Print</button>
                  </form>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="card">
               <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped table-sm">
                     <thead class="text-center">
                        <tr>
                           <th>#</th>
                           <th>Barcode</th>
                           <th>Product</th>
                           <th>Kode</th>
                           <th>Qty</th>
                           <th>Pcs</th>
                           <th>Create</th>
                           <th>Hapus</th>
                           <th>Note</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        $no = 1;
                        $ambildata = mysqli_query($conn, "SELECT detailhasil.*, barang.nmbarang, grade.nmgrade
                              FROM detailhasil
                              INNER JOIN barang ON detailhasil.idbarang = barang.idbarang
                              INNER JOIN grade ON detailhasil.idgrade = grade.idgrade
                              WHERE idrepack = $idrepack ORDER BY iddetailhasil DESC");
                        while ($tampil = mysqli_fetch_array($ambildata)) { ?>
                           <tr class="text-center">
                              <td><?= $no; ?></td>
                              <td><?= $tampil['kdbarcode']; ?></td>
                              <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                              <td><?= $tampil['nmgrade']; ?></td>
                              <td><?= $tampil['qty']; ?></td>
                              <?php
                              if ($tampil['pcs'] < 1) {
                                 $pcs = "";
                              } else {
                                 $pcs = $tampil['pcs'];
                              }
                              ?>
                              <td><?= $pcs; ?></td>
                              <td><?= date("H:i:s", strtotime($tampil['creatime'])); ?></td>
                              <td class="text-center">
                                 <?php
                                 $kdbarcode = $tampil['kdbarcode'];
                                 $idrepack = $tampil['idrepack'];
                                 $queryCheckExistence = "SELECT COUNT(*) as count FROM tallydetail WHERE barcode = '$kdbarcode'";
                                 $resultCheckExistence = mysqli_query($conn, $queryCheckExistence);
                                 $rowCheckExistence = mysqli_fetch_assoc($resultCheckExistence);

                                 // Jika kdbarcode sudah ada di tabel tallydetail, tampilkan ikon abu-abu
                                 if ($rowCheckExistence['count'] > 0) {
                                 ?>
                                    <i class="far fa-check-circle"></i>
                                 <?php
                                 } else {
                                    // Jika kdbarcode belum ada di tabel tallydetail, tampilkan tautan penghapusan
                                 ?>
                                    <a href="deletedetailhasil.php?iddetail=<?= $tampil['iddetailhasil']; ?>&id=<?= $idrepack; ?>" class="text-danger" onclick="return confirm('Yakin?')">
                                       <i class="far fa-times-circle"></i>
                                    </a>
                                 <?php
                                 }
                                 ?>
                              </td>
                              <td><?= $tampil['note']; ?></td>
                           </tr>
                        <?php
                           $no++;
                        }
                        ?>
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- /.card -->
         </div>
         <div class="col-md-3">
            <div class="card">
               <div class="card-body">
                  <strong>BAHAN</strong>
                  <table class="table table-bordered table-striped table-sm mb-3">
                     <thead class="text-center">
                        <tr>
                           <th>NAMA BARANG</th>
                           <th>BOX</th>
                           <th>QTY</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        $query = "SELECT detailbahan.idbarang, barang.nmbarang, SUM(detailbahan.qty) AS total_qty, COUNT(detailbahan.qty) AS count_qty
                              FROM detailbahan
                              INNER JOIN barang ON detailbahan.idbarang = barang.idbarang
                              WHERE detailbahan.idrepack = $idrepack
                              GROUP BY detailbahan.idbarang, barang.nmbarang";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                           <tr>
                              <td><?= $row['nmbarang'] ?></td>
                              <td class="text-center"><?= $row['count_qty'] ?></td>
                              <td class="text-right"><?= number_format($row['total_qty'], 2) ?></td>
                           </tr>
                        <?php }
                        ?>
                     </tbody>
                     <tfoot>
                        <?php
                        $queryhasil = "SELECT SUM(detailbahan.qty) AS hasilqty, COUNT(detailbahan.qty) AS hasilbox
                              FROM detailbahan
                              WHERE detailbahan.idrepack = $idrepack";
                        $resulthasil = mysqli_query($conn, $queryhasil);
                        $rowhasil = mysqli_fetch_assoc($resulthasil);
                        ?>
                        <tr class="text-right">
                           <th>TOTAL</th>
                           <th class="text-center"><?= $rowhasil['hasilbox']; ?></th>
                           <th><?= number_format($rowhasil['hasilqty'], 2); ?></th>
                        </tr>
                     </tfoot>
                  </table>
                  <strong>HASIL</strong>
                  <table class="table table-bordered table-striped table-sm">
                     <thead class="text-center">
                        <tr>
                           <th>NAMA BARANG</th>
                           <th>BOX</th>
                           <th>QTY</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        $query = "SELECT detailhasil.idbarang, barang.nmbarang, SUM(detailhasil.qty) AS total_qty, COUNT(detailhasil.qty) AS count_qty
                              FROM detailhasil
                              INNER JOIN barang ON detailhasil.idbarang = barang.idbarang
                              WHERE detailhasil.idrepack = $idrepack
                              GROUP BY detailhasil.idbarang, barang.nmbarang";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                           <tr>
                              <td><?= $row['nmbarang'] ?></td>
                              <td class="text-center"><?= $row['count_qty'] ?></td>
                              <td class="text-right"><?= number_format($row['total_qty'], 2) ?></td>
                           </tr>
                        <?php }
                        ?>
                     </tbody>
                     <tfoot>
                        <?php
                        $queryhasil = "SELECT SUM(detailhasil.qty) AS hasilqty, COUNT(detailhasil.qty) AS hasilbox
                              FROM detailhasil
                              WHERE detailhasil.idrepack = $idrepack";
                        $resulthasil = mysqli_query($conn, $queryhasil);
                        $rowhasil = mysqli_fetch_assoc($resulthasil);
                        ?>
                        <tr class="text-right">
                           <th>TOTAL</th>
                           <th class="text-center"><?= $rowhasil['hasilbox']; ?></th>
                           <th><?= number_format($rowhasil['hasilqty'], 2); ?></th>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</div>
<script>
   document.title = "CETAK HASIL REPACK";
   document.addEventListener('DOMContentLoaded', function() {
      // Menggunakan event listener untuk menangkap event keydown pada elemen dengan id "idbarang"
      document.getElementById('idbarang').addEventListener('keydown', function(e) {
         // Jika tombol yang ditekan adalah "Tab" (kode 9)
         if (e.keyCode === 9) {
            // Pindahkan fokus ke elemen dengan id "qty"
            document.getElementById('qty').focus();
            // Mencegah perpindahan fokus bawaan yang dihasilkan oleh tombol "Tab"
            e.preventDefault();
         }
      });
   });
</script>
<?php
// require "../footnote.php";
require "../footer.php";
?>