<?php
require "../konak/conn.php";
include "../assets/html/header.php";
include "../assets/html/navbar.php";
include "../assets/html/mainsidebar.php";
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div class="card card-dark mt-3">
            <div class="card-header">
              <h3 class="card-title">Buat Label</h3>
            </div>
            <form method="POST" action="cetaklabel.php">
              <div class="card-body">
                <div class="form-group">
                  <div class="form-group">
                    <label for="tglproduksi">Production Date</label>
                    <input type="date" class="form-control" name="tglproduksi" id="tglproduksi" required>
                  </div>
                  <div class="form-group">
                    <label for="expdate">Expiration Date</label>
                    <input type="date" class="form-control" name="expdate" id="expdate">
                  </div>
                  <label for="product">Product Name</label>
                  <select class="form-control" name="product" id="product" required>
                    <option value="">-- Choose Product --</option>
                    <?php
                    $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                      $idbarang = $row['idbarang'];
                      $nmbarang = $row['nmbarang'];
                      echo "<option value=\"$idbarang\">$nmbarang</option>";
                    }
                    ?>
                  </select>
                  <div class="form-group">
                    <label for="weight">Weight</label>
                    <input type="text" class="form-control" name="weight" id="weight" required autofocus>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include "../assets/html/footer.php" ?>