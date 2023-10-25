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
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= $tampil['sonumber']; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td><?= $tampil['po']; ?></td>
                                 <td><i class="fas fa-spinner fa-pulse"></i> <?= $tampil['progress']; ?></td>
                                 <td class="text-center">
                                    <a href="prinso.php?idso=<?= $tampil['idso']; ?>" class="mx-auto p-2">
                                       <i class="far fa-eye text-primary"></i>
                                    </a>
                                    <a href="editso.php?idso=<?= $tampil['idso']; ?>" class="mx-auto p-2">
                                       <i class="far fa-edit text-success"></i>
                                    </a>
                                    <a href="deleteso.php?idso=<?= $tampil['idso']; ?>" class="mx-auto p-2" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                       <i class="far fa-trash-alt text-danger"></i>
                                    </a>
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