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
?>
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="prosesinvoice.php">
                  <input type="hidden" value="<?= $kodeauto ?>" name="invnumber" id="invnumber">
                  <input type="hidden" value="<?= $iddo ?>" name="iddo" id="iddo">
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
                                 <label for="idcustomer">Nama Customer</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="idcustomer" id="idcustomer" value="" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="po">PO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="po" id="po" value="" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="donumber">DO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="donumber" id="donumber" value="" readonly>
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
                                    <label for="idgrade">Code</label>
                                 </div>
                              </div>
                              <div class="col">
                                 <div class="form-group">
                                    <label for="idbarang">Products</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="weight">Weight</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="price">Price <span class="text-danger">*</span></label>
                                 </div>
                              </div>
                              <div class="col-1">
                                 <div class="form-group">
                                    <label for="discount">Disc %</label>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <label for="amount">Amount</label>
                                 </div>
                              </div>
                           </div>
                           <!-- looping data dodetail -->
                           <div class="row">
                              <div class="col-1">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" class="form-control" name="idgrade" id="idgrade" value="" readonly>
                                    </div>
                                 </div>
                              </div>
                              <div class="col">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" class="form-control" name="idbarang" id="idbarang" value="" readonly>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" class="form-control" name="weight" id="weight" value="" readonly>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" class="form-control" name="price" id="price" required>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-1">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" class="form-control" name="discount" id="discount">
                                    </div>
                                 </div>
                              </div>
                              <div class="col-2">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="text" class="form-control" name="amount" id="amount" readonly>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- akhir looping data dodetail -->
                        </div>
                        <div class="row">
                           <div class="col-5"></div>
                           <div class="col-2">
                              <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-10 text-right">
                              Total Amount
                           </div>
                           <div class="col-2">
                              <input type="text" name="xamount" id="xamount" class="form-control text-right" readonly>
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Charge
                           </div>
                           <div class="col-2">
                              <input type="text" name="charge" id="charge" class="form-control text-right">
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Tax 11%
                           </div>
                           <div class="col-2">
                              <input type="text" name="tax" id="tax" class="form-control text-right" readonly>
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Down Payment
                           </div>
                           <div class="col-2">
                              <input type="text" name="dp" id="dp" class="form-control text-right">
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Balance
                           </div>
                           <div class="col-2">
                              <input type="text" name="balance" id="balance" class="form-control text-right" readonly>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-3">
                              <button type="button" class="btn btn-block bg-gradient-warning" onclick="calculateTotals()">Calculate</button>
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
   function calculateTotals() {
      // Aktifkan tombol Submit setelah mengklik Calculate
      document.getElementById("submit-btn").disabled = false;
   }
   // Mengubah judul halaman web
   document.title = "<?= $kodeauto ?>";
</script>

<?php
// require "../footnotes.php";
include "../footer.php";
?>