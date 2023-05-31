<?php
require "../konak/conn.php";
include "../assets/html/header.php";
include "../assets/html/navbar.php";
include "../assets/html/mainsidebar.php";
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-4 mt-3">
          <div class="card">
            <div class="card-body">
              <form method="POST" action="prosesnewboning.php">
                <div class="form-group">
                  <label>Product <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <select class="form-control" name="product" id="product" required>
                      <option value="">--Pilih--</option>
                      <?php
                      $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                      $result = mysqli_query($conn, $query);
                      // Generate options based on the retrieved data
                      while ($row = mysqli_fetch_assoc($result)) {
                        $idbarang = $row['idbarang'];
                        $nmbarang = $row['nmbarang'];
                        echo "<option value=\"$idbarang\">$nmbarang</option>";
                      }
                      ?>
                    </select>
                    <div class="input-group-append">
                      <a href="../master/newbarang.php" class="btn btn-success"><i class="fas fa-plus"></i></a>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Packed Date<span class="text-danger">*</span></label>
                  <div class="input-group date" id="packdate" data-target-input="nearest">
                    <input type="date" class="form-control" name="packdate" id="packdate" required>
                    <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                  </div>
                </div>
                <div class="form-group">
                  <label>Expired Date</span></label>
                  <div class="input-group date" id="expd" data-target-input="nearest">
                    <input type="date" class="form-control" name="exp" id="exp">
                    <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                  </div>
                </div>
                <div class="form-group">
                  <label>Weight & Pcs <span class="text-danger">*</span></label>
                  <div class="input-group date" id="qty" data-target-input="nearest">
                    <div class="row">
                      <div class="col-lg-2">
                        <input type="text" class="form-control mb-1" name="qty" id="qty" autofocus>
                      </div>
                      <div class="col-lg-2">
                        <input type="text" class="form-control mb-1" name="qty" id="qty" autofocus>
                      </div>
                      <div class="col-lg-2">
                        <input type="text" class="form-control mb-1" name="qty" id="qty" autofocus>
                      </div>
                      <div class="col-lg-2">
                        <input type="text" class="form-control mb-1" name="qty" id="qty" autofocus>
                      </div>
                      <div class="col-lg-2">
                        <input type="text" class="form-control mb-1" name="qty" id="qty" autofocus>
                      </div>
                      <div class="col-lg-2">
                        <input type="text" class="form-control mb-1" name="qty" id="qty" autofocus>
                      </div>
                    </div>
                    <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                  </div>
                </div>
                <div class="form-group text-right">
                  <button type="submit" class="btn bg-gradient-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
        <div class="col-lg-8 mt-3">
          <div class="card">
            <div class="card-body">

            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>


  <?php include "../assets/html/footer.php"; ?>