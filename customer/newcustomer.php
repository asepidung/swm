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
            <div class="col-12">
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Data Customer Baru</h3>
                  </div>
                  <form method="POST" action="inputcustomer.php">
                     <div class=" card-body">
                        <div class="row">
                           <div class="col-6">
                              <div class="form-group">
                                 <label for="nama_customer">Nama Customer <span class="text-danger">*</span></label>
                                 <input type="text" class="form-control" name="nama_customer" id="nama_customer" autofocus required>
                              </div>
                              <div class="form-group">
                                 <label for="groupcs">Group <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <select class="form-control" name="idgroup" id="idgroup" required>
                                       <option value="">Pilih Group</option>
                                       <?php
                                       $query = "SELECT * FROM groupcs ORDER BY nmgroup ASC";
                                       $result = mysqli_query($conn, $query);
                                       // Generate options based on the retrieved data
                                       while ($row = mysqli_fetch_assoc($result)) {
                                          $idgroup = $row['idgroup'];
                                          $nmgroup = $row['nmgroup'];
                                          echo "<option value=\"$idgroup\">$nmgroup</option>";
                                       }
                                       ?>
                                    </select>
                                    <div class="input-group-append">
                                       <a href="../group/newgroup.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="alamat">Alamat 1<span class="text-danger">*</label>
                                 <input type="text" class="form-control" name="alamat1" id="alamat1" required>
                              </div>
                              <div class="form-group">
                                 <label for="alamat">Alamat 2</label>
                                 <input type="text" class="form-control" name="alamat2" id="alamat2">
                              </div>
                              <div class="form-group">
                                 <label for="alamat">Alamat 3</label>
                                 <input type="text" class="form-control" name="alamat3" id="alamat3">
                              </div>
                              <div class="form-group">
                                 <label for="idsegment">Segment <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <select class="form-control" name="idsegment" id="idsegment" required>
                                       <option value="">Pilih Segment</option>
                                       <?php
                                       $query = "SELECT * FROM segment";
                                       $result = mysqli_query($conn, $query);
                                       // Generate options based on the retrieved data
                                       while ($row = mysqli_fetch_assoc($result)) {
                                          $idsegment = $row['idsegment'];
                                          $nmsegment = $row['nmsegment'];
                                          echo "<option value=\"$idsegment\">$nmsegment</option>";
                                       }
                                       ?>
                                    </select>
                                    <div class="input-group-append">
                                       <a href="../segment/segment.php" class="btn btn-warning"><i class="fas fa-plus"></i></a>
                                    </div>
                                 </div>
                              </div>

                           </div>
                           <div class="col-6">
                              <div class="form-group">
                                 <label for="top">T.O.P <span class="text-danger">*</label>
                                 <input type="number" class="form-control" name="top" id="top" required>
                              </div>
                              <div class="form-group">
                                 <label for="pajak">Customer Dikenakan Pajak <span class="text-danger">*</label>
                                 <select class="form-control" name="pajak" id="pajak" required>
                                    <option>--Pilih Satu--</option>
                                    <option value="YES">YES</option>
                                    <option value="NO">NO</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label for="tukarfaktur">Tukar Faktur <span class="text-danger">*</label>
                                 <select class="form-control" name="tukarfaktur" id="tukarfaktur" required>
                                    <option>--Pilih Satu--</option>
                                    <option value="YES">YES</option>
                                    <option value="NO">NO</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label for="telepon">Telepon</label>
                                 <input type="tel" class="form-control" name="telepon" id="telepon">
                              </div>
                              <div class="form-group">
                                 <label for="email">Email</label>
                                 <input type="email" class="form-control" name="email" id="email">
                              </div>
                              <div class="form-group">
                                 <label for="catatan">Catatan</label>
                                 <input type="text" class="form-control" name="catatan" id="catatan" value="-">
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <button type="submit" class="btn bg-gradient-primary">Submit</button>
                        </div>
                     </div>
                     <!-- /.card-body -->

                  </form>
               </div>
               <!-- /.card -->
            </div>
         </div>
   </section>
</div>
<?php include "../footer.php" ?>