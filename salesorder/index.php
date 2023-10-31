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
   <!-- /.content-header -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <!-- <h1 class="m-0">DATA BONING</h1> -->
               <a href="newso.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>SO Number</th>
                              <th>Customer</th>
                              <th>Tgl Kirim</th>
                              <th>PO</th>
                              <th>Progress</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT salesorder.*, customers.nama_customer
                           FROM salesorder 
                           INNER JOIN customers ON salesorder.idcustomer = customers.idcustomer 
                           ORDER BY idso DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $progress = $tampil['progress'];
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['sonumber']; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td><?= $tampil['po']; ?></td>
                                 <?php
                                 if ($progress == "Delivered") { ?>
                                    <td class="text-success"><i class="fas fa-check-circle"></i> Delivered </td>
                                 <?php } elseif ($progress == "On Process") { ?>
                                    <td class="text-info"><i class="fas fa-spinner fa-pulse"></i> On Process</td>
                                 <?php } elseif ($progress == "On Delivery") { ?>
                                    <td style="color: #92079c;"></i><i class="fas fa-truck"></i> On Delivery</td>
                                 <?php } else { ?>
                                    <td class="text-secondary"><i class="fas fa-clock"></i> Waiting</td>
                                 <?php } ?>
                                 <td class="text-center">
                                    <?php
                                    if ($progress == "Waiting") { ?>
                                       <a href="lihatso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-primary">
                                          <i class="far fa-eye"></i>
                                       </a>
                                       <a href="editso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-success">
                                          <i class="far fa-edit"></i>
                                       </a>
                                       <a href="deleteso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                          <i class="far fa-trash-alt"></i>
                                       </a>
                                    <?php } else { ?>
                                       <a href="lihatso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-secondary">
                                          <i class="far fa-eye"></i>
                                       </a>
                                    <?php } ?>
                                 </td>
                              </tr>
                           <?php $no++;
                           } ?>
                        </tbody>
                     </table>
                  </div>
                  <!-- /.card-body -->
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
</div>
<!-- /.content-wrapper -->

<script>
   // Mengubah judul halaman web
   document.title = "Sales Order List";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>