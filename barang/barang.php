<?php
require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <!-- <h1 class="m-0">DATA BONING</h1> -->
               <a href="newbarang.php"><button type="button" class="btn btn-info"> Product Baru</button></a>
            </div><!-- /.col -->
         </div><!-- /.row -->
      </div><!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped table-sm">
                        <thead class="text-center">
                           <tr>
                              <th rowspan="2">#</th>
                              <th rowspan="2">Kode Product</th>
                              <th rowspan="2">Nama Product</th>
                              <th colspan="2">G. Jonggol</th>
                              <th colspan="2">G. Perum</th>
                           </tr>
                           <tr>
                              <th>Good</th>
                              <th>Grade</th>
                              <th>Good</th>
                              <th>Grade</th>
                           </tr>
                        </thead>
                        <tbody>
                           </tr>
                           <?php
                           // $query_total_sapi = "SELECT SUM(qtysapi) AS total_sapi FROM boning";
                           // $result_total_sapi = mysqli_query($conn, $query_total_sapi);
                           // $row_total_sapi = mysqli_fetch_assoc($result_total_sapi);
                           // $total_sapi = $row_total_sapi['total_sapi'];
                           $no = 1;
                           $ambildata = mysqli_query($conn, "SELECT * FROM barang ORDER BY nmbarang ASC");
                           while ($tampil = mysqli_fetch_array($ambildata)) {
                           ?>
                              <tr>
                                 <td><?= $no; ?></td>
                                 <td><?= $tampil['kdbarang']; ?></td>
                                 <td><?= $tampil['nmbarang']; ?></td>
                                 <td>stock gja</td>
                                 <td>stock gjb</td>
                                 <td>stock pja</td>
                                 <td>stock pjb</td>
                              </tr>
                           <?php
                              $no++;
                           }
                           ?>
                        </tbody>
                        <tfoot>
                        </tfoot>
                     </table>
                  </div>
                  <!-- /.card-body -->
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

   <?php include "../footer.php" ?>