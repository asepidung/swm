<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";
$idreturjual = $_GET['idreturjual'];
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
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-3">
               <a href="index.php"><button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-arrow-alt-circle-left"></i> Kembali</button></a>
            </div>
            <div class="col">
               <span class="text-danger float-right">
                  <h4>LABEL BAHAN RETUR</h4>
               </span>
            </div>
         </div>
      </div>
   </div>
   <div class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-4">
               <div class="card">
                  <div class="card-body">
                     <form method="POST" action="printlabel.php" onsubmit="submitForm(event)">
                        <input type="hidden" name="idreturjual" value="<?= $idreturjual; ?>">
                        <div class="form-group">
                           <label>Product <span class="text-danger">*</span></label>
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
                           <label>Grade <span class="text-danger">*</span></label>
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
                           <label>Packed Date<span class="text-danger">*</span></label>
                           <div class="input-group">
                              <?php
                              // Set the default value of $_SESSION['packdate'] to today's date
                              if (!isset($_SESSION['packdate']) || $_SESSION['packdate'] == '') {
                                 $_SESSION['packdate'] = date('Y-m-d'); // Set the format according to your needs
                              }
                              ?>
                              <input type="date" class="form-control" name="packdate" id="packdate" required value="<?= $_SESSION['packdate']; ?>">
                           </div>
                        </div>
                        <div class="form-group">
                           <label>Expired Date</label>
                           <div class="input-group">
                              <input type="date" class="form-control" name="exp" id="exp" value="<?= isset($_SESSION['exp']) ? $_SESSION['exp'] : ''; ?>">
                           </div>
                        </div>
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" checked name="tenderstreach" id="tenderstreach" <?php echo isset($_SESSION['tenderstreach']) && $_SESSION['tenderstreach'] ? 'checked' : ''; ?>>
                           <label class="form-check-label">Aktifkan Tenderstreatch</label>
                        </div>
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="pembulatan" id="pembulatan" <?php echo isset($_SESSION['pembulatan']) && $_SESSION['pembulatan'] ? 'checked' : ''; ?>>
                           <label class="form-check-label">1 Digit Koma</label>
                        </div>
                        <div class="form-group">
                           <label class="mt-2">Weight & Pcs <span class="text-danger">*</span></label>
                           <div class="input-group col">
                              <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" required>
                           </div>
                        </div>
                        <button type="submit" class="btn btn-block bg-gradient-primary" name="submit">Print</button>
                     </form>
                  </div>
               </div>
            </div>
            <div class="col-md-8">
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
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT returjualdetail.*, barang.nmbarang, grade.nmgrade
                              FROM returjualdetail
                              INNER JOIN barang ON returjualdetail.idbarang = barang.idbarang
                              INNER JOIN grade ON returjualdetail.idgrade = grade.idgrade
                              WHERE idreturjual = $idreturjual ORDER BY idreturjualdetail DESC");
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
                                    $idreturjual = $tampil['idreturjual'];

                                    // Query untuk memeriksa keberadaan kdbarcode di tabel tallydetail
                                    $queryCheckExistence = "SELECT COUNT(*) as count FROM tallydetail WHERE barcode = '$kdbarcode'";
                                    $resultCheckExistence = mysqli_query($conn, $queryCheckExistence);
                                    $rowCheckExistence = mysqli_fetch_assoc($resultCheckExistence);

                                    // Jika kdbarcode sudah ada di tabel tallydetail, tampilkan ikon abu-abu
                                    if ($rowCheckExistence['count'] > 0) {
                                    ?>
                                       <i class="fas fa-poo fa-lg"></i>
                                    <?php
                                    } else {
                                       // Jika kdbarcode belum ada di tabel tallydetail, tampilkan tautan penghapusan
                                    ?>
                                       <a href="deletedetailretur.php?iddetail=<?= $tampil['idreturjualdetail']; ?>&id=<?= $idreturjual; ?>" class="text-danger" onclick="return confirm('Yakin?')">
                                          <i class="far fa-times-circle"></i>
                                       </a>
                                    <?php
                                    }
                                    ?>
                                 </td>
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
         </div>
      </div>
      <!-- /.container-fluid -->
   </div>
</div>
<script>
   document.title = "RETUR PENJUALAN";
</script>
<?php
// require "../footnote.php";
require "../footer.php";
?>