<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}

require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

?>
<div class="content-wrapper">
   <!-- Bagian Header Konten -->
   <!-- Bagian Konten Utama -->
   <div class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-4 mt-3">
               <div class="card">
                  <div class="card-body">
                     <form method="GET" action="editlabel.php">
                        <div class="form-group">
                           <!-- <label>Scan Here</label> -->
                           <input type="text" placeholder="Scan Disini" class="form-control text-center" name="kdbarcode" id="kdbarcode" autofocus>
                        </div>
                        <button type="submit" class="btn btn-block bg-gradient-primary">Ubah</button>
                     </form>
                  </div>
               </div>
            </div>
            <div class="col-lg-8 mt-3">
               <div class="card">
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th>#</th>
                              <th>Barcode</th>
                              <th>Product</th>
                              <th>Grade</th>
                              <th>Qty</th>
                              <th>Pcs</th>
                              <th>Time</th>
                              <!-- <th>Author</th> -->
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT r.*, b.nmbarang, u.fullname, g.nmgrade FROM relabel r
                                                   INNER JOIN barang b ON r.idbarang = b.idbarang
                                                   INNER JOIN users u ON r.iduser = u.idusers
                                                   LEFT JOIN grade g ON r.idgrade = g.idgrade
                                                   ORDER BY r.dibuat DESC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                              $fullname = $tampil['fullname'];
                              $nmbarang = $tampil['nmbarang'];
                           ?>
                              <tr class="text-center">
                                 <td><?= $no; ?></td>
                                 <td><?= $tampil['kdbarcode']; ?></td>
                                 <td class="text-left"><?= $tampil['nmbarang']; ?></td>
                                 <td><?= $tampil['nmgrade']; ?></td>
                                 <td><?= $tampil['qty']; ?></td>
                                 <td><?= $tampil['pcs']; ?></td>
                                 <td><?= date('d-M-Y', strtotime($tampil['dibuat'])); ?></td>
                                 <!-- <td><?= $fullname; ?></td> -->
                              </tr>
                           <?php
                              $no++;
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Script untuk mengatur judul halaman -->
<script>
   document.title = "Relabel";
</script>

<?php
require "../footer.php";
?>