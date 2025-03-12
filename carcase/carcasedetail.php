<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Validasi idcarcase
$idcarcase = intval($_GET['idcarcase'] ?? 0);
if ($idcarcase <= 0) {
   echo "<script>alert('ID Carcase tidak ditemukan!'); window.location='carcase.php';</script>";
   exit();
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
                        <h5><?= htmlspecialchars($nextNumber) ?></h5>
                     </span>
                  </div>
                  <div class="card-body">
                     <form id="carcaseDetailForm" action="prosescarcasedetail.php" method="POST">
                        <input type="hidden" name="idcarcase" value="<?= htmlspecialchars($idcarcase); ?>">

                        <!-- Dropdown Breed -->
                        <div class="form-group">
                           <select class="form-control" id="breed" name="breed" required>
                              <option value="">Pilih Ras</option>
                              <?php
                              $breeds = ['STEER', 'HEIFER', 'COW', 'LIMOUSIN'];
                              foreach ($breeds as $breed) {
                                 $selected = isset($_SESSION['breed']) && $_SESSION['breed'] === $breed ? 'selected' : '';
                                 echo "<option value=\"$breed\" $selected>$breed</option>";
                              }
                              ?>
                           </select>
                        </div>

                        <!-- Input Fields -->
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="berat" name="berat" placeholder="Berat (Opsional)">
                        </div>
                        <div class="form-group">
                           <input type="text" class="form-control" id="eartag" name="eartag" maxlength="6" required placeholder="Eartag" autofocus>
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="carcase1" name="carcase1" required placeholder="Carcase A">
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="carcase2" name="carcase2" required placeholder="Carcase B">
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="hides" name="hides" placeholder="Hides (Maksimal 100)">
                        </div>
                        <div class="form-group">
                           <input type="number" step="0.01" class="form-control" id="tail" name="tail" placeholder="Tail (Maksimal 100)">
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="form-group text-center">
                           <?php if (isset($_SESSION['last_iddetail'])): ?>
                              <a href="editcarcasedetail.php?iddetail=<?= htmlspecialchars($_SESSION['last_iddetail']) ?>" class="btn btn-secondary"><i class="fas fa-step-backward"></i> Prev</a>
                           <?php endif; ?>
                           <!-- Tombol "Simpan" diarahkan ke halaman datacarcase.php -->
                           <button type="submit" name="simpan" value="save" class="btn btn-success" id="btnSimpan"><i class="fas fa-save"></i> Simpan</button>
                           <!-- Tombol "Next" melanjutkan input berikutnya -->
                           <button type="submit" name="next" value="next" class="btn btn-primary"><i class="fas fa-step-forward"></i> Next</button>
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
   document.querySelectorAll("input[type='number']").forEach(input => {
      input.addEventListener("change", function() {
         let maxWeight;
         const fieldName = this.getAttribute("name");

         // Tetapkan batas maksimal untuk masing-masing field
         if (fieldName === "berat") {
            maxWeight = 1000.00; // Batas untuk berat total
         } else if (fieldName === "hides" || fieldName === "tail") {
            maxWeight = 50.00; // Batas untuk hides dan tail
         } else {
            maxWeight = 250.00; // Batas untuk karkas
         }

         const minWeight = 0.01; // Batas minimal untuk semua field

         // Validasi input
         if (this.value > maxWeight) {
            alert(`Nilai untuk ${fieldName} terlalu besar. Maksimal adalah ${maxWeight}.`);
            this.focus();
            this.value = ""; // Kosongkan input jika melebihi batas
         } else if (this.value < minWeight && this.value !== "") {
            alert(`Nilai untuk ${fieldName} terlalu kecil. Minimal adalah ${minWeight}.`);
            this.focus();
            this.value = ""; // Kosongkan input jika kurang dari batas
         }
      });
   });

   document.getElementById("btnSimpan").addEventListener("click", function(event) {
      if (!confirm("Pastikan Semua data yang kamu isi sudah benar")) {
         event.preventDefault(); // Mencegah form disubmit jika user tidak setuju
      }
   });
</script>

<?php include "../footer.php"; ?>