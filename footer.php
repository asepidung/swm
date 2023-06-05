<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
   <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
<?php
$year = date('Y');
?>

<!-- Main Footer -->
<footer class="main-footer">
   <strong>Copyright &copy; <?= $year ?> <a href="https://instagram.com/asep_idung">Idung</a>.</strong>
   <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
   </div>
</footer>
</div>
<!-- ./wrapper -->
<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="<?php echo BASE_PATH; ?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo BASE_PATH; ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo BASE_PATH; ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo BASE_PATH; ?>/dist/js/adminlte.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="<?php echo BASE_PATH; ?>/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/raphael/raphael.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo BASE_PATH; ?>/plugins/chart.js/Chart.min.js"></script>
<!-- Select2 -->
<script src="<?php echo BASE_PATH; ?>/plugins/select2/js/select2.full.min.js"></script>
<!-- date-range-picker -->
<script src="<?php echo BASE_PATH; ?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo BASE_PATH; ?>/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo BASE_PATH; ?>/dist/js/pages/dashboard2.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo BASE_PATH; ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo BASE_PATH; ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo BASE_PATH; ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<!-- <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script> -->
<!-- AdminLTE App -->
<script src="<?php echo BASE_PATH; ?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo BASE_PATH; ?>/dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
   $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()
      //Initialize Select2 Elements
      $('.select2bs4').select2({
         theme: 'bootstrap4'
      })
      $("#example1").DataTable({
         "responsive": true,
         "lengthChange": false,
         "autoWidth": false,
         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
         "paging": true,
         "lengthChange": false,
         "searching": false,
         "ordering": true,
         "info": true,
         "autoWidth": false,
         "responsive": true,
      });
   });
</script>
</body>

</html>