<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>

<!-- Page specific script -->
<script>
   $(function() {
      // Initialize Select2 Elements
      if ($('.select2').length) {
         $('.select2').select2({
            theme: 'bootstrap4' // Menggunakan tema bootstrap4
         });
      }

      // Initialize DataTables for #example1
      if ($('#example1').length) {
         $("#example1").DataTable({
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            buttons: ["copy", "excel", "pdf", "print", "colvis"]
         }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      }

      // Initialize DataTables for #example2
      if ($('#example2').length) {
         $('#example2').DataTable({
            paging: true,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: true,
            autoWidth: true,
            responsive: true,
         });
      }

      // Initialize Date Range Picker
      if ($('.daterangepicker').length) {
         $('.daterangepicker').daterangepicker();
      }

      // Initialize Input Mask
      if ($('[data-mask]').length) {
         $('[data-mask]').inputmask();
      }

      // Initialize Color Picker
      if ($('.colorpicker').length) {
         $('.colorpicker').colorpicker();
      }

      // Initialize Tempus Dominus for Date Time Picker
      if ($('.datetimepicker').length) {
         $('.datetimepicker').datetimepicker({
            format: 'L'
         });
      }

      // Initialize BS Stepper
      if (document.querySelector('.bs-stepper')) {
         window.stepper = new Stepper(document.querySelector('.bs-stepper'));
      }

      // Initialize DropzoneJS
      if (Dropzone) {
         Dropzone.autoDiscover = false;
      }
   });
</script>
</body>

</html>