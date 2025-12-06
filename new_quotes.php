<?php
// new_quotes.php
// Halaman form untuk menambah quote (1 field). Mengirim POST ke store_quotes.php

session_start();
require 'verifications/auth.php'; // pastikan user ter-login; sesuaikan path bila perlu
// require 'konak/conn.php'; // tidak perlu koneksi di halaman form kecuali butuh menampilkan data

// Ambil pesan flash (jika ada)
$success = $_SESSION['flash_success'] ?? null;
$error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Quote - SWM</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        body {
            font-size: 13px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Content wrapper (bagian sederhana, sesuaikan jika ada template) -->
        <div class="content-wrapper">
            <section class="content pt-3">
                <div class="container-fluid">
                    <div class="card card-primary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-plus"></i> Add Your Quote</h3>
                            <div class="card-tools">
                                <a href="index.php" class="btn btn-tool" title="Back to dashboard"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-body">
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                            <?php endif; ?>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                            <form action="store_quotes.php" method="post">
                                <input type="hidden" name="action" value="store_quote">
                                <div class="form-group">
                                    <label for="isiquote">Quote</label>
                                    <textarea id="isiquote" name="isiquote" class="form-control" rows="4" maxlength="1000" required placeholder="Tulis quotes Anda di sini..."></textarea>
                                    <small class="form-text text-muted">Maksimum 1000 karakter.</small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                                    <a href="index.php" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>