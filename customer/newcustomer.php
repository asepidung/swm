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
            <div class="col-6">
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Data Customer Baru</h3>
                  </div>
                  <form method="POST" action="inputcustomer.php">
                     <div class=" card-body">
                        <div class="row">
                           <div class="col">
                              <div class="form-group">
                                 <!-- <label for="groupcs">Group <span class="text-danger">*</span></label> -->
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
                                 <!-- <label for="nama_customer">Nama Customer <span class="text-danger">*</span></label> -->
                                 <input type="text" class="form-control" placeholder="Isi Nama Customer" name="nama_customer" id="nama_customer" autofocus required>
                              </div>
                              <div class="form-group">
                                 <input type="text" class="form-control" name="alamat" id="alamat" required placeholder="Isikan Alamat">
                              </div>
                              <div class="form-group">
                                 <!-- <label for="idsegment">Segment <span class="text-danger">*</span></label> -->
                                 <div class="input-group">
                                    <select class="form-control" name="idsegment" id="idsegment" required>
                                       <option value="">Payment Method</option>
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
                              <div class="form-group">
                                 <input type="number" class="form-control" name="top" id="top" required placeholder="T.O.P">
                              </div>
                              <div class="form-group">
                                 <select class="form-control" name="tukarfaktur" id="tukarfaktur" required>
                                    <option value="">--Tukar Faktur--</option>
                                    <option value="NO">NO</option>
                                    <option value="YES">YES</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <input type="tel" class="form-control" name="telepon" id="telepon" placeholder="Telepon">
                              </div>
                              <div class="form-group">
                                 <input type="text" class="form-control" name="catatan" id="catatan" placeholder="Catatan">
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