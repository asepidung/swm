<?php $userid = $_SESSION['userid']; ?>

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
        <a href="#" class="d-block"><?= $userid; ?></a>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
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
              <a href="../boning/databoning.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Boning</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../trading/trading.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Label Trading</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pages/charts/flot.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Repack Import</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pages/charts/inline.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Repack Stock</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../relabel/relabel.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Buat Label</p>
              </a>
            </li>
          </ul>
        </li>
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
              <a href="../do/do.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Delivery Order</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../do." class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Receipt</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Return</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Mutasi & Repacking</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Adjustment</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Stock</p>
              </a>
            </li>
          </ul>
        </li>
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
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Price List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../po" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Order</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Approve Invoice</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
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
                <p>Invoice</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../inv/invoice.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Piutang</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-database"></i>
            <p>
              MASTER DATA
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../barang/barang.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Barang</p>
              </a>
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
              <a href="../segment/segment.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Segment</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="../verifications/logout.php" class="nav-link">
            <i class="nav-icon fas fa-power-off fa-spin text-danger"></i>
            <p class="text-danger">
              <strong>LOGOUT</strong>
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
<!-- Content Wrapper. Contains page content -->