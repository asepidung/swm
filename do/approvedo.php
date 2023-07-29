<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
$iddo = isset($_GET['iddo']) ? intval($_GET['iddo']) : 0;
if ($iddo <= 0) {
   die("ID DO tidak valid.");
}
$querydo = "SELECT do.*, customers.nama_customer, customers.catatan, segment.idsegment
            FROM do
            INNER JOIN customers ON do.idcustomer = customers.idcustomer
            INNER JOIN segment ON customers.idsegment = segment.idsegment
            WHERE do.iddo = $iddo";
$resultdo = mysqli_query($conn, $querydo);
$row = mysqli_fetch_assoc($resultdo);

$querydodetail = "SELECT dodetail.*, grade.nmgrade, barang.nmbarang, barang.kdbarang
                  FROM dodetail
                  INNER JOIN grade ON dodetail.idgrade = grade.idgrade
                  INNER JOIN barang ON dodetail.idbarang = barang.idbarang
                  WHERE dodetail.iddo = $iddo";
$resultdodetail = mysqli_query($conn, $querydodetail);
?>
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="inputdo.php">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label>Tgl Kirim</label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" value="<?= $row['deliverydate'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label>Customer </label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $row['nama_customer'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label>Cust PO</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $row['po'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="driver">Driver</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" <?= $row['driver'] ?> readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label>Plat Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $row['plat'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $row['catatan'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div id="items-container">
                           <div class="row">
                              <div class="col-1">
                                 <div class="form-group">
                                    <label>Code</label>
                                 </div>
                              </div>
                              <div class="col-4">
                                 <div class="form-group">
                                    <label>Product</label>
                                 </div>
                              </div>
                              <div class="col-1">
                                 <div class="form-group">
                                    <label>Box</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label>Weight</label>
                                 </div>
                              </div>
                              <div class="col-3">
                                 <div class="form-group">
                                    <label>Notes</label>
                                 </div>
                              </div>
                           </div>
                           <?php while ($rowdodetail = mysqli_fetch_assoc($resultdodetail)) { ?>
                              <div class="row mt-n2">
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" value="<?= $rowdodetail['nmgrade'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-4">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control" value="<?= $rowdodetail['nmbarang'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" value="<?= $rowdodetail['box'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control  text-right" value="<?= number_format($rowdodetail['weight'], 2) ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control" value="<?= $rowdodetail['notes'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           <?php } ?>
                        </div>
                        <div class="row">
                           <div class="col-5"></div>
                           <div class="col-1">
                              <input type="text" name="xbox" id="xbox" class="form-control  text-center" value="<?= $row['xbox'] ?>" readonly>
                           </div>
                           <div class="col-2">
                              <input type="text" name="xweight" id="xweight" class="form-control text-right" value="<?= number_format($row['xweight'], 2) ?>" readonly>
                           </div>
                           <div class="col ml-1">
                              <button type="submit" name="approve" class="btn btn-block btn-success" onclick="return confirm('Pastikan semua data sudah sesuai')">Approve</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <!-- /.card -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
   document.title = "<?= $kodeauto ?>";
</script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>