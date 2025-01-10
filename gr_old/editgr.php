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
$query = "SELECT g.receivedate, g.idnumber, g.note, s.nmsupplier, s.idsupplier, p.nopoproduct, p.idpoproduct 
FROM gr g 
JOIN supplier s ON g.idsupplier = s.idsupplier 
JOIN poproduct p ON g.idpo = p.idpoproduct
WHERE g.idgr = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
   echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
   include "../footer.php";
   exit();
}

$row = $result->fetch_assoc();
?>
<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12 mt-3">
               <form method="POST" action="updategr.php">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-12 col-md-6">
                              <input type="hidden" name="idgr" value="<?= htmlspecialchars($id) ?>">
                              <div class="form-group">
                                 <label for="receivedate">Receiving Date <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="date" class="form-control" name="receivedate" id="receivedate" value="<?= htmlspecialchars($row['receivedate']) ?>" required>
                                 </div>
                              </div>
                           </div>
                           <div class="col-12 col-md-6">
                              <div class="form-group">
                                 <label for="nmsupplier">Supplier Name <span class="text-danger">*</span></label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['nmsupplier']) ?>" required readonly>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12 col-md-6">
                              <div class="form-group">
                                 <label for="idnumber">Supplier Transaction Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="idnumber" id="idnumber" value="<?= htmlspecialchars($row['idnumber']) ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="col-12 col-md-6">
                              <div class="form-group">
                                 <label for="nopoproduct">PO Number</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['nopoproduct']) ?>" readonly>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <div class="form-group">
                                 <label for="note">Note</label>
                                 <div class="input-group">
                                    <input type="text" class="form-control" name="note" id="note" value="<?= htmlspecialchars($row['note']) ?>">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div class="row mt-2">
                           <div class="col-12">
                              <button type="submit" class="btn btn-block bg-gradient-primary" name="submit" onclick="return confirm('Pastikan Data Yang Diisi Sudah Benar')"><i class="fas fa-paper-plane"></i> Update Data</button>
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
   document.title = "Edit GR";
</script>

<?php
include "../footer.php";
?>