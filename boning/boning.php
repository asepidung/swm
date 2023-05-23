<?php
require "../konak/conn.php";
include "kodebatchboning.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BONING</title>
</head>

<body>
  <form method="POST" action="inputboning.php">
    <label for="batchboning">BATCH</label>
    <input type="text" name="batcboning" id="batchboning" value="<?= $kodeauto; ?>" readonly>
    <br>
    <label for="tglkilling">Tanggal Potong</label>
    <input type="date" name="tglkilling" id="tglkilling">
    <br>
    <label for="tglboning">Tanggal Boning</label>
    <input type="date" name="tglboning" id="tglboning">
    <br>
    <label for="pemasok">Supplier Sapi</label>
    <select name="pemasok" id="pemasok">
      <option value="">--Pilih Supplier Sapi</option>
      <option value="">H. DONI</option>
    </select>
    <br>
    <label for="qtysapi">Jumlah Sapi</label>
    <input type="number" name="qtysapi" id="qtysapi">
    <br>
    <input type="submit" value="SUBMIT">
  </form>

</body>

</html>