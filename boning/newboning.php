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
  <h1>NEW BONING PROJECT</h1>
  <form method="POST" action="prosesnewboning.php">
    <label for="batchboning">BATCH</label>
    <input type="text" name="batchboning" id="batchboning" value="<?= $kodeauto; ?>" readonly>
    <br>
    <label for="tglkill">Tanggal Potong</label>
    <input type="date" name="tglkill" id="tglkill">
    <br>
    <label for="tglboning">Tanggal Boning</label>
    <input type="date" name="tglboning" id="tglboning">
    <br>
    <label for="idsupplier">Supplier Sapi</label>
    <select name="idsupplier" id="idsupplier">
      <option value="">--Pilih Supplier Sapi</option>
      <?php
      $query = "SELECT * FROM supplier ORDER BY nmsupplier ASC";
      $result = mysqli_query($conn, $query);
      // Generate options based on the retrieved data
      while ($row = mysqli_fetch_assoc($result)) {
        $idsupplier = $row['idsupplier'];
        $nmsupplier = $row['nmsupplier'];
        echo "<option value=\"$idsupplier\">$nmsupplier</option>";
      }
      ?>
    </select>
    <br>
    <label for="qtysapi">Jumlah Sapi</label>
    <input type="number" name="qtysapi" id="qtysapi">
    <br>
    <label for="catatan">Catatan</label>
    <textarea name="catatan" id="catatan" cols="30" rows="10"></textarea>
    <br>
    <button type="submit"> PROSES </button>
  </form>

</body>

</html>