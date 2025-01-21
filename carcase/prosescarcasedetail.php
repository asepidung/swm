<?php
session_start();
if (!isset($_SESSION['login'])) {
   header("location: ../verifications/login.php");
   exit();
}

require "../konak/conn.php";

// Mendapatkan data dari form
$idcarcase = intval($_POST['idcarcase']);
$berat = isset($_POST['berat']) && $_POST['berat'] !== '' ? floatval($_POST['berat']) : null; // Opsional
$breed = $_POST['breed'];
$eartag = $_POST['eartag'];
$carcase1 = floatval($_POST['carcase1']);
$carcase2 = floatval($_POST['carcase2']);
$hides = isset($_POST['hides']) && $_POST['hides'] !== '' ? floatval($_POST['hides']) : null; // Opsional
$tail = isset($_POST['tail']) && $_POST['tail'] !== '' ? floatval($_POST['tail']) : null; // Opsional

// Rentang angka yang logis
$maxWeight = 1000.00; // Maksimal untuk berat
$maxPartWeight = 200.00; // Maksimal untuk karkas
$maxHidesTail = 100.00; // Maksimal untuk hides dan tail
$minWeight = 0.01; // Minimal untuk semua input (kecuali berat, hides, dan tail yang bisa kosong)

// Validasi berat (opsional)
if ($berat !== null && ($berat > $maxWeight || $berat < $minWeight)) {
   die("Error: Berat tidak masuk akal! Periksa kembali input Anda.");
}

// Validasi carcase1
if ($carcase1 > $maxPartWeight || $carcase1 < $minWeight) {
   die("Error: Karkas A tidak masuk akal! Periksa kembali input Anda.");
}

// Validasi carcase2
if ($carcase2 > $maxPartWeight || $carcase2 < $minWeight) {
   die("Error: Karkas B tidak masuk akal! Periksa kembali input Anda.");
}

// Validasi hides (opsional)
if ($hides !== null && ($hides > $maxHidesTail || $hides < $minWeight)) {
   die("Error: Kulit tidak masuk akal! Periksa kembali input Anda.");
}

// Validasi tail (opsional)
if ($tail !== null && ($tail > $maxHidesTail || $tail < $minWeight)) {
   die("Error: Tail tidak masuk akal! Periksa kembali input Anda.");
}

// Menyiapkan query untuk menyimpan data ke tabel carcasedetail
$query = "INSERT INTO carcasedetail (idcarcase, berat, eartag, carcase1, carcase2, hides, tail, breed) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param(
   "idsdddds",
   $idcarcase,
   $berat,
   $eartag,
   $carcase1,
   $carcase2,
   $hides,
   $tail,
   $breed
);

if ($stmt->execute()) {
   // Dapatkan id detail carcase yang baru
   $_SESSION['last_iddetail'] = $stmt->insert_id;
   $_SESSION['breed'] = $breed; // Simpan breed ke session

   // Cek tombol mana yang ditekan
   if (isset($_POST['simpan']) && $_POST['simpan'] === 'save') {
      // Jika tombol "Simpan", arahkan ke halaman datacarcase.php
      header("Location: datacarcase.php");
      exit();
   } elseif (isset($_POST['next']) && $_POST['next'] === 'next') {
      // Jika tombol "Next", lanjutkan ke halaman berikutnya
      header("Location: carcasedetail.php?idcarcase=$idcarcase");
      exit();
   }
} else {
   echo "Error: " . $stmt->error;
}
$stmt->close();
