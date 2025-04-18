<?php
require "../verifications/auth.php";
require "../konak/conn.php";
require "kdbarangunik.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <!-- left column -->
            <div class="col-md-6">
               <!-- general form elements -->
               <div class="card card-dark mt-3">
                  <div class="card-header">
                     <h3 class="card-title">Input Item Baru</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form method="POST" action="prosesnewbarang.php">
                     <div class=" card-body">
                        <div class="form-group">
                           <label for="kdbarang">Kode</label>
                           <input type="text" class="form-control" name="kdbarang" id="kdbarang" value="<?= $kodeauto; ?>" readonly>
                        </div>
                        <div class="form-group">
                           <label for="nmbarang">Nama Product <span class="text-danger">*</span></label>
                           <input type="text" class="form-control" name="nmbarang" id="nmbarang" placeholder="Gunakan Huruf BESAR !!!" required>
                        </div>
                        <div class="form-group">
                           <label for="cut">Kategori <span class="text-danger">*</span></label>
                           <select class="form-control" name="cut" id="cut" required>
                              <option value="">Pilih Kategori</option>
                              <option value="1">PRIME CUT</option>
                              <option value="2">SECONDARY CUT</option>
                              <option value="3">BONES</option>
                              <option value="4">OFFAL</option>
                              <option value="5">FAT</option>
                              <option value="6">GRADES</option>
                              <option value="7">MATERIAL SUPPORT</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group mr-3 text-right">
                        <button type="submit" class="btn bg-gradient-primary">Submit</button>
                     </div>
               </div>
               <!-- /.card-body -->

               </form>
            </div>
            <!-- /.card -->
         </div>
      </div>
   </section>
</div><!-- /.container-fluid -->
<!-- /.content -->
<!-- </div> -->
<!-- /.content-wrapper -->

<?php
include "../footer.php";
include "../footnote.php";
?>