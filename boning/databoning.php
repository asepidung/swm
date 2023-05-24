<?php
require "../konak/conn.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Boning</title>
</head>

<body>
  <table border="1">

    <tr>
      <th>#</th>
      <th>BATCH</th>
      <th>Tgl Killing</th>
      <th>Tgl Boning</th>
      <th>Supplier</th>
      <th>Jml Sapi</th>
      <th>Ttl Weight</th>
      <th>Catatan</th>
      <th>AKSI</th>
    </tr>
    <?php
    $no = 1;
    $ambildata = mysqli_query($conn, "SELECT b.*, p.nmsupplier FROM boning b JOIN supplier p ON b.idsupplier = p.idsupplier ORDER BY b.batchboning DESC");
    while ($tampil = mysqli_fetch_array($ambildata)) {
      $tglkill = date("d-M-Y", strtotime($tampil['tglkill']));
      $tglboning = date("d-M-Y", strtotime($tampil['tglboning']));
    ?>
      <tr>
        <td><?= $no; ?></td>
        <td><?= $tampil['batchboning']; ?></td>
        <td><?= $tglkill; ?></td>
        <td><?= $tglboning; ?></td>
        <td><?= $tampil['nmsupplier']; ?></td>
        <td><?= $tampil['qtysapi']; ?></td>
        <td>1000 Kg</td>
        <td><?= $tampil['catatan']; ?></td>
        <td><a href="#">LIHAT</a> | <a href="#">EDIT</a></td>
      </tr>
    <?php
      $no++;
    }

    ?>

  </table>
</body>

</html>