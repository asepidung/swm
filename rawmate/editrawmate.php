<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
require "kdrawmateunik.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Mendapatkan idrawmate dari URL
$idrawmate = $_GET['idrawmate'];

// Mengambil data rawmate dari database berdasarkan idrawmate
$query = "SELECT * FROM rawmate WHERE idrawmate = '$idrawmate'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

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
              <h3 class="card-title">Edit MATERIAL</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="proseseditrawmate.php">
              <div class=" card-body">
                <div class="form-group">
                  <label for="kdrawmate">Kode</label>
                  <input type="hidden" name="idrawmate" value="<?= $idrawmate ?>" ?>
                  <input type="text" class="form-control" name="kdrawmate" id="kdrawmate" value="<?= $row['kdrawmate']; ?>" readonly>
                </div>
                <div class="form-group">
                  <label for="nmrawmate">Nama Product <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nmrawmate" id="nmrawmate" value="<?= $row['nmrawmate']; ?>">
                </div>
              </div>
              <div class="form-group mr-3 text-right">
                <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-level-up-alt"></i> Update</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </section>
</div><!-- /.container-fluid -->
<!-- /.content-wrapper -->

<?php
include "../footer.php";
include "../footnote.php";
?>