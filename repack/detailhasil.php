<?php
require "../verifications/auth.php";
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
                     <input type="hidden" name="idrepack" value="<?= (int)$idrepack; ?>">

                     <!-- Origin -->
                     <div class="form-group">
                        <div class="input-group">
                           <select class="form-control" name="origin" id="origin" required>
                              <option value="">** Pilih Asal Barang **</option>
                              <option value="3" <?= (!empty($_SESSION['origin']) && $_SESSION['origin'] == 3) ? 'selected' : ''; ?>>REPACK</option>
                              <option value="2" <?= (!empty($_SESSION['origin']) && $_SESSION['origin'] == 2) ? 'selected' : ''; ?>>TRADING</option>
                              <option value="5" <?= (!empty($_SESSION['origin']) && $_SESSION['origin'] == 5) ? 'selected' : ''; ?>>IMPORT</option>
                           </select>
                        </div>
                     </div>

                     <!-- Item -->
                     <div class="form-group">
                        <div class="input-group">
                           <?php $selectedIdbarang = $_SESSION['idbarang'] ?? ''; ?>
                           <select class="form-control" name="idbarang" id="idbarang" required autofocus>
                              <?php
                              if ($selectedIdbarang !== '') {
                                 echo "<option value=\"" . htmlspecialchars($selectedIdbarang, ENT_QUOTES) . "\" selected>--Pilih Item--</option>";
                              } else {
                                 echo '<option value="" selected>--Pilih Item--</option>';
                              }
                              $resBarang = mysqli_query($conn, "SELECT idbarang,nmbarang FROM barang ORDER BY nmbarang ASC");
                              while ($r = mysqli_fetch_assoc($resBarang)) {
                                 $idb = $r['idbarang'];
                                 $nm  = $r['nmbarang'];
                                 $sel = ($idb == $selectedIdbarang) ? 'selected' : '';
                                 echo "<option value=\"" . $idb . "\" $sel>" . htmlspecialchars($nm, ENT_QUOTES) . "</option>";
                              }
                              ?>
                           </select>
                           <div class="input-group-append">
                              <a href="../barang/newbarang.php" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                           </div>
                        </div>
                     </div>

                     <!-- Grade -->
                     <div class="form-group">
                        <div class="input-group">
                           <?php $selectedIdgrade = $_SESSION['idgrade'] ?? ''; ?>
                           <select class="form-control" name="idgrade" id="idgrade" required>
                              <?php
                              if ($selectedIdgrade !== '') {
                                 echo "<option value=\"" . htmlspecialchars($selectedIdgrade, ENT_QUOTES) . "\" selected>--Pilih Grade--</option>";
                              } else {
                                 echo '<option value="" selected>--Pilih Grade--</option>';
                              }
                              $resGrade = mysqli_query($conn, "SELECT idgrade,nmgrade FROM grade ORDER BY nmgrade ASC");
                              while ($g = mysqli_fetch_assoc($resGrade)) {
                                 $idg = $g['idgrade'];
                                 $nm  = $g['nmgrade'];
                                 $sel = ($idg == $selectedIdgrade) ? 'selected' : '';
                                 echo "<option value=\"" . $idg . "\" $sel>" . htmlspecialchars($nm, ENT_QUOTES) . "</option>";
                              }
                              ?>
                           </select>
                        </div>
                     </div>
                     <!-- Packdate -->
                     <div class="form-group mini-field">
                        <span class="mini-label">Prod</span>
                        <div class="input-group">
                           <?php if (empty($_SESSION['packdate'])) $_SESSION['packdate'] = date('Y-m-d'); ?>
                           <input type="date"
                              class="form-control"
                              name="packdate"
                              id="packdate"
                              required
                              value="<?= htmlspecialchars($_SESSION['packdate'], ENT_QUOTES); ?>">
                        </div>
                     </div>

                     <!-- Exp di baris sendiri -->
                     <div class="form-group mini-field">
                        <span class="mini-label">Exp</span>
                        <div class="input-group">
                           <?php if (!isset($_SESSION['exp'])) $_SESSION['exp'] = ''; ?>
                           <input type="date"
                              class="form-control"
                              name="exp"
                              id="exp"
                              value="<?= htmlspecialchars($_SESSION['exp'], ENT_QUOTES); ?>">
                        </div>
                     </div>


                     <!-- Catatan -->
                     <div class="form-group">
                        <div class="input-group">
                           <input type="text" class="form-control" name="note" id="note" placeholder="Catatan Item"
                              value="<?= isset($_SESSION['note']) ? htmlspecialchars($_SESSION['note'], ENT_QUOTES) : '' ?>">
                        </div>
                     </div>

                     <!-- Qty (Weight/Pcs) + pH dalam satu baris -->
                     <div class="form-group mt-1">
                        <div class="row">
                           <div class="col-6">
                              <input type="text"
                                 class="form-control"
                                 name="qty" id="qty"
                                 placeholder="Weight / Pcs (cth: 12.34/5)"
                                 value="<?= isset($_SESSION['qty']) ? htmlspecialchars($_SESSION['qty'], ENT_QUOTES) : '' ?>"
                                 required>
                           </div>
                           <div class="col-6">
                              <input type="number"
                                 step="0.1" min="5.4" max="5.7"
                                 class="form-control"
                                 name="ph" id="ph"
                                 placeholder="pH 5.4–5.7"
                                 value="<?= isset($_SESSION['ph']) ? htmlspecialchars($_SESSION['ph'], ENT_QUOTES) : '' ?>" required>
                           </div>
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
                           <th>pH</th>
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
                              WHERE idrepack = $idrepack AND detailhasil.is_deleted = 0
                              ORDER BY iddetailhasil DESC");
                        while ($tampil = mysqli_fetch_array($ambildata)) { ?>
                           <tr class="text-center">
                              <td><?= $no; ?></td>
                              <td><?= $tampil['kdbarcode']; ?></td>
                              <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                              <td><?= $tampil['nmgrade']; ?></td>
                              <td class="text-right"><?= number_format($tampil['qty'], 2); ?></td>
                              <?php
                              if ($tampil['pcs'] < 1) {
                                 $pcs = "";
                              } else {
                                 $pcs = $tampil['pcs'];
                              }
                              ?>
                              <td><?= $pcs; ?></td>
                              <td>
                                 <!-- <?= date("H:i:s", strtotime($tampil['creatime'])); ?> -->
                                 <?= $tampil['ph']; ?>
                              </td>
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
                           <th><?= isset($rowhasil['hasilqty']) ? number_format($rowhasil['hasilqty'], 2) : '0.00'; ?></th>
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
                        $query = "SELECT detailhasil.idbarang, barang.nmbarang, 
                                 SUM(detailhasil.qty) AS total_qty, 
                                 COUNT(detailhasil.qty) AS count_qty
                                 FROM detailhasil
                                 INNER JOIN barang ON detailhasil.idbarang = barang.idbarang
                                 WHERE detailhasil.idrepack = $idrepack AND detailhasil.is_deleted = 0
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
                                       WHERE detailhasil.idrepack = $idrepack AND detailhasil.is_deleted = 0";
                        $resulthasil = mysqli_query($conn, $queryhasil);
                        $rowhasil = mysqli_fetch_assoc($resulthasil);
                        ?>
                        <tr class="text-right">
                           <th>TOTAL</th>
                           <th class="text-center"><?= $rowhasil['hasilbox']; ?></th>
                           <th><?= isset($rowhasil['hasilqty']) ? number_format($rowhasil['hasilqty'], 2) : '0.00'; ?></th>
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
<style>
   .mini-field {
      position: relative;
   }

   .mini-field .mini-label {
      position: absolute;
      left: 10px;
      top: 6px;
      font-size: 10px;
      line-height: 1;
      color: #6c757d;
      /* abu-abu bootstrap */
      pointer-events: none;
      z-index: 2;
      background: #fff;
      /* agar kontras di atas input putih */
      padding: 0 2px;
      border-radius: 2px;
   }

   /* beri padding kiri agar teks input tidak tabrakan dgn label mini */
   .mini-field input.form-control {
      padding-left: 48px !important;
   }
</style>

<?php
// require "../footnote.php";
require "../footer.php";
?>