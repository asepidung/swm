<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$idcarcase = $_GET['idcarcase'] ?? null;
if (!$idcarcase) {
   echo "<script>alert('ID Carcase tidak ditemukan!'); window.location='carcase.php';</script>";
   exit;
}

// Menghitung jumlah detail carcase untuk mendapatkan nomor urut berikutnya
$query = "SELECT COUNT(*) AS total FROM carcasedetail WHERE idcarcase = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idcarcase);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nextNumber = $row['total'] + 1;

$stmt->close();
?>

<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Input Detail Carcase</h3>
                     <span class="float-right text-primary">
                        <h5><?= $nextNumber ?></h5>
                     </span>
                  </div>
                  <div class="card-body">
                     <form id="carcaseDetailForm" action="prosescarcasedetail.php" method="POST">
                        <input type="hidden" name="idcarcase" value="<?= htmlspecialchars($idcarcase); ?>">
                        <div class="form-group">
                           <select class="form-control" id="breed" name="breed" required>
                              <option value="">Pilih Ras</option>
                              <option value="STEER">STEER</option>
                              <option value="HEIFER">HEIFER</option>
                              <option value="COW">COW</option>
                              <option value="LIMOUSIN">LIMOUSIN</option>
                           </select>
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="berat" name="berat" placeholder="Berat">
                        </div>
                        <div class="form-group">
                           <input type="number" class="form-control" id="eartag" name="eartag" maxlength="5" required placeholder="Eartag" autofocus>
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="carcase1" name="carcase1" required placeholder="Carcase A">
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="carcase2" name="carcase2" required placeholder="Carcase B">
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="hides" name="hides" required placeholder="Hides">
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="tail" name="tail" placeholder="Tails">
                        </div>

                        <div class="form-group text-center">
                           <?php if (isset($_SESSION['last_iddetail'])): ?>
                              <a href="editcarcasedetail.php?iddetail=<?= $_SESSION['last_iddetail'] ?>" class="btn btn-secondary"><i class="fas fa-step-backward"></i> Prev</a>
                           <?php endif; ?>
                           <button type="submit" name="simpan" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                           <button type="submit" name="next" class="btn btn-primary"><i class="fas fa-step-forward"></i> Next</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<script>
   document.title = "Input Detail Carcase";
</script>

<?php include "../footer.php"; ?>