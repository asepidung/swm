<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("Location: ../verifications/login.php");
   exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Mengamankan input id
$query = "SELECT p.deliveryat, p.nopoproduct, s.nmsupplier, s.idsupplier, p.idpoproduct 
FROM poproduct p 
JOIN supplier s ON p.idsupplier = s.idsupplier 
WHERE p.idpoproduct = $id";
$result = mysqli_query($conn, $query);

if (!$result) {
   die("Query error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
   echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
   include "../footer.php";
   exit();
}

$row = mysqli_fetch_assoc($result);
?>
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 mt-3">
               <form method="POST" action="inputgr.php">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-12 col-md-6">
                              <div class="form-group">
                                 <label for="deliveryat">Receiving Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="deliveryat" id="deliveryat" value="<?= htmlspecialchars($row['deliveryat']) ?>" required>
                                 </div>
                              </div>
                           </div>
                           <div class="col-12 col-md-6">
                              <div class="form-group">
                                 <label for="nmsupplier">Supplier Name <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['nmsupplier']) ?>" required readonly>
                                    <input type="hidden" name="idsupplier" id="idsupplier" value="<?= htmlspecialchars($row['idsupplier']) ?>">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12 col-md-6">
                              <div class="form-group">
                                 <label for="idnumber">Supplier Transaction Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="idnumber" id="idnumber" placeholder="Biarkan Kosong Jika Tidak Ada">
                                 </div>
                              </div>
                           </div>
                           <div class="col-12 col-md-6">
                              <div class="form-group">
                                 <label for="nopoproduct">PO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['nopoproduct']) ?>" readonly>
                                    <input type="hidden" name="idpoproduct" id="idpoproduct" value="<?= htmlspecialchars($row['idpoproduct']) ?>">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <div class="form-group">
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="Catatan Untuk BTB">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <table class="table table-striped table-bordered table-sm">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Item Descriptions</th>
                                 <th>Order Quantity</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $no = 1;

                              // Query untuk mengambil data dari tabel poproductdetail berdasarkan idpoproduct
                              $query = "SELECT pd.qty, b.nmbarang 
                              FROM poproductdetail pd 
                              JOIN barang b ON pd.idbarang = b.idbarang 
                              WHERE pd.idpoproduct = $id";
                              $ambildata = mysqli_query($conn, $query);

                              // Mengecek apakah query berhasil dijalankan
                              if (!$ambildata) {
                                 die("Query error: " . mysqli_error($conn));
                              }

                              // Menampilkan data dari query
                              while ($tampil = mysqli_fetch_assoc($ambildata)) { ?>
                                 <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($tampil['nmbarang']); ?></td>
                                    <td class="text-right"><?= number_format($tampil['qty'], 2); ?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>

                        <div class="row mt-2">
                           <div class="col-12">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')">Proses GR</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
</div>

<script>
   // Mengubah judul halaman web
   document.title = "New GR";
</script>

<?php
include "../footer.php";
?>