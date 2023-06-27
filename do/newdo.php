<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="cetakdo.php" onsubmit="submitForm(event)">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-3">
                              <div class="form-group">
                                 <label for="deliverydate">Tgl Kirim <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="deliverydate" id="deliverydate" required>
                                 </div>
                              </div>
                           </div>
                           <div class="col-3">
                              <div class="form-group">
                                 <label for="customer">Customer <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <select class="form-control" name="customer" id="customer" required>
                                       <option value="">Pilih Customer</option>
                                       <?php
                                       $query = "SELECT * FROM customers ORDER BY nama_customer ASC";
                                       $result = mysqli_query($conn, $query);
                                       // Generate options based on the retrieved data
                                       while ($row = mysqli_fetch_assoc($result)) {
                                          $idcustomer = $row['idcustomer'];
                                          $nama_customer = $row['nama_customer'];
                                          echo "<option value=\"$idcustomer\">$nama_customer</option>";
                                       }
                                       ?>
                                    </select>
                                    <div class="input-group-append">
                                       <a href="../customer/newcustomer.php" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="po">Cust PO</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="po" id="po" value="-">
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="driver">Driver</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="driver" id="driver" value="-">
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="policenumb">Plat Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="policenumb" id="policenumb" value="-">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-1">
                              <div class="form-group">
                                 <label for="kdarea">Code</label>
                                 <div class="input-group">
                                    <select class="form-control" name="kdarea" id="kdarea">
                                       <option value="">J01</option>
                                       <option value="">J02</option>
                                       <option value="">P01</option>
                                       <option value="">P01</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-3">
                              <div class="form-group">
                                 <label for="idbarang">Product</label>
                                 <div class="input-group">
                                    <select class="form-control" name="idbarang" id="idbarang">
                                       <option value="">--Pilih--</option>
                                       <?php
                                       $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                                       $result = mysqli_query($conn, $query);
                                       while ($row = mysqli_fetch_assoc($result)) {
                                          $idbarang = $row['idbarang'];
                                          $nmbarang = $row['nmbarang'];
                                          echo '<option value="' . $idbarang . '">' . $nmbarang . '</option>';
                                       }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="box">Box</label>
                                 <div class="input-group">
                                    <div class="form-group">
                                       <input type="number" name="box" id="box" class="form-control">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-2">
                              <div class="form-group">
                                 <label for="qty">Weight</label>
                                 <div class="input-group">
                                    <div class="form-group">
                                       <input type="number" name="qty" id="qty" class="form-control">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-4">
                              <div class="form-group">
                                 <label for="note">Notes</label>
                                 <div class="input-group">
                                    <div class="form-group">
                                       <input type="text" name="note" id="note" class="form-control">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <button type="submit" class="btn bg-gradient-primary" name="submit">Print</button>
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
   // Mengubah judul halaman web
   document.title = "Data Boning";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>