<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
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
               <a href="drafttally.php"><button type="button" class="btn btn-sm btn-outline-primary"><i class="fab fa-firstdraft"></i> Draft</button></a>
            </div>
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <?php
         // Cek apakah ada parameter error di URL dan tampilkan pesan jika ada
         if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
         }
         ?>
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
                              <th>Note</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT tally.*, customers.nama_customer, salesorder.note
                           FROM tally 
                           INNER JOIN customers ON tally.idcustomer = customers.idcustomer
                           INNER JOIN salesorder ON tally.idso = salesorder.idso
                           ORDER BY idtally DESC");

                           if (!$ambildata) {
                              die("Query error: " . mysqli_error($conn));
                           }

                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $idso = $tampil['idso'];

                           ?>
                              <?php if ($tampil['stat'] == 'Rejected') {
                                 echo '<tr class="text-danger">';
                              } else {
                                 echo '<tr>';
                              } ?>
                              <td class="text-center"><?= $no; ?></td>
                              <td><?= htmlspecialchars($tampil['nama_customer']); ?></td>
                              <td class="text-center"><?= date("d-M-y", strtotime($tampil['deliverydate'])); ?></td>
                              <td class="text-center">
                                 <a href="printso.php?idso=<?= $tampil['idso']; ?>">
                                    <?= htmlspecialchars($tampil['sonumber']); ?>
                                 </a>
                              </td>
                              <td class="text-center"><?= htmlspecialchars($tampil['notally']); ?></td>
                              <td><?= htmlspecialchars($tampil['po']); ?></td>
                              <td><?= htmlspecialchars($tampil['note']); ?></td>
                              <td class="text-center">
                                 <a href="lihattally.php?id=<?= $tampil['idtally'] ?>">
                                    <button type="button" class="btn btn-sm btn-warning"> <i class="fas fa-eye"></i></button>
                                 </a>
                                 <?php
                                 $query_check_stat = "SELECT stat FROM tally WHERE idtally = {$tampil['idtally']}";
                                 $result_check_stat = mysqli_query($conn, $query_check_stat);

                                 if ($result_check_stat) {
                                    $row_check_stat = mysqli_fetch_assoc($result_check_stat);
                                    $stat = $row_check_stat['stat'];

                                    switch ($stat) {
                                       case "":
                                          // Display if stat is empty
                                 ?>
                                          <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Mulai Scan" onclick="window.location.href='tallydetail.php?id=<?= $tampil['idtally'] ?>&stat=ready'">
                                             <i class="fas fa-tasks"></i>
                                          </a>
                                          <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Approve" href="approvetally.php?id=<?= htmlspecialchars($tampil['idtally']) ?>&idso=<?= htmlspecialchars($idso) ?>">
                                             <i class="far fa-calendar-check"></i>
                                          </a>
                                          <a href="deletetally.php?id=<?= $tampil['idtally']; ?>&idso=<?= $idso ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Hapus" onclick="return confirm('Semua barang yang ada di tally akan kembali ke stock, apa anda yakin ?')">
                                             <i class="fas fa-minus-square"></i>
                                          </a>
                                       <?php
                                          break;

                                       case "Approved":
                                          // Display if stat is Approved
                                       ?>
                                          <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Scan Denied">
                                             <i class="fas fa-tasks"></i>
                                          </a>
                                          <a class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Unapprove" href="unapprovetally.php?id=<?= htmlspecialchars($tampil['idtally']) ?>&idso=<?= htmlspecialchars($idso) ?>'">
                                             <i class="fas fa-calendar-times"></i>
                                          </a>
                                          <a href="#" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Approved">
                                             <i class="fas fa-minus-square"></i>
                                          </a>
                                       <?php
                                          break;

                                       case "DO":
                                       case "Rejected":
                                          // Display if stat is DO or Rejected
                                       ?>
                                          <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Scan Denied">
                                             <i class="fas fa-tasks"></i>
                                          </a>
                                          <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?= ($stat == 'DO') ? 'DO' : 'Rejected'; ?>">
                                             <i class="fas fa-calendar"></i>
                                          </a>
                                          <a href="#" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="bottom" title="<?= ($stat == 'DO') ? 'DO' : 'Rejected'; ?>">
                                             <i class="fas fa-minus-square"></i>
                                          </a>
                                 <?php
                                          break;

                                       default:
                                          echo "Unknown stat value: $stat";
                                          break;
                                    }
                                 } else {
                                    echo "Error: " . mysqli_error($conn);
                                 }
                                 ?>
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
include "../footer.php";
?>