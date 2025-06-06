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
         <div class="row mb-2">
            <div class="col-sm-6">
               <a href="newbarang.php"><button type="button" class="btn btn-info"> Product Baru</button></a>
            </div>
         </div>
      </div>
   </div>

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <div class="card">
                  <div class="card-body">
                     <div class="col">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>#</th>
                                 <th>Kode</th>
                                 <th>Nama Product</th>
                                 <th>Kategori</th>
                                 <th>Actions</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $no = 1;
                              $ambildata = mysqli_query($conn, "SELECT barang.*, cuts.nmcut 
                                  FROM barang 
                                  LEFT JOIN cuts ON barang.idcut = cuts.idcut
                                  ORDER BY nmbarang ASC");
                              while ($tampil = mysqli_fetch_array($ambildata)) {
                                 $idbarang = $tampil['idbarang'];
                              ?>

                                 <tr class="text-right">
                                    <td class="text-center"><?= $no; ?></td>
                                    <td class="text-center"><?= $tampil['kdbarang']; ?></td>
                                    <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                                    <td class="text-center"><?= $tampil['nmcut']; ?></td>
                                    <td class="text-center">
                                       <a href="editbarang.php?idbarang=<?= $tampil['idbarang']; ?>" class="btn btn-xs btn-warning"><i class="fas fa-pencil-alt"></i></a>
                                       <a href="deletebarang.php?idbarang=<?= $tampil['idbarang']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('apakah anda yakin ingin menghapus barang ini?')"><i class="fas fa-minus-square"></i></a>
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
   document.title = "DATA BARANG";
</script>
<?php
include "../footer.php";
include "../footnote.php";
?>