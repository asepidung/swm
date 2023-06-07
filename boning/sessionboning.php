
<?php
// Memeriksa apakah nilai-nilai sudah disubmit sebelumnya dan disimpan dalam session
if (isset($_SESSION['packdate'])) {
   $packdateValue = $_SESSION['packdate'];
} else {
   $packdateValue = '';
}

if (isset($_SESSION['exp'])) {
   $expValue = $_SESSION['exp'];
} else {
   $expValue = '';
}

if (isset($_SESSION['product'])) {
   $barangValue = $_SESSION['product'];
} else {
   $barangValue = '';
}

// Memperbarui nilai-nilai saat formulir disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $packdateValue = $_POST['packdate'];
   $expValue = $_POST['exp'];
   $barangValue = $_POST['product'];

   // Menyimpan nilai-nilai dalam session
   $_SESSION['packdate'] = $packdateValue;
   $_SESSION['exp'] = $expValue;
   $_SESSION['product'] = $barangValue;
}
