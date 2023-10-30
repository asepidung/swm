<?php $fullname = $_SESSION['fullname']; ?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 ">
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
              <a href="../404.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Repack Import</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../404.php" class="nav-link">
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
              <a href="../tally/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Taly Sheet</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../gr/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Goods Receipt</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../returjual/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Return</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Bound Procces
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../inbound/" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Inbound</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../outbond/" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Outbond</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="../adjustment" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Adjustment</p>
              </a>
            </li>
          </ul>
        </li>
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
                <p>Delivery Order</p>
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
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Purchase Order
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../poproduct/" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>PO Product</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>PO Non Product</p>
                  </a>
                </li>
              </ul>
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
                <p>Invoice</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../404.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Piutang</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-sticky-note"></i>
            <p>
              DATA REPORT
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../stock/" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Stock</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../inv/invoice.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Penjualan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../404.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Utang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../404.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Piutang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Menu lainya nanti</p>
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