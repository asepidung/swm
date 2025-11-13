<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

$idusers = $_GET['id'];

// Ambil data role dari tabel role berdasarkan idusers
$ambildata = mysqli_query($conn, "SELECT * FROM role WHERE idusers = $idusers");
$tampil = mysqli_fetch_assoc($ambildata);

// Tambahkan role 'stock' di sini
$roles = [
   'cattle' => 'cattle', // 🔹 DITAMBAHKAN BARU
   'produksi' => 'Produksi',
   'warehouse' => 'Warehouse',
   'stock' => 'Stock',
   'distributions' => 'Distributions',
   'purchase_module' => 'Purchase Module',
   'sales' => 'Sales',
   'finance' => 'Finance',
   'data_report' => 'Data Report',
   'master_data' => 'Master Data'
];
?>

<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-6">
               <div class="card mt-3">
                  <div class="card-body register-card-body">
                     <p class="login-box-msg">User Akses</p>
                     <form action="updateuser.php" method="post">
                        <input type="hidden" name="idusers" value="<?= $tampil['idusers'] ?>">
                        <div class="form-group">
                           <label>Pilih Menu yang Bisa Diakses:</label><br>
                           <?php foreach ($roles as $key => $label) : ?>
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" name="menu_access[]" id="<?= $key ?>" value="<?= $key ?>" <?= $tampil[$key] == 1 ? 'checked' : '' ?>>
                                 <label class="custom-control-label" for="<?= $key ?>"><?= $label ?></label>
                              </div>
                           <?php endforeach; ?>
                        </div>
                        <div class="row">
                           <div class="col-4">
                              <button type="submit" class="btn btn-primary btn-block">Update</button>
                           </div>
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
   document.title = "Edit User";
</script>

<?php
include "../footer.php";
?>