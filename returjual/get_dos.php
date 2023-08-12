
<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
}
require "../konak/conn.php";
// Mengambil ID pelanggan dari permintaan AJAX
$customer_id = $_GET['customer_id'];

// Mengambil data DO terkait dari database
$query = "SELECT iddo, donumber FROM do WHERE idcustomer = $customer_id ORDER BY donumber DESC";
$result = $conn->query($query);

$dos = array();
if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
      $dos[] = array(
         'iddo' => $row['iddo'],
         'donumber' => $row['donumber']
      );
   }
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($dos);

$conn->close();
?>
