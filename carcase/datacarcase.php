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
         <div class="row mb-2">
            <div class="col-12 col-md-2 mb-2">
               <a href="newkill.php">
                  <button type="button" class="btn btn-sm btn-outline-primary btn-block"><i class="fas fa-plus"></i> Baru</button>
               </a>
            </div>
         </div>
      </div>
   </div>
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <?php
                     include "calculatorcarcase.php";
                     ?>
                     <table id="example1" class="table table-bordered table-striped table-sm text-right">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Killing Date</th>
                              <th>Supplier</th>
                              <th>Berat &Sigma;</th>
                              <th>Head &Sigma;</th>
                              <th>Carcase &Sigma;</th>
                              <th>Offal</th>
                              <th>Hides &Sigma;</th>
                              <th>Tails &Sigma;</th>
                              <th>Carcase %</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           if (mysqli_num_rows($result) > 0) {
                              $no = 1;
                              while ($row = mysqli_fetch_assoc($result)) {
                                 // Menghitung persentase carcase
                                 $carcase_percentage = 0;
                                 if ($row['total_berat'] > 0) {
                                    $carcase_percentage = (($row['total_carcase']) / ($row['total_berat'])) * 100; // Total carcase = total_carcase1 + total_carcase2
                                 } ?>
                                 <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= htmlspecialchars(date('d-M-Y', strtotime($row['killdate'])))  ?></td>
                                    <td class="text-left"><?= htmlspecialchars($row['nmsupplier']) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['total_berat'], 2)) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['total_eartag']) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['total_carcase'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['total_carcase_tail'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['total_hides'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($row['total_tails'], 2)) ?></td>
                                    <td><?= number_format($carcase_percentage, 2) ?></td>
                                    <td class="text-center">
                                       <a href="lihatcarcase.php?idcarcase=<?= $row['idcarcase'] ?>" class="btn btn-info btn-sm" title="Lihat"><i class="fas fa-eye"></i></a>
                                       <a href="editcarcase.php?idcarcase=<?= $row['idcarcase'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-paper-plane"></i></a>
                                       <a href="deletecarcase.php?idcarcase=<?= $row['idcarcase'] ?>" onclick="return confirmDelete()" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-minus-square"></i></a>
                                    </td>

                                 </tr>
                           <?php }
                           } else {
                              echo "<tr><td colspan='12' class='text-center'>Tidak ada data ditemukan</td></tr>";
                           }
                           ?>
                        </tbody>

                        <script>
                           function confirmDelete() {
                              return confirm("Apakah Anda yakin ingin menghapus data ini?");
                           }
                        </script>

                     </table>

                  </div>
               </div>
            </div>
         </div>
      </div>

   </section>
</div>
<script>
   // Mengubah judul halaman web
   document.title = "Data Killing";
</script>
<?php
include "../footer.php";
?>