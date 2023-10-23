<?php
require "../konak/conn.php";

if (isset($_GET['idcustomer'])) {
    $idcustomer = $_GET['idcustomer'];

    // Lakukan kueri database untuk mendapatkan donumber berdasarkan ID customer
    $query = "SELECT iddo, donumber FROM do WHERE idcustomer = $idcustomer";
    $result = mysqli_query($conn, $query);
    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
}
