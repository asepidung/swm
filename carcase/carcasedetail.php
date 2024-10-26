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
?>

<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Input Detail Carcase</h3>
                  </div>
                  <div class="card-body">
                     <form id="carcaseDetailForm" action="prosescarcasedetail.php" method="POST">
                        <!-- Hidden field for idcarcase -->
                        <input type="hidden" name="idcarcase" value="<?= htmlspecialchars($idcarcase); ?>">

                        <!-- Field untuk berat -->
                        <div class="form-group">
                           <label for="berat">Berat</label>
                           <input type="text" class="form-control" id="berat" name="berat">
                        </div>

                        <!-- Field untuk eartag -->
                        <div class="form-group">
                           <label for="eartag">Eartag</label>
                           <input type="text" class="form-control" id="eartag" name="eartag" maxlength="5" required>
                        </div>

                        <!-- Field untuk carcase1 -->
                        <div class="form-group">
                           <label for="carcase1">Carcase A </label>
                           <input type="text" class="form-control" id="carcase1" name="carcase1" required>
                        </div>

                        <!-- Field untuk carcase2 -->
                        <div class="form-group">
                           <label for="carcase2">Carcase B </label>
                           <input type="text" class="form-control" id="carcase2" name="carcase2" required>
                        </div>

                        <!-- Field untuk hides -->
                        <div class="form-group">
                           <label for="hides">Hides </label>
                           <input type="text" class="form-control" id="hides" name="hides" required>
                        </div>

                        <!-- Field untuk tail -->
                        <div class="form-group">
                           <label for="tail">Tail </label>
                           <input type="text" class="form-control" id="tail" name="tail">
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
</script>
<?php include "../footer.php" ?>