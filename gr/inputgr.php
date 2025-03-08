<?php
require "../verifications/auth.php";
require "../konak/conn.php";
include "grnumber.php";
include "idtransaksi.php"; // ID Transaksi di-include dari file ini

if (isset($_POST['submit'])) {
    $deliveryat = $_POST['deliveryat'];
    $idsupplier = $_POST['idsupplier'];
    $note = isset($_POST['note']) && trim($_POST['note']) !== '' ? trim($_POST['note']) : '-';
    $idpo = $_POST['idpo']; // Sesuaikan nama kolom dengan tabel po
    $idusers = $_SESSION['idusers'];
    $suppcode = $_POST['suppcode'];
    $idrawmate = $_POST['idrawmate']; // Array idrawmate
    $received_qty = $_POST['received_qty']; // Array qty diterima

    // Pastikan idrawmate dan received_qty memiliki data yang valid
    if (empty($idrawmate) || empty($received_qty)) {
        echo "Error: Invalid input for rawmate or received quantities.";
        exit();
    }

    // Mulai transaksi
    $conn->autocommit(false);

    try {
        // Query INSERT untuk tabel grraw
        $query_gr = "INSERT INTO grraw (grnumber, receivedate, idsupplier, note, idusers, idpo, suppcode) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_gr = $conn->prepare($query_gr);

        if ($stmt_gr === false) {
            throw new Exception("Error preparing insert statement: " . $conn->error);
        }

        // Bind parameter dan eksekusi
        $stmt_gr->bind_param("ssisiis", $gr, $deliveryat, $idsupplier, $note, $idusers, $idpo, $suppcode);

        if (!$stmt_gr->execute()) {
            throw new Exception("Error executing insert statement: " . $stmt_gr->error);
        }

        // Ambil ID GR terakhir yang dimasukkan
        $idgr = $conn->insert_id;

        // Ambil Order Quantity dari tabel podetail
        $query_podetail = "SELECT idrawmate, qty AS order_qty FROM podetail WHERE idpo = ?";
        $stmt_podetail = $conn->prepare($query_podetail);

        if ($stmt_podetail === false) {
            throw new Exception("Error preparing podetail query: " . $conn->error);
        }

        $stmt_podetail->bind_param("i", $idpo);
        $stmt_podetail->execute();
        $result_podetail = $stmt_podetail->get_result();

        // Buat array untuk menyimpan order_qty berdasarkan idrawmate
        $order_quantities = [];
        while ($row = $result_podetail->fetch_assoc()) {
            $order_quantities[$row['idrawmate']] = $row['order_qty'];
        }

        // Loop untuk memasukkan data ke tabel grrawdetail dan stockraw
        $query_grdetail = "INSERT INTO grrawdetail (idgr, idrawmate, qty, orderqty, idtransaksi) VALUES (?, ?, ?, ?, ?)";
        $stmt_grdetail = $conn->prepare($query_grdetail);

        if ($stmt_grdetail === false) {
            throw new Exception("Error preparing grrawdetail statement: " . $conn->error);
        }

        $query_stockraw = "INSERT INTO stockraw (idrawmate, qty, idtransaksi) VALUES (?, ?, ?)";
        $stmt_stockraw = $conn->prepare($query_stockraw);

        if ($stmt_stockraw === false) {
            throw new Exception("Error preparing stockraw statement: " . $conn->error);
        }

        foreach ($idrawmate as $index => $idraw) {
            $qty_received = $received_qty[$index]; // Ambil qty diterima sesuai index
            $order_qty = isset($order_quantities[$idraw]) ? $order_quantities[$idraw] : 0; // Ambil order_qty berdasarkan idrawmate

            // Masukkan ke tabel grrawdetail
            $stmt_grdetail->bind_param("iiidi", $idgr, $idraw, $qty_received, $order_qty, $idtransaksi);

            if (!$stmt_grdetail->execute()) {
                throw new Exception("Error inserting into grrawdetail: " . $stmt_grdetail->error);
            }

            // Masukkan ke tabel stockraw
            $stmt_stockraw->bind_param("iis", $idraw, $qty_received, $idtransaksi);

            if (!$stmt_stockraw->execute()) {
                throw new Exception("Error inserting into stockraw: " . $stmt_stockraw->error);
            }
        }

        // Setelah berhasil melakukan insert, lakukan update pada tabel po
        $query_update = "UPDATE po SET stat = 1 WHERE idpo = ?";
        $stmt_update = $conn->prepare($query_update);

        if ($stmt_update === false) {
            throw new Exception("Error preparing update statement: " . $conn->error);
        }

        $stmt_update->bind_param("i", $idpo);

        if (!$stmt_update->execute()) {
            throw new Exception("Error executing update statement: " . $stmt_update->error);
        }

        // Query untuk mendapatkan idrequest dari tabel po
        $query_idrequest = "SELECT idrequest FROM po WHERE idpo = ?";
        $stmt_idrequest = $conn->prepare($query_idrequest);

        if ($stmt_idrequest === false) {
            throw new Exception("Error preparing idrequest query: " . $conn->error);
        }

        $stmt_idrequest->bind_param("i", $idpo);
        $stmt_idrequest->execute();
        $result_idrequest = $stmt_idrequest->get_result();

        if ($result_idrequest->num_rows > 0) {
            $row = $result_idrequest->fetch_assoc();
            $idrequest = $row['idrequest'];

            // Update tabel request untuk set stat menjadi 'Completed'
            $query_update_request = "UPDATE request SET stat = 'Completed' WHERE idrequest = ?";
            $stmt_update_request = $conn->prepare($query_update_request);

            if ($stmt_update_request === false) {
                throw new Exception("Error preparing update request statement: " . $conn->error);
            }

            $stmt_update_request->bind_param("i", $idrequest);

            if (!$stmt_update_request->execute()) {
                throw new Exception("Error executing update request statement: " . $stmt_update_request->error);
            }
        } else {
            throw new Exception("idrequest not found for the given idpo: " . $idpo);
        }

        // Insert log activity ke tabel logactivity
        $event = "Buat GR RAW";
        $queryLogActivity = "INSERT INTO logactivity (iduser, event, docnumb) VALUES (?, ?, ?)";
        $stmtLogActivity = $conn->prepare($queryLogActivity);
        if (!$stmtLogActivity) {
            throw new Exception("Error preparing log activity statement: " . $conn->error);
        }

        $stmtLogActivity->bind_param('iss', $idusers, $event, $gr);

        if (!$stmtLogActivity->execute()) {
            throw new Exception("Error executing log activity statement: " . $stmtLogActivity->error);
        }

        // Commit transaksi jika semua query berhasil dieksekusi
        $conn->commit();

        // Redirect ke halaman index jika berhasil
        header("location: index.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        $conn->autocommit(true);
        if (isset($stmt_gr)) {
            $stmt_gr->close();
        }
        if (isset($stmt_grdetail)) {
            $stmt_grdetail->close();
        }
        if (isset($stmt_stockraw)) {
            $stmt_stockraw->close();
        }
        if (isset($stmt_podetail)) {
            $stmt_podetail->close();
        }
        if (isset($stmt_update)) {
            $stmt_update->close();
        }
        if (isset($stmt_idrequest)) {
            $stmt_idrequest->close();
        }
        if (isset($stmt_update_request)) {
            $stmt_update_request->close();
        }
        if (isset($stmtLogActivity)) {
            $stmtLogActivity->close();
        }
        $conn->close();
    }
}
