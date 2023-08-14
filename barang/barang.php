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
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <a href="newbarang.php"><button type="button" class="btn btn-info"> Product Baru</button></a>
            </div>
         </div>
      </div>
   </div>

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <div class="col">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th rowspan="2">Kode</th>
                                 <th rowspan="2">Nama Product</th>
                                 <th colspan="6">Penjualan DO</th>
                                 <th colspan="6">Penjualan LB</th>
                              </tr>
                              <tr>
                                 <th>J01</th>
                                 <th>J02</th>
                                 <th>P01</th>
                                 <th>P02</th>
                                 <th>J03</th>
                                 <th>P03</th>
                                 <th>J01</th>
                                 <th>J02</th>
                                 <th>P01</th>
                                 <th>P02</th>
                                 <th>J03</th>
                                 <th>P03</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $ambildata = mysqli_query($conn, "SELECT * FROM barang");
                              while ($tampil = mysqli_fetch_array($ambildata)) {
                                 $idbarang = $tampil['idbarang'];
                                 include "flowstock.php";
                              ?>
                                 <tr class="text-center">
                                    <td><?= $tampil['kdbarang']; ?></td>
                                    <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                                    <td><?= $doJ01; ?></td>
                                    <td><?= $doJ02; ?></td>
                                    <td><?= $doP01; ?></td>
                                    <td><?= $doP02; ?></td>
                                    <td><?= $doJ03; ?></td>
                                    <td><?= $doP03; ?></td>
                                    <td><?= $lbJ01; ?></td>
                                    <td><?= $lbJ02; ?></td>
                                    <td><?= $lbP01; ?></td>
                                    <td><?= $lbP02; ?></td>
                                    <td><?= $lbJ03; ?></td>
                                    <td><?= $lbP03; ?></td>
                                 </tr>
                              <?php
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   document.title = "DATA BARANG";
</script>
<?php
include "../footer.php";
include "../footnote.php";
?>