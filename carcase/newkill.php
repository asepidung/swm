<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Query untuk mendapatkan data supplier
$supplierQuery = "SELECT idsupplier, nmsupplier FROM supplier";
$supplierResult = mysqli_query($conn, $supplierQuery);
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Input Data Karkas</h3>
                  </div>
                  <div class="card-body">
                     <form id="carcaseForm" action="prosescarcase.php" method="post">
                        <div class="form-group">
                           <label for="killdate">Tanggal Penyembelihan:</label>
                           <input type="date" class="form-control" id="killdate" name="killdate" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-group">
                           <label for="idsupplier">Supplier:</label>
                           <select class="form-control" id="idsupplier" name="idsupplier" required>
                              <option value="">Pilih Supplier</option>
                              <?php
                              // Loop melalui hasil query untuk mengisi pilihan supplier
                              while ($row = mysqli_fetch_assoc($supplierResult)) {
                                 echo '<option value="' . $row['idsupplier'] . '">' . htmlspecialchars($row['nmsupplier']) . '</option>';
                              }
                              ?>
                           </select>
                        </div>

                        <div class="form-group">
                           <label for="breed">Breed:</label>
                           <select class="form-control" id="breed" name="breed" required>
                              <option value="">Pilih Ras</option>
                              <option value="STEER">STEER</option>
                              <option value="HEIFER">HEIFER</option>
                              <option value="COW">COW</option>
                              <option value="LIMOUSIN">LIMOUSIN</option>
                           </select>
                        </div>

                        <div class="form-group">
                           <label for="note">Catatan:</label>
                           <textarea class="form-control" id="note" name="note" maxlength="100"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Simpan Data</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   // Mengubah judul halaman web
   document.title = "Input Data Killing";
</script>
<?php include "../footer.php" ?>