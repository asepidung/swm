<?php
require "../konak/conn.php";
// require "kodelabelunik.php";
include "../assets/html/header.php";
include "../assets/html/navbar.php";
include "../assets/html/mainsidebar.php";
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <!-- Content Header (Page header) -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="card card-dark mt-3">
            <div class="card-header">
              <h3 class="card-title">Buat Label</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="cetaklabel.php">
              <div class=" card-body">
                <div class="form-group">
                  <label for="batchboning">BATCH</label>
                  <input type="text" class="form-control" name="batchboning" id="batchboning" value="<?= $kodeauto; ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Supplier</label>
                  <div class="input-group">
                    <select class="form-control" name="idsupplier" id="idsupplier">
                      <option value="">Pilih Disini</option>
                      <?php
                      $query = "SELECT * FROM supplier ORDER BY nmsupplier ASC";
                      $result = mysqli_query($conn, $query);
                      // Generate options based on the retrieved data
                      while ($row = mysqli_fetch_assoc($result)) {
                        $idsupplier = $row['idsupplier'];
                        $nmsupplier = $row['nmsupplier'];
                        echo "<option value=\"$idsupplier\">$nmsupplier</option>";
                      }
                      ?>
                    </select>
                    <div class="input-group-append">
                      <a href="../master/supplier/newsupplier.php" class="btn btn-primary">Tambah Supplier</a>
                    </div>
                  </div>
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

  <?php include "../assets/html/footer.php" ?>