<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

if (isset($_POST['submit'])) {
   $kdbarcode = $_POST['kdbarcode'];

   // Query untuk mengecek apakah kdbarcode ada di tabel stock
   $checkStockQuery = "SELECT * FROM stock WHERE kdbarcode = '$kdbarcode'";
   $checkStockResult = mysqli_query($conn, $checkStockQuery);

   if ($checkStockResult && mysqli_num_rows($checkStockResult) > 0) {
      // Kdbarcode ada di tabel stock, lanjutkan ke halaman editrelabel2.php
      header("location: editrelabel2.php?kdbarcode=$kdbarcode");
      exit;
   } else {
      // Kdbarcode tidak ada di tabel stock, tampilkan pesan
      echo "Data tidak ada di stock.";
   }
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
            <div class="col-lg-3">
               <div class="card">
                  <div class="card-body">
                     <form method="POST" action="editrelabel2.php">
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
            <!-- tampilan -->
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