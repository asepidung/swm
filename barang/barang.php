<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <a href="newbarang.php" class="btn bg-gradient-success btn-md shadow-sm">
                  <i class="fas fa-plus-circle"></i> Product Baru
               </a>
            </div>
         </div>
      </div>
   </div>

   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <div class="card">
                  <div class="card-body">
                     <div class="col">
                        <table id="example1" class="table table-bordered table-striped table-hover table-sm">
                           <thead class="text-center bg-light">
                              <tr>
                                 <th>#</th>
                                 <th>Kode</th>
                                 <th>Nama Product</th>
                                 <th>Kategori</th>
                                 <th>J Karton</th>
                                 <th>Jml DryLog</th>
                                 <th>Jenis Plastik</th>
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
                              ?>
                                 <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center"><?= htmlspecialchars($tampil['kdbarang']); ?></td>
                                    <td class="text-left"><?= htmlspecialchars($tampil['nmbarang']); ?></td>
                                    <td class="text-center"><?= htmlspecialchars($tampil['nmcut']); ?></td>
                                    <td class="text-center"><?= htmlspecialchars($tampil['karton'] ?? ''); ?></td>
                                    <td class="text-center"><?= htmlspecialchars($tampil['drylog'] ?? ''); ?></td>
                                    <td class="text-center"><?= htmlspecialchars($tampil['plastik'] ?? ''); ?></td>
                                    <td class="text-center">
                                       <a href="editbarang.php?idbarang=<?= $tampil['idbarang']; ?>"
                                          class="btn btn-sm btn-warning mx-1" title="Edit">
                                          <i class="fas fa-edit"></i>
                                       </a>
                                       <a href="deletebarang.php?idbarang=<?= $tampil['idbarang']; ?>"
                                          class="btn btn-sm btn-danger mx-1"
                                          title="Hapus"
                                          onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                          <i class="fas fa-trash-alt"></i>
                                       </a>
                                    </td>
                                 </tr>
                              <?php } ?>
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