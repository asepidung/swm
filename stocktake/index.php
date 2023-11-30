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
         <div class="row">
            <div class="col">
               <!-- <h1 class="m-0">DATA BONING</h1> -->
               <a href="newst.php"><button type="button" class="btn btn-outline-primary"><i class="fab fa-firstdraft"></i> Baru</button></a>
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
                              <th>Barang</th>
                              <th>Tgl Kirim</th>
                              <th>SO Number</th>
                              <th>Tally ID</th>
                              <th>PO</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT tally.*, customers.nama_customer
                           FROM tally 
                           INNER JOIN customers ON tally.idcustomer = customers.idcustomer 
                           ORDER BY idtally DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td class="text-center"><?= $tampil['sonumber']; ?></td>
                                 <td class="text-center"><?= $tampil['notally']; ?></td>
                                 <td><?= $tampil['po']; ?></td>
                                 <td class="text-center">
                                    <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Mulai Scan" onclick="window.location.href='tallydetail.php?id=<?= $tampil['idtally'] ?>&stat=ready'">
                                       <i class="fas fa-tasks"></i>
                                    </a>
                                    <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="window.location.href='printtally.php?id=<?= $tampil['idtally']; ?>'">
                                       <i class="fas fa-print"></i>
                                    </a>
                                    <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit" onclick="window.location.href='edittallydetail.php?id=<?= $tampil['idtally']; ?>'">
                                       <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="window.location.href='deletetallydetail.php?id=<?= $tampil['idtally']; ?>'">
                                       <i class="fas fa-minus-square"></i>
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
   document.title = "Tally Sheet";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>