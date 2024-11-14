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

// Menyimpan pilihan breed ke session
if (isset($_POST['breed'])) {
   $_SESSION['breed'] = $_POST['breed'];
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
                              <option value="STEER" <?= isset($_SESSION['breed']) && $_SESSION['breed'] == 'STEER' ? 'selected' : '' ?>>STEER</option>
                              <option value="HEIFER" <?= isset($_SESSION['breed']) && $_SESSION['breed'] == 'HEIFER' ? 'selected' : '' ?>>HEIFER</option>
                              <option value="COW" <?= isset($_SESSION['breed']) && $_SESSION['breed'] == 'COW' ? 'selected' : '' ?>>COW</option>
                              <option value="LIMOUSIN" <?= isset($_SESSION['breed']) && $_SESSION['breed'] == 'LIMOUSIN' ? 'selected' : '' ?>>LIMOUSIN</option>
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
                           <button type="submit" name="simpan" class="btn btn-success" id="btnSimpan"><i class="fas fa-save"></i> Simpan</button>
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
   // Fokus pada input berikutnya ketika menggunakan tab
   document.querySelectorAll("input, select").forEach((elem) => {
      elem.addEventListener("keydown", function(event) {
         if (event.key === "Enter") {
            const formElements = Array.from(document.querySelectorAll("input, select"));
            const index = formElements.indexOf(event.target);
            if (index !== -1 && index < formElements.length - 1) {
               formElements[index + 1].focus();
               event.preventDefault(); // Mencegah form submit saat tekan Enter
            }
         }
      });
   });

   // Konfirmasi hanya pada tombol "Simpan"
   document.getElementById("btnSimpan").addEventListener("click", function(event) {
      if (!confirm("Pastikan Semua data yang kamu isi sudah benar")) {
         event.preventDefault(); // Mencegah form disubmit jika user tidak setuju
      }
   });
</script>

<?php include "../footer.php"; ?>