<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("Location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Query untuk mendapatkan data dari tabel poproduct yang stat-nya Waiting dan join dengan tabel supplier
$query = "SELECT p.idpoproduct, s.nmsupplier, p.deliveryat, p.nopoproduct, p.note, p.stat
          FROM poproduct p
          JOIN supplier s ON p.idsupplier = s.idsupplier
          WHERE p.stat = 'Waiting'";

$result = mysqli_query($conn, $query);
if (!$result) {
   die("Query error: " . mysqli_error($conn));
}
?>
<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card mt-3">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Supplier</th>
                              <th>Receiving Date</th>
                              <th>PO</th>
                              <th>Note</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $counter = 1;
                           while ($row = mysqli_fetch_assoc($result)) {
                              echo '<tr>
                                       <td class="text-center">' . $counter++ . '</td>
                                       <td>' . htmlspecialchars($row['nmsupplier']) . '</td>
                                       <td class="text-center">' . htmlspecialchars($row['deliveryat']) . '</td>
                                       <td class="text-center">' . htmlspecialchars($row['nopoproduct']) . '</td>
                                       <td class="text-center">' . htmlspecialchars($row['note']) . '</td>
                                       <td class="text-center">
                                          <a class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Buat GR" href="newgr.php?id=' . $row['idpoproduct'] . '">
                                          Proses GR <i class="fas fa-truck"></i>
                                          </a>
                                       </td>
                                    </tr>';
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
   document.title = "DRAFT GR";
</script>
<?php
include "../footer.php";
?>