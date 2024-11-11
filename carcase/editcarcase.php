<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Mendapatkan idcarcase dari URL
$idcarcase = isset($_GET['idcarcase']) ? (int)$_GET['idcarcase'] : 0;

// Query untuk mengambil data carcase dan detailnya
$query_carcase = "SELECT carcase.idcarcase, carcase.killdate, carcase.breed, carcase.idsupplier, supplier.nmsupplier 
                  FROM carcase 
                  JOIN supplier ON carcase.idsupplier = supplier.idsupplier 
                  WHERE carcase.idcarcase = $idcarcase";
$result_carcase = mysqli_query($conn, $query_carcase);
$row_carcase = mysqli_fetch_assoc($result_carcase);

// Query untuk mengambil semua supplier
$query_suppliers = "SELECT idsupplier, nmsupplier FROM supplier";
$result_suppliers = mysqli_query($conn, $query_suppliers);

// Daftar opsi breed
$breeds = ["STEER", "HEIFER", "COW", "LIMOUSIN", "MIX"];

$query_carcasedetail = "SELECT * FROM carcasedetail WHERE idcarcase = $idcarcase";
$result_carcasedetail = mysqli_query($conn, $query_carcasedetail);
?>

<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-12">
               <h1 class="m-0">Edit Carcase - ID: <?php echo $row_carcase['idcarcase']; ?></h1>
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
                     <form id="editCarcaseForm" action="prosesupdate.php" method="post">
                        <h4>Data Carcase</h4>
                        <div class="form-group">
                           <label>Killing Date:</label>
                           <input type="hidden" name="idcarcase" value="<?= $row_carcase['idcarcase'] ?>">
                           <input type="date" class="form-control" name="killdate" value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($row_carcase['killdate']))); ?>">
                        </div>
                        <div class="form-group">
                           <label>Supplier:</label>
                           <select class="form-control" name="idsupplier">
                              <?php while ($supplier = mysqli_fetch_assoc($result_suppliers)) { ?>
                                 <option value="<?php echo $supplier['idsupplier']; ?>"
                                    <?php if ($supplier['idsupplier'] == $row_carcase['idsupplier']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($supplier['nmsupplier']); ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>
                        <div class="form-group">
                           <label>Breed:</label>
                           <select class="form-control" name="breed">
                              <?php foreach ($breeds as $breed) { ?>
                                 <option value="<?php echo $breed; ?>"
                                    <?php if ($breed == $row_carcase['breed']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($breed); ?>
                                 </option>
                              <?php } ?>
                           </select>
                        </div>

                        <table class="table table-bordered table-striped table-sm">
                           <thead class="text-center">
                              <tr>
                                 <th>#</th>
                                 <th>Berat</th>
                                 <th>Eartag</th>
                                 <th>Carcase 1</th>
                                 <th>Carcase 2</th>
                                 <th>Hides</th>
                                 <th>Tails</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              if (mysqli_num_rows($result_carcasedetail) > 0) {
                                 $no = 1;
                                 while ($row_carcasedetail = mysqli_fetch_assoc($result_carcasedetail)) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>" . $no++ . "</td>";
                                    echo "<input type='hidden' name='iddetail[]' value='" . htmlspecialchars($row_carcasedetail['iddetail']) . "'>";
                                    echo "<td><input type='number' class='form-control text-right' name='berat[]' value='" . htmlspecialchars($row_carcasedetail['berat']) . "' required></td>";
                                    echo "<td><input type='text' class='form-control text-center' name='eartag[]' value='" . htmlspecialchars($row_carcasedetail['eartag']) . "' required></td>";
                                    echo "<td><input type='text' class='form-control text-right' name='carcase1[]' value='" . htmlspecialchars($row_carcasedetail['carcase1']) . "' required></td>";
                                    echo "<td><input type='text' class='form-control text-right' name='carcase2[]' value='" . htmlspecialchars($row_carcasedetail['carcase2']) . "' required></td>";
                                    echo "<td><input type='text' class='form-control text-right' name='hides[]' value='" . htmlspecialchars($row_carcasedetail['hides']) . "' required></td>";
                                    echo "<td><input type='text' class='form-control text-right' name='tail[]' value='" . htmlspecialchars($row_carcasedetail['tail']) . "' required></td>";
                                    echo "</tr>";
                                 }
                              } else {
                                 echo "<tr><td colspan='7' class='text-center'>Tidak ada data detail ditemukan</td></tr>";
                              }
                              ?>
                           </tbody>
                        </table>


                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<script>
   // Mengubah judul halaman web
   document.title = "Edit Carcase - ID: <?php echo $row_carcase['idcarcase']; ?>";
</script>
<?php
include "../footer.php";
?>