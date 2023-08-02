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
               <form method="POST" action="prosesapprovedo.php">
                  <input type="hidden" name="iddo" value="<?= $iddo ?>">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-2">
                              <div class="form-group">
                                 <label>Tgl Kirim</label>
                                 <div class="input-group">
                                    <input type="date" name="deliverydate" class="form-control" value="<?= $row['deliverydate'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col-3">
                              <div class="form-group">
                                 <label>Customer </label>
                                 <div class="input-group">
                                    <input type="hidden" name="idcustomer" value="<?= $row['idcustomer'] ?>">
                                    <input type="text" class="form-control" value="<?= $row['nama_customer'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col-3">
                              <div class="form-group">
                                 <label>Cust PO</label>
                                 <div class="input-group">
                                    <input type="text" name="po" class="form-control" value="<?= $row['po'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label>DO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="donumber" value="<?= $row['donumber'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-2">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" name="driver" class="form-control" value="<?= $row['driver'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col-3">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" name="plat" class="form-control" value="<?= $row['plat'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" name="note" class="form-control" value="<?= $row['catatan'] ?>" readonly>
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
                              <div class="col-3">
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
                              <div class="col">
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
                                          <input type="hidden" name="idgrade" value="<?= $rowdodetail['idgrade'] ?>">
                                          <input type="text" class="form-control text-center" value="<?= $rowdodetail['nmgrade'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-3">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="hidden" name="idbarang" value="<?= $rowdodetail['idbarang'] ?>">
                                          <input type="text" class="form-control" value="<?= $rowdodetail['nmbarang'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" name="box[]" class="form-control text-center" value="<?= $rowdodetail['box'] ?>" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" name="weight[]" class="form-control text-right" value="<?= $rowdodetail['weight'] ?>" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" name="notes" class="form-control" placeholder="<?= $rowdodetail['notes'] ?>">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           <?php } ?>
                        </div>
                        <div class="row">
                           <div class="col-2">
                              <button type="button" class="btn btn-block btn-warning" id="calculate-btn">Calculate</button>
                           </div>
                           <div class="col-2">
                              <button type="submit" disabled name="approve" id="submit-btn" class="btn btn-block btn-success" onclick="return confirm('Pastikan semua data sudah sesuai')">Receipt</button>
                           </div>
                           <div class="col-1">
                              <input type="text" name="xbox" id="xbox" class="form-control text-center" readonly>
                           </div>
                           <div class="col-2">
                              <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly>
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
   function calculateTotal() {
      const boxInputs = document.getElementsByName("box[]");
      const weightInputs = document.getElementsByName("weight[]");
      let totalBox = 0;
      let totalWeight = 0;

      for (let i = 0; i < boxInputs.length; i++) {
         const boxValue = parseInt(boxInputs[i].value) || 0;
         const weightValue = parseFloat(weightInputs[i].value) || 0;
         totalBox += boxValue;
         totalWeight += weightValue;
      }

      document.getElementById("xbox").value = totalBox;
      document.getElementById("xweight").value = totalWeight.toFixed(2);
      // Aktifkan tombol Submit setelah mengklik Calculate
      document.getElementById("submit-btn").disabled = false;
   }

   // Attach click event to the Calculate button
   const calculateBtn = document.getElementById("calculate-btn");
   calculateBtn.addEventListener("click", calculateTotal);
</script>
<?php
// require "../footnotes.php";
include "../footer.php";
?>