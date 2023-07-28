<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$idinvoice = $_GET['idinvoice'];

// Query untuk mengambil data dari tabel invoice berdasarkan idinvoice
$query_invoice = "SELECT * FROM invoice WHERE idinvoice = '$idinvoice'";
$result_invoice = mysqli_query($conn, $query_invoice);
$row_invoice = mysqli_fetch_assoc($result_invoice);

// Query untuk mengambil data dari tabel invoicedetail berdasarkan idinvoice
$query_invoicedetail = "SELECT * FROM invoicedetail WHERE idinvoice = '$idinvoice'";
$result_invoicedetail = mysqli_query($conn, $query_invoicedetail);
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="prosesupdateinvoice.php">
                  <input type="hidden" name="idinvoice" value="<?= $idinvoice ?>">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label for="noinvoice">Invoice Number <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="noinvoice" id="noinvoice" value="<?= $row_invoice['noinvoice'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="invoice_date" id="invoice_date" value="<?= $row_invoice['invoice_date'] ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="idcustomer">Customer ID</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="idcustomer" id="idcustomer" value="<?= $row_invoice['idcustomer'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="pocustomer">PO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="pocustomer" id="pocustomer" value="<?= $row_invoice['pocustomer'] ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label for="donumber">DO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="donumber" id="donumber" value="<?= $row_invoice['donumber'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label for="note">Note</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="keterangan" value="<?= $row_invoice['note'] ?>">
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
                           <?php
                           while ($rowdetail = mysqli_fetch_assoc($result_invoicedetail)) {
                              $idgrade = $rowdetail['idgrade'];
                              $idbarang = $rowdetail['idbarang'];
                              $weight = $rowdetail['weight'];
                              $price = $rowdetail['price'];
                              $discount = $rowdetail['discount'];
                              $discountrp = $rowdetail['discountrp'];
                              $amount = $rowdetail['amount'];
                           ?>
                              <div class="row mt-n2">
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <!-- Assuming you have another query to get 'nmgrade' based on $idgrade -->
                                          <input type="text" class="form-control text-center" name="nmgrade" id="nmgrade" value="<?= $nmgrade ?>" readonly>
                                          <input type="hidden" class="form-control text-center" name="idgrade[]" id="idgrade" value="<?= $idgrade ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <!-- Assuming you have another query to get 'nmbarang' based on $idbarang -->
                                          <input type="text" class="form-control text-center" name="nmbarang" id="nmbarang" value="<?= $nmbarang ?>" readonly>
                                          <input type="hidden" class="form-control text-center" name="idbarang[]" id="idbarang" value="<?= $idbarang ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" name="weight" id="weight" value="<?= $weight ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" name="price" id="price" value="<?= $price ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" name="discount" id="discount" value="<?= $discount ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" name="discountrp" id="discountrp" value="<?= $discountrp ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" name="amount" id="amount" value="<?= $amount ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           <?php } ?>

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
<script>
   document.title = "Edit <?= $row_invoice['noinvoice'] ?>";
</script>
<?php
include "../footer.php";
?>