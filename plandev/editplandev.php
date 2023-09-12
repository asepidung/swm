<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Memeriksa apakah ID rencana pengiriman telah dikirimkan melalui URL
if (isset($_GET['idplandev'])) {
   $idplandev = mysqli_real_escape_string($conn, $_GET['idplandev']);

   // Mengambil data rencana pengiriman berdasarkan ID
   $query = "SELECT plandev.*, customers.nama_customer FROM plandev
              JOIN customers ON plandev.idcustomer = customers.idcustomer
              WHERE idplandev = $idplandev";
   $result = mysqli_query($conn, $query);
   $tampil = mysqli_fetch_array($result);
} else {
   echo "ID rencana pengiriman tidak ditemukan.";
   exit;
}
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="updateplandev.php">
                  <input type="hidden" name="idplandev" value="<?= $idplandev; ?>">
                  <div class="card">
                     <div class="card-body">
                        <div class="form-group">
                           <label for="plandelivery">Tgl Kirim <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <input type="date" class="form-control" name="plandelivery" id="plandelivery" value="<?= $tampil['plandelivery']; ?>" required autofocus>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="idcustomer">Customer <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <select class="form-control" name="idcustomer" id="idcustomer" required>
                                 <option value="">Pilih Customer</option>
                                 <?php
                                 $query = "SELECT * FROM customers ORDER BY nama_customer ASC";
                                 $result = mysqli_query($conn, $query);
                                 while ($row = mysqli_fetch_assoc($result)) {
                                    $idcustomer = $row['idcustomer'];
                                    $nama_customer = $row['nama_customer'];
                                    $selected = ($idcustomer == $tampil['idcustomer']) ? "selected" : "";
                                    echo "<option value=\"$idcustomer\" $selected>$nama_customer</option>";
                                 }
                                 ?>
                              </select>
                              <div class="input-group-append">
                                 <a href="../customer/newcustomer.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="weight">Weight <span class="text-danger">*</span></label>
                           <div class="input-group">
                              <input type="number" class="form-control" name="weight" id="weight" value="<?= $tampil['weight']; ?>" required>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="driver_name">Driver</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="driver_name" id="driver_name" value="<?= $tampil['driver_name']; ?>">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="armada">Armada</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="armada" id="armada" value="<?= $tampil['armada']; ?>">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="loadtime">Load Time</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="loadtime" id="loadtime" value="<?= $tampil['loadtime']; ?>">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="note">Catatan</label>
                           <div class="input-group">
                              <input type="text" class="form-control" name="note" id="note" placeholder="keterangan" value="<?= $tampil['note']; ?>">
                           </div>
                        </div>
                        <div class="form-group text-center">
                           <button type="submit" class="btn btn-block btn-primary">Update</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   document.title = "Edit Rencana Pengiriman";
</script>

<?php
include "../footer.php";
?>