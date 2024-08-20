<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "swm";

// membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Set timezone di PHP
date_default_timezone_set('Asia/Jakarta');

// Set timezone di MySQL
$conn->query("SET time_zone = '+07:00'");
