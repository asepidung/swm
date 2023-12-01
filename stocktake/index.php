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
                              <th>Taking Date</th>
                              <th>Number</th>
                              <th>J01</th>
                              <th>J02</th>
                              <th>J03</th>
                              <th>P01</th>
                              <th>P02</th>
                              <th>P03</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT * FROM stocktake");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr class="text-right">
                                 <td class="text-center"><?= $no ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['tglst'])) ?></td>
                                 <td class="text-center"><?= $tampil['nost'] ?></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td></td>
                                 <td class="text-center">
                                    <a href="lihatst.php?id=<?= $tampil['idst']; ?>" class="btn btn-sm btn-success">
                                       <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="printst.php?id=<?= $tampil['idst']; ?>" class="btn btn-sm btn-primary">
                                       <i class="fas fa-print"></i>
                                    </a>
                                    <a href="starttaking.php?id=<?= $tampil['idst']; ?>&stat=ready" class="btn btn-sm btn-warning">
                                       <i class="fas fa-play"></i>
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