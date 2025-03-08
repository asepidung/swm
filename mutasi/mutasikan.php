<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Validasi parameter ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Pastikan ID valid sebelum menampilkan formulir
if ($id <= 0) {
   echo "Invalid ID. <a href='javascript:history.back()'>Kembali</a>";
   exit;
}
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-6 mt-3">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <form method="POST" action="ubahkode.php">
                        <div class="form-group">
                           <label for="tindakan">Pilih Tindakan</label>
                           <div class="input-group">
                              <input type="hidden" name="idmutasi" value="<?= $id ?>">
                              <select class="form-control" name="tindakan" id="tindakan">
                                 <option value="1">Mutasi Ke <strong>J01</strong></option>
                                 <option value="2">Mutasi Ke <strong>J02</strong></option>
                                 <option value="5">Mutasi Ke <strong>J03</strong></option>
                                 <option value="3">Mutasi Ke <strong>P01</strong></option>
                                 <option value="4">Mutasi Ke <strong>P02</strong></option>
                                 <option value="6">Mutasi Ke <strong>P03</strong></option>
                              </select>
                           </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Proses</button>
                     </form>
                  </div>
                  <!-- /.card-body -->
               </div>
               <!-- /.card -->
            </div>
            <!-- /.col -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
   document.title = "Mutasi";
</script>

<?php
// require "../footnote.php";
include "../footer.php";
?>