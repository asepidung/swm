<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("location: ../verifications/login.php");
}
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
include "donumber.php";

$iddo = $_GET['iddo'];

$query = "SELECT * FROM do WHERE iddo = '$iddo'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

?>
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col mt-3">
          <form method="POST" action="updatedo.php">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-2">
                    <div class="form-group">
                      <label for="deliverydate">Tgl Kirim <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <input type="date" class="form-control" name="deliverydate" id="deliverydate" value="<?= $row['deliverydate']; ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label for="idcustomer">Customer <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <select class="form-control" name="idcustomer" id="idcustomer" required>
                          <option value="">Pilih Customer</option>
                          <?php
                          $query = "SELECT * FROM customers ORDER BY nama_customer ASC";
                          $result = mysqli_query($conn, $query);
                          // Generate options based on the retrieved data
                          while ($customerRow = mysqli_fetch_assoc($result)) {
                            $idcustomer = $customerRow['idcustomer'];
                            $nama_customer = $customerRow['nama_customer'];
                            $selected = ($idcustomer == $row['idcustomer']) ? "selected" : "";
                            echo "<option value=\"$idcustomer\" $selected>$nama_customer</option>";
                          }
                          ?>
                        </select>
                        <div class="input-group-append">
                          <a href="../customer/newcustomer.php" class="btn btn-dark"><i class="fas fa-plus"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="po">Cust PO</label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="po" id="po" value="<?= $row['po']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="driver">Driver</label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="driver" id="driver" value="<?= $row['driver']; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="plat">Plat Number</label>
                      <div class="input-group">
                        <input type="text" class="form-control" name="plat" id="plat" value="<?= $row['plat']; ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <div class="input-group">
                        <input type="text" class="form-control" name="note" id="note" placeholder="keterangan" value="<?= $row['note']; ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div id="items-container">

                  <!-- bagian dodetail -->
                  <div class="row">
                    <div class="col-1">
                      <div class="form-group">
                        <label for="idgrade">Code</label>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label for="idbarang">Product</label>
                      </div>
                    </div>
                    <div class="col-1">
                      <div class="form-group">
                        <label for="box">Box</label>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group">
                        <label for="weight">Weight</label>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label for="notes">Notes</label>
                      </div>
                    </div>
                  </div>
                  <?php
                  $query_dodetail = "SELECT * FROM dodetail WHERE iddo = '$iddo'";
                  $result_dodetail = mysqli_query($conn, $query_dodetail);
                  while ($row_dodetail = mysqli_fetch_assoc($result_dodetail)) {
                  ?>
                    <div class="row">
                      <div class="col-1">
                        <div class="form-group">
                          <!-- <label for="idgrade">Code</label> -->
                          <div class="input-group">
                            <select class="form-control" name="idgrade[]" id="idgrade">
                              <?php
                              // Query untuk mengambil data dari tabel grade
                              $sql = "SELECT * FROM grade";
                              $result = $conn->query($sql);
                              // Membuat pilihan dalam select box berdasarkan data yang diambil
                              if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                  $selected = ($row['idgrade'] == $row_dodetail['idgrade']) ? "selected" : "";
                                  echo "<option value=\"" . $row["idgrade"] . "\" $selected>" . $row["nmgrade"] . "</option>";
                                }
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <!-- <label for="idbarang">Product</label> -->
                          <div class="input-group">
                            <select class="form-control" name="idbarang[]" id="idbarang" required>
                              <option value="">--Pilih--</option>
                              <?php
                              $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                              $result = mysqli_query($conn, $query);
                              while ($row = mysqli_fetch_assoc($result)) {
                                $idbarang = $row['idbarang'];
                                $nmbarang = $row['nmbarang'];
                                $selected = ($idbarang == $row_dodetail['idbarang']) ? "selected" : "";
                                echo '<option value="' . $idbarang . '" ' . $selected . '>' . $nmbarang . '</option>';
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <div class="form-group">
                          <!-- <label for="box">Box</label> -->
                          <div class="input-group">
                            <input type="number" name="box[]" class="form-control" required value="<?= $row_dodetail['box']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-2">
                        <div class="form-group">
                          <!-- <label for="weight">Weight</label> -->
                          <div class="input-group">
                            <input type="text" name="weight[]" class="form-control text-right" required value="<?= $row_dodetail['weight']; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col">
                        <div class="form-group">
                          <!-- <label for="notes">Notes</label> -->
                          <div class="input-group">
                            <input type="text" name="notes[]" class="form-control" value="<?= $row_dodetail['notes']; ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>

          </form>
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
<!-- </div> -->
<!-- /.content-wrapper -->

<script>
  function calculateTotals() {
    var boxes = document.getElementsByName("box[]");
    var weights = document.getElementsByName("weight[]");
    var xbox = 0;
    var xweight = 0;

    for (var i = 0; i < boxes.length; i++) {
      xbox += parseInt(boxes[i].value) || 0;
      xweight += parseFloat(weights[i].value) || 0;
    }

    document.getElementById("xbox").value = xbox;
    document.getElementById("xweight").value = xweight.toFixed(2);
  }

  // Mengubah judul halaman web
  document.title = "Delivery Order";

  function addItem() {
    var itemsContainer = document.getElementById('items-container');

    // Baris item baru
    var newItemRow = document.createElement('div');
    newItemRow.className = 'item-row';

    // Konten baris item baru
    newItemRow.innerHTML = `
        <div class="row">
            <div class="col-1">
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="idgrade[]" id="idgrade">
                     <?php
                      // Query untuk mengambil data dari tabel grade
                      $sql = "SELECT * FROM grade";
                      $result = $conn->query($sql);

                      // Membuat pilihan dalam select box berdasarkan data yang diambil
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          echo "<option value=\"" . $row["idgrade"] . "\">" . $row["nmgrade"] . "</option>";
                        }
                      }
                      ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="col-4">
               <div class="form-group">
                  <div class="input-group">
                     <select class="form-control" name="idbarang[]" id="idbarang" required>
                        <option value="">--Pilih--</option>
                        <?php
                        $query = "SELECT * FROM barang ORDER BY nmbarang ASC";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                          $idbarang = $row['idbarang'];
                          $nmbarang = $row['nmbarang'];
                          echo '<option value="' . $idbarang . '">' . $nmbarang . '</option>';
                        }
                        ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="col-1">
               <div class="form-group">
                  <div class="input-group">
                     <input type="number" name="box[]" class="form-control" required>
                  </div>
               </div>
            </div>
            <div class="col-2">
               <div class="form-group">
                  <div class="input-group">
                     <input type="text" name="weight[]" class="form-control text-right" required>
                  </div>
               </div>
            </div>
            <div class="col">
               <div class="form-group">
                  <div class="input-group">
                     <input type="text" name="notes[]" class="form-control">
                  </div>
               </div>
            </div>
            <div class="col-1">        
               <button type="button" class="btn btn-link text-danger btn-remove-item" onclick="removeItem(this)">
                  <i class="fas fa-minus-circle"></i>
               </button>
            </div>
         </div>
      `;

    // Tambahkan baris item baru ke dalam container
    itemsContainer.appendChild(newItemRow);
  }

  function removeItem(button) {
    var itemRow = button.closest('.item-row');

    // Hapus baris item
    itemRow.remove();
  }
</script>
<?php
// require "../footnotes.php";
include "../footer.php";
?>