<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Daftar User</title>
   <link rel="icon" href="../dist/img/favicon.png" type="image/x-icon">
   <!-- Google Font: Source Sans Pro -->
   <link rel="stylesheet" href="../https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
   <!-- icheck bootstrap -->
   <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">
   <div class="register-box">
      <div class="register-logo">
         <a href="index2.html"><b>SWM</b>-cms</a>
      </div>

      <div class="card">
         <div class="card-body register-card-body">
            <p class="login-box-msg">Regist New User/Employee</p>

            <form action="newuser.php" method="post">
               <div class="input-group mb-3">
                  <input type="text" class="form-control" name="userid" id="userid" placeholder="Buat Username Login Anda" required>
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <i class="fas fa-user-check"></i>
                     </div>
                  </div>
               </div>
               <div class="input-group mb-3">
                  <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Nama Lengkap" required>
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <i class="fas fa-file-signature"></i>
                     </div>
                  </div>
               </div>
               <div class="input-group mb-3">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                     </div>
                  </div>
               </div>
               <div class="input-group mb-3">
                  <input type="password" class="form-control" name="password2" id="password2" placeholder="Ulangi Password" required>
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <!-- /.col -->
                  <div class="col-4">
                     <button type="submit" class="btn btn-primary btn-block">Register</button>
                  </div>
                  <!-- /.col -->
               </div>
            </form>
         </div>
         <!-- /.form-box -->
      </div><!-- /.card -->
   </div>
   <!-- /.register-box -->
   <script>
      var password = document.getElementById("password");
      var password2 = document.getElementById("password2");

      function validatePassword() {
         if (password.value !== password2.value) {
            password2.setCustomValidity("Passwords do not match");
         } else {
            password2.setCustomValidity("");
         }
      }

      password.onchange = validatePassword;
      password2.onkeyup = validatePassword;
   </script>
   <!-- jQuery -->
   <script src="../plugins/jquery/jquery.min.js"></script>
   <!-- Bootstrap 4 -->
   <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
   <!-- AdminLTE App -->
   <script src="../dist/js/adminlte.min.js"></script>
</body>

</html>