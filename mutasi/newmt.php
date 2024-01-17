<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

?>
<div class="content-wrapper">
   <section class="content">
      <div class="container">
         <div class="row">
            <div class="col mt-3">
               <form method="POST" action="prosesmutasi.php">
                  <div class="card">
                     <div class="card-body">
                        <div class="col">
                           <div class="form-group">
                              <label for="tglst">Mutasi Date <span class="text-danger">*</span></label>
                              <input type="date" class="form-control" name="tglmutasi" id="tglmutasi" required>
                           </div>
                        </div>
                        <div class="col">
                           <div class="form-group">
                              <label for="note">Catatan</label>
                              <input type="text" class="form-control" name="note">
                           </div>
                        </div>
                        <div class="col">
                           <button type="submit" name="submit" class="btn bg-gradient-success">Start Mutasi</button>
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
   document.title = "New Mutasi";
</script>
<?php
// require "../footnotes.php";
include "../footer.php";
?>