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
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-12 col-md-2 mb-2">
               <a href="newso.php">
                  <button type="button" class="btn btn-sm btn-outline-primary btn-block"><i class="fas fa-plus"></i> Baru</button>
               </a>
            </div>
            <div class="col-12 col-md-6 mb-2">
               <form method="GET" action="">
                  <div class="input-group">
                     <input type="date" class="form-control form-control-sm" name="awal" value="<?= $awal; ?>">
                     <input type="date" class="form-control form-control-sm" name="akhir" value="<?= $akhir; ?>">
                     <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-primary" name="search"><i class="fas fa-search"></i></button>
                     </div>
                  </div>
               </form>
            </div>

         </div>
      </div>
   </div>
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Killing Number</th>
                              <th>Killing Date</th>
                              <th>Supplier</th>
                              <th>Head</th>
                              <th>Carcase %</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>

   </section>
</div>


<script>
   // Mengubah judul halaman web
   document.title = "Sales Order List";
</script>
<?php
include "../footer.php";
?>