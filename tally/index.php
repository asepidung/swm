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
               <!-- <h1 class="m-0">DATA tally</h1> -->
               <a href="newtally.php"><button type="button" class="btn btn-info"><i class="fas fa-plus-circle"></i> Baru</button></a>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Customer</th>
                              <th>Tally Numb</th>
                              <th>Tgl Kirim</th>
                              <th>xBox</th>
                              <th>xQty</th>
                              <th>Catatan</th>
                              <th>AKSI</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT tally.*, customers.nama_customer 
                           FROM tally
                           INNER JOIN customers ON tally.idcustomer = customers.idcustomer
                           ORDER BY tally.deliverydate DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) { ?>
                              <tr class="text-center">
                                 <td><?= $no; ?></td>
                                 <td class="text-left"><?= $tampil['nama_customer']; ?></td>
                                 <td><?= $tampil['tallynumber']; ?></td>
                                 <td><?= date("d-M-Y", strtotime($tampil['deliverydate'])); ?></td>
                                 <td>xBox</td>
                                 <td class="text-right">xQty</td>
                                 <td class="text-left"><?= $tampil['keterangan']; ?></td>
                                 </button>
                                 <td>
                                    <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="SCAN" onclick="window.location.href='scantally.php?id=<?php echo $tampil['idtally']; ?>'">
                                       <i class="fas fa-barcode"></i>
                                    </a>
                                    <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Lihat Hasil tally" onclick="window.location.href='tallydetail.php?id=<?php echo $tampil['idtally']; ?>'">
                                       <i class="fas fa-eye">
                                       </i>
                                    </a>
                                    <a class="btn btn-info btn-sm" href="edittally.php?idtally=<?= $tampil['idtally'] ?>">
                                       <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="deletetally.php?idtally=<?= $tampil['idtally'] ?>" onclick="return confirm('Apakah kamu yakin ingin menghapus data tally ini?')">
                                       <i class="fas fa-minus-circle"></i>
                                    </a>
                                 </td>
                              </tr>
                           <?php $no++;
                           }; ?>
                        </tbody>
                        <!-- <tfoot>
                           <th colspan="4"></th>
                           <th class="text-center"><?= number_format($total_sapi); ?> </td>
                           <th class="text-right"><?= number_format($total_berat_keseluruhan, 2); ?></td>
                           <th colspan="3"></th>
                        </tfoot> -->
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
   document.title = "Data tally";
</script>
<?php
require "../footnote.php";
include "../footer.php" ?>