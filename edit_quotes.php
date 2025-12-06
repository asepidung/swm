<?php
session_start();
require 'verifications/auth.php';
require 'konak/conn.php';

// Ambil ID
$idquote = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idquote <= 0) {
    die("Invalid quote ID.");
}

// Ambil data quote
$stmt = $conn->prepare("SELECT isiquote FROM quotes WHERE idquote = ?");
$stmt->bind_param("i", $idquote);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Quote not found.");
}

$row = $result->fetch_assoc();
$isiquote = $row['isiquote'];

$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Quote</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <div class="content-wrapper pt-3">
            <section class="content">
                <div class="container-fluid">

                    <div class="card card-warning shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit"></i> Edit Quote</h3>
                            <div class="card-tools">
                                <a href="index.php" class="btn btn-tool"><i class="fas fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-body">

                            <?php if (isset($_SESSION['flash_error'])): ?>
                                <div class="alert alert-danger"><?= $_SESSION['flash_error']; ?></div>
                                <?php unset($_SESSION['flash_error']); ?>
                            <?php endif; ?>

                            <form action="update_quotes.php" method="post">
                                <input type="hidden" name="idquote" value="<?= $idquote ?>">

                                <div class="form-group">
                                    <label for="isiquote">Quote</label>
                                    <textarea id="isiquote" name="isiquote" rows="4" maxlength="1000"
                                        class="form-control" required><?= htmlspecialchars($isiquote); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-warning rounded-pill shadow-sm px-3">
                                    <i class="fas fa-save mr-1"></i> Update
                                </button>

                                <a href="index.php" class="btn btn-secondary">Cancel</a>
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