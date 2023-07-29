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
$query_invoice = "SELECT invoice.*, customers.nama_customer, customers.pajak
                  FROM invoice
                  INNER JOIN customers ON invoice.idcustomer = customers.idcustomer
                  WHERE invoice.idinvoice = '$idinvoice'";
$result_invoice = mysqli_query($conn, $query_invoice);
$row_invoice = mysqli_fetch_assoc($result_invoice);

// Query untuk mengambil data dari tabel invoicedetail berdasarkan idinvoice
$query_invoicedetail = "SELECT invoicedetail.*, grade.nmgrade, barang.nmbarang
                        FROM invoicedetail
                        INNER JOIN grade ON invoicedetail.idgrade = grade.idgrade
                        INNER JOIN barang ON invoicedetail.idbarang = barang.idbarang
                        WHERE invoicedetail.idinvoice = '$idinvoice'";
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
                  <input type="hidden" name="pajak" id="pajak" value="<?= $row_invoice['pajak']; ?>">
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
                                    <input type="text" class="form-control" name="nama_customer" id="nama_customer" value="<?= $row_invoice['nama_customer'] ?>" readonly>
                                    <input type="hidden" name="idcustomer" id="idcustomer" value="<?= $row_invoice['idcustomer'] ?>">
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
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="Catatan ..." value="<?= $row_invoice['note'] ?>">
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
                           <!-- invoicedetail -->
                           <?php
                           while ($row_invoicedetail = mysqli_fetch_assoc($result_invoicedetail)) {
                              $idinvoicedetail = $row_invoicedetail['idinvoicedetail'];
                              $idgrade = $row_invoicedetail['idgrade'];
                              $nmgrade = $row_invoicedetail['nmgrade'];
                              $idbarang = $row_invoicedetail['idbarang'];
                              $nmbarang = $row_invoicedetail['nmbarang'];
                              $weight = $row_invoicedetail['weight'];
                              $price = $row_invoicedetail['price'];
                              $discount = $row_invoicedetail['discount'];
                              $discountrp = $row_invoicedetail['discountrp'];
                              $amount = $row_invoicedetail['amount'];
                           ?>
                              <div class="row m-n2">
                                 <div class="col-1">
                                    <div class="form-group">
                                       <input type="hidden" name="idgrade" id="idgrade[]" value="<?= $idgrade ?>">
                                       <input type="text" class="form-control text-center" value="<?= $nmgrade ?>" readonly>
                                    </div>
                                 </div>
                                 <div class="col">
                                    <div class="form-group">
                                       <input type="text" class="form-control" value="<?= $nmbarang ?>" readonly>
                                       <input type="hidden" name="idbarang" id="idbarang[]" value="<?= $idbarang ?>">
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <input type="text" class="form-control text-right" name="weight" id="weight[]" value="<?= number_format($weight, 2) ?>" readonly>
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <input type="text" class="form-control text-right" name="price" id="price[]" value="<?= number_format($price, 2) ?>">
                                    </div>
                                 </div>
                                 <div class="col-1">
                                    <div class="form-group">
                                       <input type="text" class="form-control  text-center" name="discount" id="discount[]" value="<?= $discount ?>">
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <input type="text" class="form-control  text-right" name="discountrp" id="discountrp[]" value="0">
                                    </div>
                                 </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                       <input type="text" class="form-control text-right" name="amount" id="amount[]" value="0" readonly>
                                    </div>
                                 </div>
                              </div>
                           <?php
                           }
                           ?>
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
                           </div>
                        </div>
                        <input type="hidden" name="xdiscount" id="xdiscount" value="0">
                        <div class="row">
                           <div class="col-3">
                              <button type="button" class="btn btn-block bg-gradient-warning" onclick="calculateAmounts()">Calculate</button>
                           </div>
                           <div class="col-3">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')" disabled id="submit-btn">Update</button>
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
<script src="../dist/js/hitunginvoice.js"></script>
<script>
   document.title = "Edit Invoice";
</script>
<?php
include "../footer.php";
?>