<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <a href="newreturjual.php"><button type="button" class="btn btn-outline-primary"><i class="fas fa-plus"></i> Baru</button></a>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->

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
                              <th>Return Number</th>
                              <th>Customer</th>
                              <th>Total qty</th>
                              <th>Catatan</th>
                              <th>Made By</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "
                           SELECT returjual.*, customers.nama_customer, users.fullname
                           FROM returjual
                           JOIN customers ON returjual.idcustomer = customers.idcustomer
                           LEFT JOIN users ON returjual.idusers = users.idusers
                           WHERE returjual.is_deleted = 0 
                           ORDER BY returnnumber DESC");

                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              // Jika fullname NULL, gunakan teks alternatif
                              $fullname = !empty($tampil['fullname']) ? $tampil['fullname'] : "Tidak Diketahui";
                           ?>
                              <tr>
                                 <td class="text-center"><?= $no; ?></td>
                                 <td class="text-center"><?= htmlspecialchars($tampil['returnnumber']); ?></td>
                                 <td><?= htmlspecialchars($tampil['nama_customer']); ?></td>
                                 <td></td>
                                 <td><?= htmlspecialchars($tampil['note']); ?></td>
                                 <td class="text-center"><?= htmlspecialchars($fullname); ?></td>
                                 <td class="text-center">
                                    <a href="detailrj.php?idreturjual=<?= htmlspecialchars($tampil['idreturjual']); ?>" class="btn btn-sm btn-warning">
                                       <i class="fas fa-barcode"></i>
                                    </a>
                                    <a href="lihatreturjual.php?idreturjual=<?= htmlspecialchars($tampil['idreturjual']); ?>" class="btn btn-sm btn-success">
                                       <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="deletereturjual.php?idreturjual=<?= htmlspecialchars($tampil['idreturjual']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('apakah anda yakin ingin menghapus data ini?')">
                                       <i class="fas fa-minus-circle"></i>
                                    </a>
                                 </td>
                              </tr>
                           <?php
                              $no++;
                           }
                           ?>
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
   document.title = "Sales Return";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>