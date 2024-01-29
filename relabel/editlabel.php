<?php
session_start();
// Tidak perlu memeriksa session untuk halaman ini

require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   // Periksa apakah parameter kdbarcode telah diterima
   if (isset($_GET['kdbarcode'])) {
      $kdbarcode = $_GET['kdbarcode'];

      // Query untuk mengecek apakah kdbarcode ada di tabel stock
      $checkStockQuery = "SELECT * FROM stock WHERE kdbarcode = '$kdbarcode'";
      $checkStockResult = mysqli_query($conn, $checkStockQuery);

      if (!$checkStockResult) {
         die("Query Error: " . mysqli_error($conn));
      }

      // Periksa jumlah baris hasil query
      $rowCount = mysqli_num_rows($checkStockResult);

      if ($rowCount == 0) {
         // Data barang tidak ditemukan
         echo '<script>alert("Data Barang Tidak Ditemukan");</script>';
         echo '<script>window.location.href = "index.php";</script>'; // Redirect ke halaman index.php menggunakan JavaScript
         exit(); // Hentikan eksekusi script setelah redirect
      } else {
         // Data barang ditemukan, lanjutkan dengan menampilkan data dari tabel stock
         $row = mysqli_fetch_assoc($checkStockResult);
?>
         <div class="content-wrapper">
            <div class="content">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-lg-4 mt-2">
                        <div class="card">
                           <div class="card-body">
                              <form method="POST" action="cetakrelabel.php" onsubmit="submitForm(event)">
                                 <!-- ... -->
                                 <div class="form-group">
                                    <label>Product <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <select class="form-control" name="idbarang" id="idbarang">
                                          <?php
                                          $querybarang = "SELECT * FROM barang ORDER BY nmbarang ASC";
                                          $resultbarang = mysqli_query($conn, $querybarang);
                                          while ($barangRow = mysqli_fetch_assoc($resultbarang)) {
                                             $idbarang = $barangRow['idbarang'];
                                             $nmbarang = $barangRow['nmbarang'];
                                             $selectedbarang = ($idbarang == $row['idbarang']) ? "selected" : "";
                                             echo "<option value=\"$idbarang\" $selectedbarang>$nmbarang</option>";
                                          }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label>Grade <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <select class="form-control" name="idgrade[]" id="idgrade">
                                          <?php
                                          $querygrade = "SELECT * FROM grade ORDER BY nmgrade ASC";
                                          $resultgrade = mysqli_query($conn, $querygrade);
                                          while ($gradeRow = mysqli_fetch_assoc($resultgrade)) {
                                             $idgrade = $gradeRow['idgrade'];
                                             $nmgrade = $gradeRow['nmgrade'];
                                             $selectedgrade = ($idgrade == $row['idgrade']) ? "selected" : "";
                                             echo "<option value=\"$idgrade\" $selectedgrade>$nmgrade</option>";
                                          }
                                          ?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label>Packed Date<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                       <input type="date" class="form-control" name="packdate" id="packdate" required value="<?= $row['pod']; ?>">
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label>Expired Date</label>
                                    <div class="input-group">
                                       <input type="date" class="form-control" name="exp" id="exp">
                                    </div>
                                 </div>
                                 <div class="form-check">
                                    <input class="form-check-input" checked type="checkbox" name="tenderstreach" id="tenderstreach">
                                    <label class="form-check-label">Aktifkan Tenderstreatch</label>
                                 </div>
                                 <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="pembulatan" id="pembulatan">
                                    <label class="form-check-label">1 Digit Koma</label>
                                 </div>
                                 <!-- ... -->
                                 <input type="hidden" name="idusers" id="idusers" value="<?= $idusers ?>">
                                 <!-- <input type="hidden" name="product" id="product"> -->
                                 <input type="hidden" name="kdbarcode" id="kdbarcode" value="<?= $kdbarcode ?>">
                                 <div class="form-group">
                                    <label class="mt-2">Weight & Pcs <span class="text-danger">*</span></label>
                                    <div class="input-group col-lg-4">
                                       <?php
                                       if ($row['pcs'] > 0) {
                                          $qty = $row['qty'] . '/' . $row['pcs'];
                                       } else {
                                          $qty = $row['qty'];
                                       }
                                       ?>
                                       <input type="text" class="form-control" name="qty" id="qty" placeholder="Weight & Pcs" required value="<?= $qty; ?>" autofocus>
                                    </div>
                                 </div>
                                 <button type="submit" class="btn btn-block bg-gradient-primary" name="submit">Print</button>
                              </form>
                           </div>
                        </div>
                        <!-- /.card -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
<?php
      }
   } else {
      echo "Parameter kdbarcode tidak ditemukan.";
   }
} else {
   echo "Form tidak dikirimkan.";
}

require "../footer.php";
?>