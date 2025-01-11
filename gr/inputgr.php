<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("location: ../verifications/login.php");
    exit();
}

require "../konak/conn.php";
include "grnumber.php";

if (isset($_POST['submit'])) {
    $deliveryat = $_POST['deliveryat'];
    $idsupplier = $_POST['idsupplier'];
    $note = isset($_POST['note']) && trim($_POST['note']) !== '' ? trim($_POST['note']) : '-';
    $idpo = $_POST['idpo']; // Sesuaikan nama kolom dengan tabel po
    $idusers = $_SESSION['idusers'];
    $suppcode = $_POST['suppcode'];
    $idrawmate = $_POST['idrawmate']; // Array idrawmate
    $received_qty = $_POST['received_qty']; // Array qty diterima

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
        $query_grdetail = "INSERT INTO grrawdetail (idgr, idrawmate, qty, orderqty) VALUES (?, ?, ?, ?)";
        $stmt_grdetail = $conn->prepare($query_grdetail);

        if ($stmt_grdetail === false) {
            throw new Exception("Error preparing grrawdetail statement: " . $conn->error);
        }

        $query_stockraw = "INSERT INTO stockraw (idgrrawdetail, idrawmate, qty) VALUES (?, ?, ?)";
        $stmt_stockraw = $conn->prepare($query_stockraw);

        if ($stmt_stockraw === false) {
            throw new Exception("Error preparing stockraw statement: " . $conn->error);
        }

        foreach ($idrawmate as $index => $idraw) {
            $qty_received = $received_qty[$index]; // Ambil qty diterima sesuai index
            $order_qty = isset($order_quantities[$idraw]) ? $order_quantities[$idraw] : 0; // Ambil order_qty berdasarkan idrawmate

            // Masukkan ke tabel grrawdetail
            $stmt_grdetail->bind_param("iiid", $idgr, $idraw, $qty_received, $order_qty);

            if (!$stmt_grdetail->execute()) {
                throw new Exception("Error inserting into grrawdetail: " . $stmt_grdetail->error);
            }

            // Ambil ID grrawdetail terakhir
            $idgrrawdetail = $conn->insert_id;

            // Masukkan ke tabel stockraw
            $stmt_stockraw->bind_param("iii", $idgrrawdetail, $idraw, $qty_received);

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
        $stmt_gr->close();
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
        if (isset($stmtLogActivity)) {
            $stmtLogActivity->close();
        }
        $conn->close();
    }
}
