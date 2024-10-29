<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";

include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Mendapatkan ID Carcase dari URL atau mengalihkan jika ID tidak ada
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
$nextNumber = $row['total'] + 1; // Nomor berikutnya

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
                        <h5><?= $nextNumber ?></h5> <!-- Menampilkan nomor input berikutnya -->
                     </span>
                  </div>
                  <div class="card-body">
                     <form id="carcaseDetailForm" action="prosescarcasedetail.php" method="POST">
                        <!-- Hidden field untuk idcarcase -->
                        <input type="hidden" name="idcarcase" value="<?= htmlspecialchars($idcarcase); ?>">

                        <!-- Field untuk berat -->
                        <div class="form-group">
                           <input type="text" class="form-control" id="berat" name="berat" placeholder="Berat" autofocus>
                        </div>

                        <!-- Field untuk eartag -->
                        <div class="form-group">
                           <input type="text" class="form-control" id="eartag" name="eartag" maxlength="5" required placeholder="Eartag">
                        </div>
                        <div class="form-group">
                           <input type="text" class="form-control" id="carcase1" name="carcase1" required placeholder="Carcase A">
                        </div>

                        <div class="form-group">
                           <input type="text" class="form-control" id="carcase2" name="carcase2" required placeholder="Carcase B">
                        </div>

                        <!-- Modal untuk Peringatan -->
                        <div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel" aria-hidden="true">
                           <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title" id="warningModalLabel">Peringatan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                    </button>
                                 </div>
                                 <div class="modal-body">
                                    Selisih Karkas Mencurigakan, Pastikan data timbang benar.
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                 </div>
                              </div>
                           </div>
                        </div>


                        <!-- Field untuk hides -->
                        <div class="form-group">
                           <input type="text" class="form-control" id="hides" name="hides" required placeholder="Hides">
                        </div>

                        <!-- Field untuk tail -->
                        <div class="form-group">
                           <input type="text" class="form-control" id="tail" name="tail" placeholder="Tails">
                        </div>

                        <!-- Tombol Simpan dan Next -->
                        <div class="form-group text-right">
                           <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                           <button type="submit" name="next" class="btn btn-primary">Next</button>
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
   document.addEventListener("DOMContentLoaded", function() {
      const carcase1 = document.getElementById("carcase1");
      const carcase2 = document.getElementById("carcase2");
      const hides = document.getElementById("hides");

      function checkDifference() {
         const value1 = parseFloat(carcase1.value) || 0;
         const value2 = parseFloat(carcase2.value) || 0;

         // Pastikan kedua nilai terisi sebelum melakukan pengecekan
         if (carcase1.value && carcase2.value) {
            if (Math.abs(value1 - value2) >= 10) {
               $('#warningModal').modal('show');
            }
         }
      }

      // Tambahkan event listener untuk kolom hides
      hides.addEventListener("focus", checkDifference);
   });
</script>



<?php include "../footer.php"; ?>