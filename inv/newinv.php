<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "invnumber.php";
$iddo = $_GET['iddo'];

// Mengambil data dari tabel do
$queryDo = "SELECT do.*, customers.nama_customer, customers.pajak, customers.top, customers.tukarfaktur, segment.idsegment
            FROM do
            INNER JOIN customers ON do.idcustomer = customers.idcustomer
            INNER JOIN segment ON customers.idsegment = segment.idsegment
            WHERE do.iddo = $iddo";
$resultDo = mysqli_query($conn, $queryDo);
$rowDo = mysqli_fetch_assoc($resultDo);
$tukarfaktur = $rowDo['tukarfaktur'];
$pajak = $rowDo['pajak'];
$top = $rowDo['top'];
$idsegment = $rowDo['idsegment'];
// Mengambil data dari tabel dodetail, grade, dan barang
$queryDodetail = "SELECT dodetail.*, grade.nmgrade, barang.nmbarang, barang.kdbarang
                  FROM dodetail
                  INNER JOIN grade ON dodetail.idgrade = grade.idgrade
                  INNER JOIN barang ON dodetail.idbarang = barang.idbarang
                  WHERE dodetail.iddo = $iddo";
$resultDodetail = mysqli_query($conn, $queryDodetail);
?>
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="prosesinvoice.php">
                  <input type="hidden" value="<?= $noinvoice ?>" name="noinvoice" id="noinvoice">
                  <input type="hidden" value="<?= $iddo ?>" name="iddo" id="iddo">
                  <input type="hidden" value="<?= $idsegment; ?>" name="idsegment" id="idsegment">
                  <input type="hidden" value="<?= $top; ?>" name="top">
                  <input type="hidden" value="<?= $pajak; ?>" name="pajak">
                  <input type="hidden" value="<?= $tukarfaktur; ?>" name="tukarfaktur" id="tukarfaktur">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="invoice_date" id="invoice_date" required>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="nama_customer">Nama Customer</label>
                                 <div class="input-group">
                                    <input type="hidden" name="idcustomer" id="idcustomer" value="<?= $rowDo['idcustomer'] ?>">
                                    <input type="text" class="form-control" name="nama_customer" id="nama_customer" value="<?= $rowDo['nama_customer'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="pocustomer">PO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="pocustomer" id="pocustomer" value="<?= $rowDo['po'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="donumber">DO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="donumber" id="donumber" value="<?= $rowDo['donumber'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="keterangan">
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
                              <div class="col">
                                 <div class="form-group">
                                    <label>Products</label>
                                 </div>
                              </div>
                              <div class="col-1">
                                 <div class="form-group">
                                    <label>Weight</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label>Price <span class="text-danger">*</span></label>
                                 </div>
                              </div>
                              <div class="col-1">
                                 <div class="form-group">
                                    <label>Disc %</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label>Disc Rp</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label>Amount</label>
                                 </div>
                              </div>
                           </div>
                           <?php while ($rowDodetail = mysqli_fetch_assoc($resultDodetail)) { ?>
                              <div class="row mt-n2">
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" name="nmgrade" id="nmgrade" value="<?= $rowDodetail['nmgrade'] ?>" readonly>
                                          <input type="hidden" class="form-control text-center" name="idgrade[]" id="idgrade" value="<?= $rowDodetail['idgrade'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control" name="nmbarang" id="nmbarang" value="<?= $rowDodetail['nmbarang'] ?>" readonly>
                                          <input type="hidden" class="form-control" name="idbarang[]" id="idbarang" value="<?= $rowDodetail['idbarang'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" name="weight[]" value="<?= $rowDodetail['weight'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" name="price[]" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" name="discount[]" value="0">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" name="discountrp[]" value="0" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" name="amount[]" readonly value="0">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           <?php } ?>
                        </div>
                        <div class="row">
                           <div class="col-4 text-right">Weight Total</div>
                           <div class="col-1">
                              <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly>
                           </div>
                           <div class="col-5 text-right">
                              Total Amount
                           </div>
                           <div class="col-2">
                              <input type="text" name="xamount" id="xamount" class="form-control text-right" readonly value="0">
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Tax 11%
                           </div>
                           <div class="col-2">
                              <input type="text" name="tax" id="tax" class="form-control text-right" readonly value="0">
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Charge
                           </div>
                           <div class="col-2">
                              <input type="text" name="charge" id="charge" class="form-control text-right" value="0">
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Down Payment
                           </div>
                           <div class="col-2">
                              <input type="text" name="downpayment" id="downpayment" class="form-control text-right" value="0">
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Balance
                           </div>
                           <div class="col-2">
                              <input type="text" name="balance" id="balance" class="form-control text-right" readonly value="0">
                              <input type="hidden" name="xdiscount" id="xdiscount" value="0">
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-3">
                              <button type="button" class="btn btn-block bg-gradient-warning" onclick="calculateAmounts()">Calculate</button>
                           </div>
                           <div class="col-3">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')" disabled id="submit-btn">Submit</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <!-- /.card -->
         </div>
      </div>
   </section>
</div>
<script src="../dist/js/hitunginvoice.js"></script>
<script>
   document.title = "<?= $noinvoice ?>";
</script>
<?php
// require "../footnotes.php";
include "../footer.php";
?>