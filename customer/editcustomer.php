<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

if (isset($_GET['id'])) {
   $id = $_GET['id'];
   $query = "SELECT c.*, s.nmsegment
             FROM customers c
             JOIN segment s ON c.idsegment = s.idsegment
             WHERE c.idcustomer = $id";
   $result = mysqli_query($conn, $query);
   $customer = mysqli_fetch_assoc($result);
   if (!$customer) {
      echo "<script>alert('Customer tidak ditemukan.'); window.location='customer.php';</script>";
      exit;
   }
}

if (isset($_POST['simpan'])) {
   $nama_customer = $_POST['nama_customer'];
   $alamat = $_POST['alamat'];
   $idsegment = $_POST['idsegment'];
   $top = $_POST['top'];
   $sales_referensi = $_POST['sales_referensi'];
   $pajak = isset($_POST['pajak']) ? true : false;
   $telepon = $_POST['telepon'];
   $email = $_POST['email'];
   $tukarfaktur = isset($_POST['tukarfaktur']) ? true : false;
   $catatan = $_POST['catatan'];

   $query = "UPDATE customers SET 
             nama_customer = '$nama_customer', 
             alamat = '$alamat', 
             idsegment = '$idsegment', 
             top = '$top', 
             sales_referensi = '$sales_referensi', 
             pajak = $pajak, 
             telepon = '$telepon', 
             email = '$email', 
             catatan = '$catatan', 
             tukarfaktur = $tukarfaktur 
             WHERE idcustomer = $id";

   if (mysqli_query($conn, $query)) {
      echo "<script>alert('Data berhasil diperbarui.'); window.location='customer.php';</script>";
      exit;
   } else {
      echo "<script>alert('Maaf, terjadi kesalahan saat memperbarui data.'); window.location='editcustomer.php?id=$id';</script>";
      exit;
   }
}
?>

<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card mt-3">
                  <div class="card-body">
                     <form action="" method="POST">
                        <div class="form-group">
                           <label for="nama_customer">Nama Customer</label>
                           <input type="text" class="form-control" name="nama_customer" id="nama_customer" value="<?= $customer['nama_customer']; ?>" required>
                        </div>
                        <div class="form-group">
                           <label for="alamat">Alamat</label>
                           <textarea class="form-control" name="alamat" id="alamat" required><?= $customer['alamat']; ?></textarea>
                        </div>
                        <div class="form-group">
                           <label for="idsegment">Segment</label>
                           <select class="form-control" name="idsegment" id="idsegment" required>
                              <?php
                              $query = "SELECT * FROM segment";
                              $result = mysqli_query($conn, $query);
                              while ($row = mysqli_fetch_assoc($result)) {
                                 $idsegment = $row['idsegment'];
                                 $nmsegment = $row['nmsegment'];
                                 $selected = ($idsegment == $customer['idsegment']) ? "selected" : "";
                                 echo "<option value=\"$idsegment\" $selected>$nmsegment</option>";
                              }
                              ?>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="top">T.O.P (Hari)</label>
                           <input type="number" class="form-control" name="top" id="top" value="<?= $customer['top']; ?>" required>
                        </div>
                        <div class="form-group">
                           <label for="sales_referensi">Sales Referensi</label>
                           <input type="text" class="form-control" name="sales_referensi" id="sales_referensi" value="<?= $customer['sales_referensi']; ?>">
                        </div>
                        <div class="form-group">
                           <label for="pajak">Customer Dikenakan Pajak</label>
                           <select class="form-control" name="pajak" id="pajak" required>
                              <option value="1" <?= $customer['pajak'] ? "selected" : ""; ?>>Yes</option>
                              <option value="0" <?= !$customer['pajak'] ? "selected" : ""; ?>>No</option>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="tukarfaktur">Tukar Faktur</label>
                           <select class="form-control" name="tukarfaktur" id="tukarfaktur" required>
                              <option value="1" <?= $customer['tukarfaktur'] ? "selected" : ""; ?>>Yes</option>
                              <option value="0" <?= !$customer['tukarfaktur'] ? "selected" : ""; ?>>No</option>
                           </select>
                        </div>
                        <div class="form-group">
                           <label for="telepon">Telepon</label>
                           <input type="text" class="form-control" name="telepon" id="telepon" value="<?= $customer['telepon']; ?>">
                        </div>
                        <div class="form-group">
                           <label for="email">Email</label>
                           <input type="email" class="form-control" name="email" id="email" value="<?= $customer['email']; ?>">
                        </div>
                        <div class="form-group">
                           <label for="catatan">Catatan</label>
                           <textarea class="form-control" name="catatan" id="catatan"><?= $customer['catatan']; ?></textarea>
                        </div>
                        <button type="submit" name="simpan" class="btn btn-primary">UPDATE</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<?php include "../footer.php" ?>