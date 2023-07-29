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

// Mengambil data dari tabel invoice
$queryinvoice = "SELECT invoice.*, customers.nama_customer, segment.idsegment
            FROM invoice
            INNER JOIN customers ON invoice.idcustomer = customers.idcustomer
            INNER JOIN segment ON customers.idsegment = segment.idsegment
            WHERE invoice.idinvoice = $idinvoice";
$resultInvoice = mysqli_query($conn, $queryinvoice);
$rowInvoice = mysqli_fetch_assoc($resultInvoice);

$queryinvoicedetail = "SELECT invoicedetail.*, grade.nmgrade, barang.nmbarang, barang.kdbarang
                  FROM invoicedetail
                  INNER JOIN grade ON invoicedetail.idgrade = grade.idgrade
                  INNER JOIN barang ON invoicedetail.idbarang = barang.idbarang
                  WHERE invoicedetail.idinvoice = $idinvoice";
$resultinvoicedetail = mysqli_query($conn, $queryinvoicedetail);
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="approveinvoice.php">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <label>Invoice Date</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= date("d-M-y", strtotime($rowInvoice['invoice_date'])) ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label>Nama Customer</label>
                                 <div class="input-group">
                                    <input type="hidden" value="<?= $rowInvoice['idcustomer'] ?>">
                                    <input type="text" class="form-control" value="<?= $rowInvoice['nama_customer'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label>PO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $rowInvoice['pocustomer'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                           <div class="col">
                              <div class="form-group">
                                 <label>invoice Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $rowInvoice['noinvoice'] ?>" readonly>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $rowInvoice['note'] ?>">
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
                              <div class="col">
                                 <div class="form-group">
                                    <label>Products</label>
                                 </div>
                              </div>
                              <div class="col-2">
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
                           <?php while ($rowInvoicedetail = mysqli_fetch_assoc($resultinvoicedetail)) { ?>
                              <div class="row mt-n2">
                                 <input type="hidden" class="form-control text-center" value="<?= $rowInvoicedetail['idgrade'] ?>" readonly>
                                 <div class="col">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control" value="<?= $rowInvoicedetail['nmbarang'] ?>" readonly>
                                          <input type="hidden" class="form-control" value="<?= $rowInvoicedetail['idbarang'] ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" value="<?= number_format($rowInvoicedetail['weight'], 2) ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" value="<?= number_format($rowInvoicedetail['price'], 2) ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-center" value="<?= $rowInvoicedetail['discount'] . "%" ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" value="<?= number_format($rowInvoicedetail['discountrp'], 2) ?>" readonly>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <div class="input-group">
                                          <input type="text" class="form-control text-right" readonly value="<?= number_format($rowInvoicedetail['amount'], 2) ?>">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           <?php } ?>
                        </div>
                        <div class="row">
                           <div class="col-3 text-right">Weight Total</div>
                           <div class="col-2">
                              <input type="text" class="form-control text-right" value="<?= number_format($rowInvoice['xweight'], 2) ?>" readonly>
                           </div>
                           <div class="col-5 text-right">
                              Total Amount
                           </div>
                           <div class="col-2">
                              <input type="text" class="form-control text-right" readonly value="<?= number_format($rowInvoice['xamount'], 2) ?>">
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Tax 11%
                           </div>
                           <div class="col-2">
                              <input type="text" class="form-control text-right" readonly value="<?= number_format($rowInvoice['tax'], 2) ?>">
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Charge
                           </div>
                           <div class="col-2">
                              <input type="text" class="form-control text-right" value="<?= number_format($rowInvoice['charge'], 2) ?>" readonly>
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Downpayment
                           </div>
                           <div class="col-2">
                              <input type="text" class="form-control text-right" value="<?= number_format($rowInvoice['downpayment'], 2) ?>" readonly>
                           </div>
                        </div>
                        <div class="row mt-1">
                           <div class="col-10 text-right">
                              Balance
                           </div>
                           <div class="col-2">
                              <input type="text" class="form-control text-right" readonly value="<?= number_format($rowInvoice['balance'], 2) ?>">
                           </div>
                        </div>
                        <div class="row mt-3">
                           <div class="col-2">
                              <button type="button" name="approved" class="btn btn-block bg-gradient-warning">Approve</button>
                           </div>
                           <div class="col-2">
                              <button type="button" name="disapproved" class="btn btn-block bg-gradient-warning">Disapprove</button>
                           </div>
                           <div class="col-2">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit">Print</button>
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

<script>
   document.title = "<?= $rowInvoice['noinvoice'] ?>";
</script>
<?php
// require "../footnotes.php";
include "../footer.php";
?>