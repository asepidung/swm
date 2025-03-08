<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "../header.php";
require "../navbar.php";
require "../mainsidebar.php";

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
   // Periksa apakah parameter kdbarcode telah diterima
   if (isset($_GET['kdbarcode'])) {
      $kdbarcode = $_GET['kdbarcode'];
      $idtally = $_GET['idtally'];
      // Query untuk mengecek apakah kdbarcode ada di tabel tallydetail
      $checkStockQuery = "SELECT tallydetail.*, barang.nmbarang, grade.nmgrade
                           FROM tallydetail
                           LEFT JOIN barang ON tallydetail.idbarang = barang.idbarang
                           LEFT JOIN grade ON tallydetail.idgrade = grade.idgrade
                           WHERE barcode = '$kdbarcode'";

      $checkStockResult = mysqli_query($conn, $checkStockQuery);

      if (!$checkStockResult) {
         die("Query Error: " . mysqli_error($conn));
      }

      // Periksa jumlah baris hasil query
      $rowCount = mysqli_num_rows($checkStockResult);

      if ($rowCount == 0) {
         // Data barang tidak ditemukan
         echo '<script>alert("Data Barang Tidak Ditemukan");</script>';
         header("location: index.php"); // Kembali ke halaman index.php
      } else {
         // Data barang ditemukan, lanjutkan dengan menampilkan data dari tabel tallydetail
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
                                 <input type="hidden" name="idtally" value="<?= $idtally ?>">
                                 <input type="hidden" name="idtallydetail" value="<?= $row['idtallydetail'] ?>">
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="hidden" name="idbarang" value="<?= $row['idbarang'] ?>">
                                       <input type="text" class="form-control" value="<?= $row['nmbarang'] ?>" readonly>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="hidden" name="idgrade" value="<?= $row['idgrade'] ?>">
                                       <input type="text" class="form-control" value="<?= $row['nmgrade'] ?>" readonly>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="hidden" name="xpackdate" id="xpackdate" value="<?= $row['pod']; ?>">
                                       <input type="date" class="form-control" name="packdate" id="packdate" required value="<?= $row['pod']; ?>">
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <div class="input-group">
                                       <input type="date" class="form-control" name="exp" id="exp" readonly>
                                    </div>
                                 </div>
                                 <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tenderstreach" id="tenderstreach">
                                    <label class="form-check-label">Aktifkan Tenderstreatch</label>
                                 </div>
                                 <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="pembulatan" id="pembulatan">
                                    <label class="form-check-label">1 Digit Koma</label>
                                 </div>
                                 <input type="hidden" name="idusers" id="idusers" value="<?= $idusers ?>">
                                 <input type="hidden" name="kdbarcode" id="kdbarcode" value="<?= $kdbarcode ?>">
                                 <div class="form-group mt-2">
                                    <div class="row">
                                       <div class="col-8">
                                          <input type="text" class="form-control" name="qty" value="<?= $row['weight']; ?>" readonly>
                                       </div>
                                       <div class="col">
                                          <input type="hidden" name="xpcs" value="<?= $row['pcs']; ?>">
                                          <input type="number" name="pcs" class="form-control" value="<?= $row['pcs']; ?>">
                                       </div>
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