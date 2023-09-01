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
               <a href="newrawmate.php"><button type="button" class="btn btn-info"> Material Baru</button></a>
            </div>
         </div>
      </div>
   </div>

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-8">
               <div class="card">
                  <div class="card-body">
                     <div class="col">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>#</th>
                                 <th>Kode</th>
                                 <th>Nama Material</th>
                                 <th>Actions</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $no = 1;
                              $ambildata = mysqli_query($conn, "SELECT * FROM rawmate");
                              while ($tampil = mysqli_fetch_array($ambildata)) {
                                 $idrawmate = $tampil['idrawmate'];
                              ?>
                                 <tr class="text-right">
                                    <td class="text-center"><?= $no; ?></td>
                                    <td class="text-center"><?= $tampil['kdrawmate']; ?></td>
                                    <td class="text-left"><?= $tampil['nmrawmate']; ?></td>
                                    <td class="text-center">
                                       <a href="editrawmate.php?idrawmate=<?= $tampil['idrawmate']; ?>" class="text-succes mx-auto p-2"><i class="fas fa-pencil-alt"></i></a>
                                       <a href="deleterawmate.php?idrawmate=<?= $tampil['idrawmate']; ?>" class="text-danger mx-auto p-2" onclick="return confirm('apakah anda yakin ingin menghapus rawmate ini?')"><i class="fas fa-minus-square"></i></a>
                                    </td>
                                 </tr>
                              <?php $no++;
                              } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<script>
   document.title = "RAW MATERIAL";
</script>
<?php
include "../footer.php";
include "../footnote.php";
?>