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
            <div class="col-1">
               <a href="drafttally.php"><button type="button" class="btn btn-outline-primary"><i class="fab fa-firstdraft"></i> Draft</button></a>
            </div>
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
                              <th>Customer</th>
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
                              $idso = $tampil['idso'];
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td><?= $tampil['nama_customer']; ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td class="text-center"><?= $tampil['sonumber']; ?></td>
                                 <td class="text-center"><?= $tampil['notally']; ?></td>
                                 <td><?= $tampil['po']; ?></td>
                                 <td class="text-center">
                                    <a href="printso.php?idso=<?= $tampil['idso']; ?>" class="btn btn-sm btn-info"><i class="fas fa-cart-arrow-down"></i></a>
                                    <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Mulai Scan" onclick="window.location.href='tallydetail.php?id=<?= $tampil['idtally'] ?>&stat=ready'">
                                       <i class="fas fa-tasks"></i>
                                    </a>
                                    <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Lihat Tally" onclick="window.location.href='lihattally.php?id=<?= $tampil['idtally']; ?>'">
                                       <i class="fas fa-eye"></i>
                                    </a>
                                    <?php
                                    $query_check_do = "SELECT COUNT(*) as do_count FROM do WHERE idtally = {$tampil['idtally']}";
                                    $result_check_do = mysqli_query($conn, $query_check_do);
                                    $row_check_do = mysqli_fetch_assoc($result_check_do);
                                    $do_count = $row_check_do['do_count'];
                                    if ($do_count > 0) { ?>
                                       <a href="#" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="DO Sudah Dibuat">
                                          <i class="fas fa-truck"></i>
                                       </a>
                                       <a href="#" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="DO Sudah Dibuat">
                                          <i class="fas fa-minus-square"></i>
                                       </a>
                                    <?php } else { ?>
                                       <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Buat DO" onclick="window.location.href='../do/doissue.php?id=<?= $tampil['idtally'] ?>'">
                                          <i class="fas fa-truck"></i>
                                       </a>
                                       <a href="deletetally.php?id=<?= $tampil['idtally']; ?>&idso=<?= $idso ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus">
                                          <i class="fas fa-minus-square"></i>
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
   document.title = "Tally Sheet";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>