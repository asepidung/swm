<?php
$fullname = $_SESSION['fullname'];
$idusers = $_SESSION['idusers'];

// Ambil data role dari database berdasarkan iduser
$query = "SELECT * FROM role WHERE idusers = $idusers";
$result = mysqli_query($conn, $query);
$role = mysqli_fetch_assoc($result);
include "notifcount.php";

?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="../index.php" class="brand-link">
    <img src="../dist/img/logoSWM.png" alt="SWM Logo" class="brand-image">
    <span class="brand-text font-weight-light">WIJAYA MEAT</span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= $fullname; ?></a>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <?php if ($role['produksi'] == 1) : ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>
                PRODUKSI
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../carcase/datacarcase.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Carcase</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../boning/databoning.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Boning</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../repack" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Repack
                    <span class="badge badge-info right"><?= $repackCount ?></span>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../relabel/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Relabel</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($role['warehouse'] == 1) : ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                WAREHOUSE
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../tally/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Taly Sheet
                    <span class="badge badge-info right"><?= $drafttally ?></span>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Goods Receipt
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="../grbeef" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Daging</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../gr" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Non Daging</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="../returjual/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Return</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../mutasi/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mutasi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../stock/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../stockraw/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Raw</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../stock/tofroz.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>> 60 Days</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../stocktake" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Take</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($role['distributions'] == 1) : ?>
          <li class="nav-item">
            <a href="../404.php" class="nav-link">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                DISTRIBUTIONS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../do/do.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Delivery Order
                    <span class="badge badge-info right"><?= $draftdo ?></span>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../do/dodetail.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Do Detail</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../plandev/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Schedule</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-hand-holding-usd"></i>
            <p>
              REQUISITION
              <?php if ($idusers == 13): ?>
                <?php if ($TotalRequest > 0): ?>
                  <span class="badge badge-warning right"><?= $TotalRequest ?></span>
                <?php endif; ?>
                <?php if ($TotalOrdering > 0): ?>
                  <span class="badge badge-primary right"><?= $TotalOrdering ?></span>
                <?php endif; ?>
              <?php elseif ($idusers == 15): ?>
                <?php if ($TotalWaiting > 0): ?>
                  <span class="badge badge-warning right"><?= $TotalWaiting ?></span>
                <?php endif; ?>
              <?php endif; ?>
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../requisitionbeef/index.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Daging
                  <?php if ($idusers == 13 && $CountRequest > 0): ?>
                    <span class="badge badge-warning right"><?= $CountRequest ?></span>
                  <?php elseif ($idusers == 15 && $CountWaiting > 0): ?>
                    <span class="badge badge-warning right"><?= $CountWaiting ?></span>
                  <?php elseif ($idusers == 13 && $CountOrdering > 0): ?>
                    <span class="badge badge-primary right"><?= $CountOrdering ?></span>
                  <?php endif; ?>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../requisition/index.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Non Daging
                  <?php if ($idusers == 13 && $CountRequestNonDaging > 0): ?>
                    <span class="badge badge-warning right"><?= $CountRequestNonDaging ?></span>
                  <?php elseif ($idusers == 15 && $CountWaitingNonDaging > 0): ?>
                    <span class="badge badge-warning right"><?= $CountWaitingNonDaging ?></span>
                  <?php elseif ($idusers == 13 && $CountOrderingNonDaging > 0): ?>
                    <span class="badge badge-primary right"><?= $CountOrderingNonDaging ?></span>
                  <?php endif; ?>
                </p>
              </a>
            </li>
          </ul>
        </li>

        <?php if ($role['purchase_module'] == 1) : ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                PURCHASE MODULE
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../pobeef/" class="nav-link">
                  <i class="far fa-dot-circle nav-icon"></i>
                  <p>PO Daging</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../po/" class="nav-link">
                  <i class="far fa-dot-circle nav-icon"></i>
                  <p>PO Non Daging</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($role['sales'] == 1) : ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-bag"></i>
              <p>
                SALES
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../pricelist/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Price List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../salesorder" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Order</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../404.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Approve Invoice</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($role['finance'] == 1) : ?>
          <li class="nav-item">
            <a href="../404.php" class="nav-link">
              <i class="nav-icon fas fa-cart-plus"></i>
              <p>
                FINANCE
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../inv/invoice.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Invoice
                    <span class="badge badge-info right"><?= $draftinvoice ?></span>
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../inv/invoicedetail.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Invoice Detail</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($role['data_report'] == 1) : ?>
          <li class="nav-item">
            <a href="../404.php" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                DATA REPORT
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../sales/order" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Order</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../404.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Balance Sheet</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../404.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profit / Loss</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../404.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Detail</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../log.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Log Activity</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($role['master_data'] == 1) : ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                MASTER DATA
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Barang</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="../barang/barang.php" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Product</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../rawmate/" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>NoN Product</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="../rawcategory/" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Raw Category</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="../supplier/supplier.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Supplier</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../customer/customer.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../group/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Group Customer</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../segment/segment.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Segment</p>
                </a>
              </li>
              <?php if ($idusers == 1) { ?>
                <li class="nav-item">
                  <a href="../user/user.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Users</p>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a href="../verifications/logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              LOGOUT
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>