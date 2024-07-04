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
                              <th>Catatan</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT * FROM stocktake ORDER BY nost DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr class="text-right">
                                 <td class="text-center"><?= $no ?></td>
                                 <td class="text-center"><?= date("d-M-y", strtotime($tampil['tglst'])) ?></td>
                                 <td class="text-center"><?= $tampil['nost'] ?></td>
                                 <td class="text-left"><?= $tampil['note'] ?></td>
                                 <td class="text-center">
                                    <div class="btn-group">
                                       <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Pilih Proses
                                       </button>
                                       <div class="dropdown-menu">
                                          <a class="dropdown-item" href="starttaking.php?id=<?= $tampil['idst']; ?>&stat=ready">
                                             <i class="fas fa-barcode"></i> Start Taking
                                          </a>
                                          <a class="dropdown-item" href="lihatst.php?id=<?= $tampil['idst']; ?>">
                                             <i class="fas fa-eye"></i> View
                                          </a>
                                          <a class="dropdown-item" href="printst.php?id=<?= $tampil['idst']; ?>">
                                             <i class="fas fa-print"></i> Print
                                          </a>
                                          <a class="dropdown-item" href="#" onclick="confirmStockIn(<?= $tampil['idst']; ?>)">
                                             <i class="fas fa-upload"></i> Confirm Stock In
                                          </a>
                                          <a class="dropdown-item" href="editst.php?id=<?= $tampil['idst']; ?>">
                                             <i class="fas fa-edit"></i> Edit
                                          </a>
                                          <a class="dropdown-item" href="deletest.php?id=<?= $tampil['idst']; ?>" onclick="return confirm('apakah anda yakin ingin menghapus data ini?')">
                                             <i class="fas fa-trash"></i> Delete
                                          </a>
                                       </div>
                                    </div>
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
   function confirmStockIn(idst) {
      // Menampilkan peringatan pertama
      var confirmFirst = confirm("Apakah kamu sudah yakin dengan data stock opname yang ada? (ingat data tidak bisa dikembalikan)");

      // Jika pengguna memilih OK pada peringatan pertama
      if (confirmFirst) {
         // Menampilkan peringatan kedua
         var confirmSecond = confirm("Ketika anda melanjutkan, maka data di stock akan dihapus dan diganti dengan data baru dari hasil stock opname");

         // Jika pengguna memilih OK pada peringatan kedua
         if (confirmSecond) {
            // Redirect ke halaman stockin.php
            window.location.href = "stockin.php?id=" + idst;
         }
      }
   }
   // Mengubah judul halaman web
   document.title = "STOCK TAKE";
</script>
<?php
// require "../footnote.php";
include "../footer.php" ?>