<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../verifications/login.php");
    exit;
}

require "../konak/conn.php";
include "../header.php";
include "../navbar.php";
include "../mainsidebar.php";

// Ambil ID dari URL
$idrequest = $_GET['id'] ?? null;

if (!$idrequest) {
    die("Error: Missing request ID.");
}

// Ambil data dari tabel `request`
$query_request = "SELECT * FROM request WHERE idrequest = ?";
$stmt = mysqli_prepare($conn, $query_request);
mysqli_stmt_bind_param($stmt, "i", $idrequest);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows === 0) {
    die("Error: Request not found.");
}

$request = mysqli_fetch_assoc($result);

// Ambil data detail permintaan dari tabel `requestdetail`
$query_details = "SELECT * FROM requestdetail WHERE idrequest = ?";
$stmt_details = mysqli_prepare($conn, $query_details);
mysqli_stmt_bind_param($stmt_details, "i", $idrequest);
mysqli_stmt_execute($stmt_details);
$details_result = mysqli_stmt_get_result($stmt_details);

$request_details = [];
while ($detail = mysqli_fetch_assoc($details_result)) {
    $request_details[] = $detail;
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($stmt_details);

?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col mt-3">
                    <form method="POST" action="update.php">
                        <input type="hidden" name="idrequest" value="<?= htmlspecialchars($idrequest) ?>">
                        
                        <!-- Form utama -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="duedate">Due Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="duedate" id="duedate" value="<?= htmlspecialchars($request['duedate']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="idsupplier">Buy To <span class="text-danger">*</span></label>
                                            <select class="form-control" name="idsupplier" id="idsupplier" required>
                                                <option value="">Pilih Supplier</option>
                                                <?php
                                                $query = "SELECT * FROM supplier ORDER BY nmsupplier ASC";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $selected = $row['idsupplier'] == $request['idsupplier'] ? 'selected' : '';
                                                    echo "<option value=\"{$row['idsupplier']}\" $selected>{$row['nmsupplier']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="other">If Vendor Other</label>
                                            <input type="text" id="other" name="other" class="form-control" value="<?= htmlspecialchars($request['other'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="note">Note</label>
                                            <input type="text" class="form-control" name="note" id="note" value="<?= htmlspecialchars($request['note']) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail item -->
                        <div class="card">
                            <div class="card-body">
                                <div id="items-container">
                                    <?php foreach ($request_details as $detail): ?>
                                        <div class="row item-row">
                                            <div class="col-12 col-md-3">
                                                <div class="form-group">
                                                    <select class="form-control" name="idrawmate[]" required>
                                                        <option value="">--Product--</option>
                                                        <?php
                                                        $query = "SELECT * FROM rawmate ORDER BY nmrawmate ASC";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $selected = $row['idrawmate'] == $detail['idrawmate'] ? 'selected' : '';
                                                            echo "<option value=\"{$row['idrawmate']}\" $selected>{$row['nmrawmate']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-1 mb-2">
                                                <input type="text" name="weight[]" class="form-control text-right" placeholder="Qty" value="<?= htmlspecialchars($detail['qty']) ?>" required>
                                            </div>
                                            <div class="col-6 col-md-2 mb-2">
                                                <input type="text" name="price[]" class="form-control text-right" placeholder="Price" value="<?= htmlspecialchars($detail['price']) ?>" required>
                                            </div>
                                            <div class="col-6 col-md-2 mb-2">
                                                <input type="text" name="amount[]" class="form-control text-right" placeholder="Amount" readonly>
                                            </div>
                                            <div class="col-6 col-md-3 mb-2">
                                                <input type="text" name="notes[]" class="form-control" placeholder="Note" value="<?= htmlspecialchars($detail['notes']) ?>">
                                            </div>
                                            <div class="col mb-2">
                                                <button type="button" class="btn btn-link text-danger btn-remove-item" onclick="removeItem(this)">
                                                    <i class="fas fa-minus-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="row align-items-center mt-3">
                                    <div class="col-12 col-md-2">
                                        <button type="button" class="btn btn-link text-success" onclick="addItem()">
                                            <i class="fas fa-plus-circle"></i> Add Item
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <input type="text" name="xweight" id="xweight" class="form-control text-right" readonly placeholder="Total Qty">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <input type="text" name="xamount" id="xamount" class="form-control text-right" readonly placeholder="Total Amount">
                                    </div>
                                    <div class="col-6 col-md mt-2">
                                        <button type="submit" class="btn btn-primary btn-block" name="update">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="../dist/js/calculatepo.js"></script>
<script>
    function addItem() {
        const itemsContainer = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.className = 'row item-row';
        newRow.innerHTML = `
            <div class="col-12 col-md-3">
                <select class="form-control" name="idrawmate[]" required>
                    <option value="">--Product--</option>
                    <?php
                    $query = "SELECT * FROM rawmate ORDER BY nmrawmate ASC";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['idrawmate']}'>{$row['nmrawmate']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-6 col-md-1">
                <input type="text" name="weight[]" class="form-control text-right" placeholder="Qty" required>
            </div>
            <div class="col-6 col-md-2">
                <input type="text" name="price[]" class="form-control text-right" placeholder="Price" required>
            </div>
            <div class="col-6 col-md-2">
                <input type="text" name="amount[]" class="form-control text-right" placeholder="Amount" readonly>
            </div>
            <div class="col-6 col-md-3">
                <input type="text" name="notes[]" class="form-control" placeholder="Note">
            </div>
            <div class="col">
                <button type="button" class="btn btn-link text-danger btn-remove-item" onclick="removeItem(this)">
                    <i class="fas fa-minus-circle"></i>
                </button>
            </div>
        `;
        itemsContainer.appendChild(newRow);
    }

    function removeItem(button) {
        button.closest('.item-row').remove();
    }

    document.title = "Edit Request";
</script>

<?php include "../footer.php"; ?>
