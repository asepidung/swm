<?php
require "../verifications/auth.php";
require "../konak/conn.php";

include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Mendapatkan iddetail dari URL
$iddetail = $_GET['iddetail'] ?? null;
if (!$iddetail) {
   echo "<script>alert('ID Detail tidak ditemukan!'); window.location='carcasedetail.php';</script>";
   exit;
}

// Mengambil data dari database berdasarkan iddetail
$query = "SELECT * FROM carcasedetail WHERE iddetail = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $iddetail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
   echo "<script>alert('Detail Karkas tidak ditemukan!'); window.location='carcasedetail.php';</script>";
   exit;
}

$data = $result->fetch_assoc();
$stmt->close();
?>

<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Edit Detail Carcase</h3>
                  </div>
                  <div class="card-body">
                     <form id="editCarcaseDetailForm" action="proseseditcarcasedetail.php" method="POST">
                        <input type="hidden" name="iddetail" value="<?= htmlspecialchars($data['iddetail']); ?>">
                        <input type="hidden" name="idcarcase" value="<?= htmlspecialchars($data['idcarcase']); ?>">

                        <div class="form-group d-flex align-items-center">
                           <label for="eartag" class="mr-3" style="min-width: 120px;">Eartag</label>
                           <input type="number" class="form-control" id="eartag" name="eartag" maxlength="5" required placeholder="Eartag" value="<?= htmlspecialchars($data['eartag']); ?>" autofocus>
                        </div>
                        <div class="form-group d-flex align-items-center">
                           <label for="berat" class="mr-3" style="min-width: 120px;">Berat</label>
                           <input type="number" step="0.01" class="form-control" id="berat" name="berat" placeholder="Berat" value="<?= htmlspecialchars($data['berat']); ?>">
                        </div>
                        <div class="form-group d-flex align-items-center">
                           <label for="carcase1" class="mr-3" style="min-width: 120px;">Carcase A</label>
                           <input type="number" step="0.01" class="form-control" id="carcase1" name="carcase1" required placeholder="Carcase A" value="<?= htmlspecialchars($data['carcase1']); ?>">
                        </div>
                        <div class="form-group d-flex align-items-center">
                           <label for="carcase2" class="mr-3" style="min-width: 120px;">Carcase B</label>
                           <input type="number" step="0.01" class="form-control" id="carcase2" name="carcase2" required placeholder="Carcase B" value="<?= htmlspecialchars($data['carcase2']); ?>">
                        </div>
                        <div class="form-group d-flex align-items-center">
                           <label for="hides" class="mr-3" style="min-width: 120px;">Hides</label>
                           <input type="number" step="0.01" class="form-control" id="hides" name="hides" required placeholder="Hides" value="<?= htmlspecialchars($data['hides']); ?>">
                        </div>
                        <div class="form-group d-flex align-items-center">
                           <label for="tail" class="mr-3" style="min-width: 120px;">Tails</label>
                           <input type="number" step="0.01" class="form-control" id="tail" name="tail" placeholder="Tails" value="<?= htmlspecialchars($data['tail']); ?>">
                        </div>

                        <div class="form-group text-right">
                           <a href="carcasedetail.php?idcarcase=<?= htmlspecialchars($data['idcarcase']); ?>" class="btn btn-secondary">Back</a>
                           <button type="submit" name="update" class="btn btn-success">Update</button>
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
   document.title = "Edit Detail Carcase";
</script>

<?php include "../footer.php"; ?>