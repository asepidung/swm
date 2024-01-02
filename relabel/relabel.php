<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// check if idboning is set in $_GET array
$idusers = $_SESSION['idusers'];
// Mengambil daftar barang
$query = "SELECT * FROM barang ORDER BY nmbarang ASC";
$result = mysqli_query($conn, $query);
$barangOptions = "";
while ($row = mysqli_fetch_assoc($result)) {
   $idbarang = $row['idbarang'];
   $nmbarang = $row['nmbarang'];
   $barangOptions .= "<option value=\"$idbarang\">$nmbarang</option>";
}
?>
<div class="content-wrapper">
   <!-- /.content-header -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-n4">
            <div class="col">
               <marquee behavior="scrolling" direction="">
                  <h2>RELABEL</h2>
               </marquee>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <div class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-4">
               <div class="card">
                  <div class="card-body">
                     <form method="POST" action="editrelabel.php">
                        <div class="form-group">
                           <label>Scan Here</label>
                           <input type="text" class="form-control text-center" name="kdbarcode" id="kdbarcode" autofocus>
                        </div>
                        <button type="submit" class="btn btn-block bg-gradient-primary" name="submit">Ubah</button>
                     </form>
                  </div>
               </div>
               <!-- /.card -->
            </div>
            <div class="col-lg-8">
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
                              <th>Author</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT r.*, b.nmbarang, u.fullname, g.nmgrade FROM relabel r
                                                   INNER JOIN barang b ON r.idbarang = b.idbarang
                                                   INNER JOIN users u ON r.iduser = u.idusers
                                                   LEFT JOIN grade g ON r.idgrade = g.idgrade
                                                   ORDER BY r.kdbarcode DESC");
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
                                 <td><?= $fullname; ?></td>
                              </tr>
                           <?php
                              $no++;
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               </div>
               <!-- /.card -->
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </div>
</div>
<script>
   document.title = "Relabel";
</script>
<?php
// require "../footnote.php";
require "../footer.php";
?>